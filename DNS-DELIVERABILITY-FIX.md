# YieldEmpire Email Deliverability — DNS Fix (THE SPAM FIX)

Your emails were landing in spam primarily because of **DNS authentication**, not code.
The code (branded templates + List-Unsubscribe headers + plain-text twins) was already
in good shape. Resend's domain is "verified" in the dashboard (DKIM is set up), but:

- **SPF did NOT include Resend** → every Resend-sent email fails SPF (hard fail `~all`).
- **DMARC record was missing entirely** → mailbox providers have no policy, so they
  lean toward the spam folder.

Live lookups (run 2026-08-14):
- SPF : `v=spf1 include:spf.efwd.registrar-servers.com ~all`   ❌ (only Namecheap forwarding)
- DKIM: `resend._domainkey.yieldempire.org`                    ✅ present
- DMARC:`_dmarc.yieldempire.org`                               ❌ missing
- NS  : aitana.ns.cloudflare.com / dimitris.ns.cloudflare.com  → edit DNS in **Cloudflare**

====================================================================
STEP 1 — Fix SPF (add Resend)   ← most important change
====================================================================
In Cloudflare → DNS → add/edit the TXT record for `@` (the root domain).

Current:  v=spf1 include:spf.efwd.registrar-servers.com ~all
Replace:  v=spf1 include:spf.efwd.registrar-servers.com include:amazonses.com ~all

Wait — use Resend's own include, NOT amazonses. Resend sends through Amazon SES
under the hood, and Resend publishes its SPF include as `include:amazonses.com`.
(Resend's docs: "Add this TXT record: `v=spf1 include:amazonses.com ~all`".)
Since you already have Namecheap forwarding, keep both:

    Type: TXT
    Name: @
    Content: v=spf1 include:spf.efwd.registrar-servers.com include:amazonses.com ~all
    TTL: Auto

NOTE: SPF allows at most 10 DNS lookups total and only ONE SPF TXT record on the
root domain. Do NOT create a second SPF record — edit the existing one (above).

If you ever stop using Namecheap forwarding, simplify to:
    v=spf1 include:amazonses.com ~all

====================================================================
STEP 2 — Add DMARC
====================================================================
Cloudflare → DNS → add:

    Type: TXT
    Name: _dmarc
    Content: v=DMARC1; p=none; rua=mailto:dmarc@yieldempire.org
    TTL: Auto

Start with `p=none` (monitor only — does not block anything, but tells Gmail/Outlook
you are serious and improves trust). After 2–4 weeks of clean DMARC reports, you can
tighten to `p=quarantine` then `p=reject`.

====================================================================
STEP 3 — Confirm DKIM (already done in Resend)  ✅
====================================================================
Resend already added the DKIM CNAME when you verified `yieldempire.org`. Leave it.
Verify it resolves: `dig +short TXT resend._domainkey.yieldempire.org` (should return a `p=...`).

====================================================================
STEP 4 — After the DNS change, verify
====================================================================
1. Wait 5–30 min for DNS to propagate (Cloudflare is usually < 1 min).
2. Send a real OTP + welcome email and inspect the raw headers:
   - `Authentication-Results:` should now show `spf=pass`, `dkim=pass`, `dmarc=pass`.
   - `List-Unsubscribe:` and `List-Unsubscribe-Post: List-Unsubscribe=One-Click` present.
3. Run https://www.mail-tester.com (target score 9/10 or higher).
4. Confirm a real send lands in the Inbox (not Spam) at both Gmail and Outlook.

====================================================================
WHY THIS WORKS
====================================================================
- SPF pass means the receiving server accepts that Resend is allowed to send for
  yieldempire.org. Without it, Resend-sent mail is "not authorized" → spam.
- DMARC pass (even `p=none`) signals a published policy → providers trust the domain.
- DKIM (already present) proves the message wasn't altered in transit.
- List-Unsubscribe (already in code) lowers complaint rates, the #1 spam driver.
- Plain-text + branded HTML (already in code) keeps content signals clean.

Code side is handled. DNS (Steps 1–2) you do in Cloudflare — it's a 2-minute change.
