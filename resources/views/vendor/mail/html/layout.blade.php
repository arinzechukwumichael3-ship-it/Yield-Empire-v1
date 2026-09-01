<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>{{ $css ?? '' }}</style>
<style>
  @media only screen and (max-width: 600px) {
    .email-shell, .inner-body, .footer, .content-cell { width: 100% !important; }
    .content-cell { padding: 28px 22px !important; }
  }
  @media (prefers-reduced-motion: no-preference) {
    .email-card { animation: yeFadeUp .6s cubic-bezier(.2,.8,.2,1) both; }
    .brand-band { background-size: 220% 220%; animation: yeShimmer 7s ease infinite; }
  }
  @keyframes yeFadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  @keyframes yeShimmer { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
</style>
</head>
<body style="margin:0;padding:0;background-color:#eef2fb;">
<span class="preheader" style="display:none !important;max-height:0;overflow:hidden;mso-hide:all;">{{ $preheader ?? '' }}</span>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#eef2fb;">
  <tr>
    <td align="center" style="padding:32px 12px;">
      <table class="email-shell" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;">
        <tr>
          <td class="email-card" style="border-radius:16px;overflow:hidden;box-shadow:0 14px 38px rgba(11,31,77,0.14);background-color:#ffffff;">
            {{ $header ?? '' }}
            <table class="inner-body" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;background-color:#ffffff;">
              <tr>
                <td class="content-cell" style="padding:36px 40px;">
                  {{ $slot ?? '' }}

                  {{ $subcopy ?? '' }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        {{ $footer ?? '' }}
      </table>
    </td>
  </tr>
</table>
</body>
</html>
