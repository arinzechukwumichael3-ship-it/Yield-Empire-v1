@php
    $name = $user->firstname ?? 'there';
    $email = $user->email ?? '';
    $unsubscribe_url = $unsubscribeUrl ?? '';
    $rows = $rows ?? [];
@endphp

<x-email.shell
    :title="'Welcome to YieldEmpire - Your Account is Ready!'"
    :preheader="'Your account has been verified successfully'"
>
    <div style="text-align:center;margin-bottom:24px;">
        <div style="display:inline-block;width:56px;height:56px;background:#10b981;border-radius:12px;line-height:56px;text-align:center;font-size:24px;color:#ffffff;font-weight:700;">✓</div>
    </div>

    <h1 style="text-align:center;margin:0 0 8px;font-size:22px;font-weight:800;color:#0f172a;letter-spacing:-0.3px;">Welcome to YieldEmpire</h1>
    <p style="text-align:center;margin:0 0 24px;color:#64748b;font-size:14px;">Your account is verified and ready to use</p>

    <div class="success">
        <p><strong>Account verified!</strong> You can now send and receive international transfers, manage virtual cards, and track all your transactions from your secure dashboard.</p>
    </div>

    <h2 style="margin:28px 0 16px;font-size:17px;font-weight:700;color:#0f172a;">Your International Banking Details</h2>
    <p class="lead" style="margin:0 0 16px;color:#475569;font-size:15px;line-height:1.65;">
        Share these details with friends, family or business partners anywhere in the world to receive instant transfers straight into your YieldEmpire account.
    </p>

    @if(count($rows) > 0)
    <table class="info-table" role="presentation" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th colspan="2">Account Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td class="label">{{ $row[0] }}</td>
                <td class="value">{{ $row[1] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div style="text-align:center;margin:28px 0 8px;">
        <a href="{{ route('user.dashboard') }}" class="btn">Go to Dashboard</a>
    </div>

    <p class="muted" style="color:#64748b;margin:20px 0 0;font-size:13px;line-height:1.6;">
        Need assistance? Email <a href="mailto:support@yieldempire.org">support@yieldempire.org</a> or WhatsApp <a href="https://wa.me/447464483316">+44 7464 483316</a>.
    </p>
</x-email.shell>

@php
    $footer = '
    <p class="brand">YieldEmpire &middot; Secure Financial Technology</p>
    <p>
        <a href="mailto:support@yieldempire.org">support@yieldempire.org</a> &nbsp;&middot;&nbsp;
        <a href="https://yieldempire.org">yieldempire.org</a>
    </p>
    ' . ($unsubscribe_url ? '<p style="margin:0;font-size:11px;color:#aab2c2;line-height:1.6;">You received this because you verified a YieldEmpire account. <a href="' . $unsubscribe_url . '">Unsubscribe</a></p>' : '') . '
    ';
@endphp
