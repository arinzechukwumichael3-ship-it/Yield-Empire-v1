<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{{ $title ?? 'YieldEmpire' }}</title>
@include('components.email.styles')
</head>
<body style="margin:0;padding:0;background-color:#eef2fb;">
@if(!empty($preheader))
<span class="preheader">{{ $preheader }}</span>
@endif
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#eef2fb;">
  <tr>
    <td align="center" style="padding:32px 12px;">
      <table class="email-shell" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;">
        <tr>
          <td class="email-card" style="border-radius:16px;overflow:hidden;box-shadow:0 14px 38px rgba(11,31,77,0.14);background-color:#ffffff;">
            {{-- Brand band --}}
            <div class="brand-band" style="background:linear-gradient(135deg,#0b1f4d 0%,#3b5bdb 55%,#15aabf 120%);padding:28px 40px;text-align:center;">
              <div class="brand-logo" style="color:#ffffff;font-weight:800;font-size:24px;letter-spacing:.5px;font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif;line-height:1.1;">Yield<span class="accent" style="color:#15aabf;">Empire</span></div>
              <div class="brand-tag" style="margin-top:6px;font-size:12px;color:rgba(255,255,255,0.70);font-family:Arial,Helvetica,sans-serif;letter-spacing:1.5px;text-transform:uppercase;">Secure Financial Technology &amp; Investments</div>
            </div>
            {{-- Body --}}
            <table class="inner-body" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;background-color:#ffffff;">
              <tr>
                <td class="content-cell" style="padding:36px 40px;">
                  {{ $slot }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        {{-- Footer --}}
        <tr>
          <td align="center" style="padding:22px 10px 8px;">
            <p class="footer-brand" style="margin:0 0 8px;font-size:13px;color:#64748b;font-weight:600;">YieldEmpire &middot; Secure Financial Technology &amp; Investments</p>
            <p style="margin:0 0 10px;font-size:12px;color:#94a3b8;line-height:1.6;">
              <strong style="color:#64748b;">Need help?</strong><br>
              Email: <a href="mailto:support@yieldempire.org" style="color:#3b5bdb;text-decoration:underline;">support@yieldempire.org</a> &nbsp;·&nbsp; WhatsApp: <a href="https://wa.me/447464483316" style="color:#3b5bdb;text-decoration:underline;">+44 7464 483316</a>
            </p>
            <p style="margin:0;font-size:11px;color:#aab2c2;line-height:1.6;">
              &copy; {{ date('Y') }} YieldEmpire. All rights reserved.<br>
              @if(!empty($unsubscribe_url))
              You are receiving this email because you hold a YieldEmpire account. <a href="{{ $unsubscribe_url }}" style="color:#3b5bdb;text-decoration:underline;">Unsubscribe</a>.
              @else
              You are receiving this email because you hold a YieldEmpire account.
              @endif
            </p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
