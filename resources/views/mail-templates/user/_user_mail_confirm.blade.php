<x-email.shell
  :title="'Email Confirmed - YieldEmpire'"
  :preheader="'Your email ' . ($email ?? '') . ' has been confirmed.'"
>
  <p class="greeting" style="margin:0 0 16px;color:#0b1f4d;font-size:18px;font-weight:700;">Hi {{ $name }},</p>

  <p class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    Your email address <strong style="color:#0b1f4d;">{{ $email }}</strong> has been successfully confirmed on your YieldEmpire account.
  </p>

  <div class="panel" style="margin:20px 0;">
    <div class="panel-content" style="background-color:#f1f5ff;border-left:4px solid #3b5bdb;border-radius:0 12px 12px 0;color:#334155;padding:18px 20px;">
      <p style="margin:0;color:#334155;font-size:14px;line-height:1.7;">You now have full access to all YieldEmpire features including:</p>
      <ul style="margin:8px 0 0;padding-left:20px;color:#334155;font-size:14px;line-height:1.8;">
        <li>Virtual Cards</li>
        <li>International Transfers</li>
        <li>Crypto Deposits &amp; Withdrawals</li>
        <li>Real-time Banking &amp; Investments</li>
      </ul>
    </div>
  </div>

  <p class="muted" style="color:#64748b;margin:18px 0 0;font-size:13px;line-height:1.6;">
    If you did not authorize this change, please contact our support team immediately at support@yieldempire.org.
  </p>
</x-email.shell>
