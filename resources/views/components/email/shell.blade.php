<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{{ $title ?? 'YieldEmpire' }}</title>
@include('components.email.styles')
</head>
<body style="margin:0;padding:0;background-color:#f4f6fb;">
@if(!empty($preheader))
<span class="preheader">{{ $preheader }}</span>
@endif
<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:28px 12px;">
      <table class="email-shell" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;">
        <tr>
          <td class="email-card" style="border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);background-color:#ffffff;">
            <table class="inner-body" align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px;max-width:600px;background-color:#ffffff;">
              <tr>
                <td class="content-cell">
                  {{ $slot }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @isset($footer)
        <tr>
          <td class="email-footer">
            {{ $footer }}
          </td>
        </tr>
        @endisset
      </table>
    </td>
  </tr>
</table>
</body>
</html>
