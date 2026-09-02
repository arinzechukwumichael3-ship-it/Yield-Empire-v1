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
    .content-cell { padding: 28px 20px !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background-color:#f4f6fb;">
<span class="preheader" style="display:none !important;max-height:0;overflow:hidden;mso-hide:all;">{{ $preheader ?? '' }}</span>
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:28px 12px;">
      <table class="email-shell" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;">
        <tr>
          <td class="email-card" style="border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);background-color:#ffffff;">
            {{ $header ?? '' }}
            <table class="inner-body" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;background-color:#ffffff;">
              <tr>
                <td class="content-cell" style="padding:40px 44px;">
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
