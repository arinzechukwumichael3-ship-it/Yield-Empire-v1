<x-email.shell
  :title="'Reset Your Admin Password - ' . ($site_name ?? 'YieldEmpire')"
  :preheader="'Reset your YieldEmpire admin password.'"
>
  <p class="greeting" style="margin:0 0 16px;color:#0b1f4d;font-size:18px;font-weight:700;">Hello,</p>

  <p class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    We received a request to reset the password for your admin account
    @if(!empty($site_name)) for <strong style="color:#0b1f4d;">{{ $site_name }}</strong> @endif.
    Click the button below to choose a new password. For your security, this link will expire soon.
  </p>

  <table class="action" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin:28px auto;text-align:center;width:100%;">
    <tr>
      <td align="center">
        <a href="{{ $reset_url ?? 'javascript:void(0)' }}" class="button button-primary" target="_blank" rel="noopener"
           style="background:linear-gradient(135deg,#3b5bdb,#5f3dc4);color:#ffffff;text-decoration:none;font-weight:700;font-size:15px;padding:14px 32px;border-radius:10px;display:inline-block;box-shadow:0 10px 22px rgba(59,91,219,0.35);">Reset Password</a>
      </td>
    </tr>
  </table>

  <p class="muted" style="color:#94a3b8;margin:22px 0 0;font-size:13px;line-height:1.6;">
    If you did not request this change, you can safely ignore this email &mdash; your password will remain unchanged.
  </p>
  <p style="margin:18px 0 0;color:#94a3b8;font-size:12px;line-height:1.6;">Thanks,<br>The YieldEmpire Security Team</p>
</x-email.shell>
