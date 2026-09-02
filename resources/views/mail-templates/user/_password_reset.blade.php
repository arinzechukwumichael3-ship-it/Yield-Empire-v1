@php
    $name = $user->firstname ?? 'there';
    $code = $password_reset->code ?? '';
    $token = $password_reset->token ?? '';
    $verifyUrl = route('user.password.forgot.code.verify.form', $token);
@endphp

<x-email.shell
    :title="'Password Reset - YieldEmpire'"
    :preheader="'Your password reset code is: ' . $code"
>
    <div style="text-align:center;margin-bottom:24px;">
        <div style="display:inline-block;width:56px;height:56px;background:#f59e0b;border-radius:12px;line-height:56px;text-align:center;font-size:24px;color:#ffffff;font-weight:700;">?</div>
    </div>

    <h1 style="text-align:center;margin:0 0 8px;font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.3px;">Password Reset</h1>
    <p style="text-align:center;margin:0 0 24px;color:#64748b;font-size:14px;">Use this code to reset your password</p>

    <div class="otp-container">
        <div class="otp-box">{{ $code }}</div>
    </div>

    <p class="lead" style="margin:0 0 12px;color:#475569;font-size:15px;line-height:1.65;">
        Hi <strong>{{ $name }}</strong>, you requested to reset your YieldEmpire password. Use the code above to continue.
    </p>

    <div class="alert">
        <p><strong>Security tip:</strong> Never share this code with anyone. YieldEmpire will never ask for it via phone or email.</p>
    </div>

    <p class="muted" style="color:#64748b;margin:16px 0 0;font-size:13px;line-height:1.6;">
        Didn't request this? Someone may have entered your email by mistake. You can safely ignore this email — your password will not be changed.
    </p>
</x-email.shell>

@php
    $footer = '
    <p class="brand">YieldEmpire &middot; Secure Financial Technology</p>
    <p>
        <a href="mailto:support@yieldempire.org">support@yieldempire.org</a> &nbsp;&middot;&nbsp;
        <a href="https://yieldempire.org">yieldempire.org</a>
    </p>
    ';
@endphp
