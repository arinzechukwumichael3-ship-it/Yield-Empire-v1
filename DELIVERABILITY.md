# YieldEmpire Email System — Redesign & Deliverability Notes

## What was already in place (verified 2026-08-14)
- Shared design system: `resources/views/components/email/shell.blade.php`,
  `resources/views/components/email/styles.blade.php`.
- Notification layer (all `MailMessage` notifications) renders through the on-brand
  `resources/views/vendor/mail/html/{layout,message,header,footer}.blade.php` +
  `themes/default.css`. `layout.blade.php` correctly echoes `{{ $css ?? '' }}`.
- Layer-1 Mailables (`UserRegister`, `UserConfirmMail`, `UserForgotPasswordCode`,
  `UserEmail`, `UserGroupEmail`) wrap `<x-email.shell>` with a matching `*_text`
  plain-text twin → multipart HTML+text (spam-filter plus).
- `MessageSending` listener (`app/Listeners/AddDeliverabilityHeaders.php`) appends
  `List-Unsubscribe` + `List-Unsubscribe-Post: List-Unsubscribe=One-Click` (RFC 8058).
- Signed unsubscribe route + controller + `users.unsubscribed_at` column + migration.
- CSRF exempt for `email/unsubscribe/*`.

## Changes made this pass (fix the OTP + welcome emails)
1. **OTP email** (`app/Notifications/User/Auth/SendAuthorizationCode.php`)
   - Removed `->bcc('support@yieldempire.org')` (BCC to self is a spam signal).
   - Subject changed from "Account Authorization" → "Your YieldEmpire verification code".
   - Added a visible unsubscribe link in the body (matches the List-Unsubscribe header).
2. **Welcome email (international details)** — replaced inline-html `MailMessage` with a
   real branded Mailable so it gets the shared design system + plain-text twin:
   - New: `app/Mail/WelcomeNotificationMail.php`
   - New views: `mail-templates/user/_welcome_international.blade.php` (+ `_text`).
   - `app/Notifications/User/Auth/WelcomeNotification.php` and
     `app/Notifications/WelcomeEmail.php` now wrap that Mailable (synchronous send,
     no queue — so a down queue worker can't silently drop the welcome mail).
   - Both carry a visible unsubscribe link.

## The dominant spam cause = DNS (NOT code)
SPF did not include Resend; DMARC was missing. See `DNS-DELIVERABILITY-FIX.md` for the
exact Cloudflare records to add. Until SPF + DMARC are fixed, emails will keep hitting spam
even with perfect templates.

## Deploy checklist
- `php artisan migrate`  (adds `unsubscribed_at`)
- `php artisan view:clear && php artisan config:clear && php artisan route:clear`
- Add the SPF + DMARC records in Cloudflare (see DNS-DELIVERABILITY-FIX.md)
- Verify with mail-tester.com (target ≥ 9/10) and a real Gmail/Outlook inbox test.
