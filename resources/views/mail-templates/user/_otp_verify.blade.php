@php
    $email = $user->email ?? ($notifiable->email ?? '');
    $name = $user->firstname ?? ($notifiable->firstname ?? 'there');
    $code = $data->code ?? '';
    $token = $data->token ?? '';
    $unsubscribe_url = $unsubscribeUrl ?? '';
@endphp

<x-email.shell
    :title="'Verify Your YieldEmpire Account'"
    :preheader="'Your verification code is: ' . $code"
>
    <div style="text-align:center;margin-bottom:24px;">
        <div style="display:inline-block;width:56px;height:56px;background:#0f172a;border-radius:12px;line-height:56px;text-align:center;font-size:24px;color:#ffffff;font-weight:700;">Y</div>
    </div>

    <h1 style="text-align:center;margin:0 0 8px;font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.3px;">Verify Your Email</h1>
    <p style="text-align:center;margin:0 0 24px;color:#64748b;font-size:14px;">Enter this code to confirm your account</p>

    <div class="otp-container">
        <div class="otp-box">{{ $code }}</div>
    </div>

    <p class="lead" style="margin:0 0 12px;color:#475569;font-size:15px;line-height:1.65;">
        Hi <strong>{{ $name }}</strong>, use the code above to verify your YieldEmpire account. This code expires in <strong>15 minutes</strong>.
    </p>

    <div class="alert">
        <p><strong>Security tip:</strong> Never share this code with anyone. YieldEmpire will never ask for it via phone or email.</p>
    </div>

    <p class="muted" style="color:#64748b;margin:16px 0 0;font-size:13px;line-height:1.6;">
        Didn't request this? Someone may have entered your email by mistake. You can safely ignore this email — no account will be created.
    </p>
</x-email.shell>

@php
    $footer = '
    <p class="brand">YieldEmpire &middot; Secure Financial Technology</p>
    <p>
        <a href="mailto:support@yieldempire.org">support@yieldempire.org</a> &nbsp;&middot;&nbsp;
        <a href="https://yieldempire.org">yieldempire.org</a>
    </p>
    ' . ($unsubscribe_url ? '<p style="margin:0;font-size:11px;color:#aab2c2;line-height:1.6;">You received this because you registered at YieldEmpire. <a href="' . $unsubscribe_url . '">Unsubscribe</a></p>' : '') . '
    ';
@endphp
