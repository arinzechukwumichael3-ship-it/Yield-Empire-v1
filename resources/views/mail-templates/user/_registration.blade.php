<x-email.shell
  :title="'Welcome to YieldEmpire'"
  :preheader="'Your verification code is ' . ($code ?? '')"
>
  <p class="greeting" style="margin:0 0 16px;color:#0b1f4d;font-size:18px;font-weight:700;">Hi {{ $name }},</p>

  <p class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    Thank you for choosing YieldEmpire for your financial technology needs. Your account has been created and is almost ready to use.
  </p>

  <p class="lead" style="margin:0 0 8px;color:#475569;font-size:15px;line-height:1.65;">
    Use the verification code below to confirm your email address:
  </p>

  <div class="otp-wrap" style="text-align:center;margin:22px 0;letter-spacing:12px;">
    <span class="code-pill" style="display:inline-block;background:#0b1f4d;color:#ffffff;font-size:22px;font-weight:800;letter-spacing:4px;padding:12px 24px;border-radius:10px;font-family:Consolas,Menlo,monospace;">{{ $code }}</span>
  </div>

  <p class="muted" style="color:#64748b;margin:18px 0 0;font-size:13px;line-height:1.6;">
    This code expires in 15 minutes. If you did not create a YieldEmpire account, you can safely ignore this email &mdash; no account will be created.
  </p>
</x-email.shell>
