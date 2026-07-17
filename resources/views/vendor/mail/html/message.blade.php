<x-mail::layout>
{{-- Header / brand band --}}
<x-slot:header>
<div class="brand-band" style="background:linear-gradient(135deg,#0b1f4d 0%,#3b5bdb 55%,#15aabf 120%); padding:30px 40px; text-align:center;">
  <table align="center" cellpadding="0" cellspacing="0" role="presentation" style="margin:0 auto;">
    <tr>
      <td style="width:46px;height:46px;border-radius:13px;background:rgba(255,255,255,0.18);color:#ffffff;font-weight:800;font-size:22px;text-align:center;vertical-align:middle;font-family:Arial,Helvetica,sans-serif;">E</td>
      <td style="color:#ffffff;font-weight:800;font-size:24px;letter-spacing:.5px;padding-left:12px;font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif;">EnzoBank</td>
    </tr>
  </table>
</div>
</x-slot:header>

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{{ $subcopy }}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<table align="center" width="600" cellpadding="0" cellspacing="0" role="presentation" style="width:600px; max-width:600px;">
  <tr>
    <td align="center" style="padding:22px 10px 8px;">
      <p style="margin:0 0 8px; font-size:13px; color:#64748b; font-weight:600;">EnzoBank &middot; Secure digital banking</p>
      <p style="margin:0 0 10px; font-size:12px; color:#94a3b8;">
        <a href="https://enzobank.org" style="color:#3b5bdb; text-decoration:none;">Website</a> &nbsp;&middot;&nbsp;
        <a href="https://enzobank.org/privacy" style="color:#3b5bdb; text-decoration:none;">Privacy</a> &nbsp;&middot;&nbsp;
        <a href="https://enzobank.org/terms" style="color:#3b5bdb; text-decoration:none;">Terms</a> &nbsp;&middot;&nbsp;
        <a href="https://enzobank.org/support" style="color:#3b5bdb; text-decoration:none;">Support</a>
      </p>
      <p style="margin:0; font-size:11px; color:#aab2c2; line-height:1.6;">
        &copy; {{ date('Y') }} EnzoBank. All rights reserved.<br>
        You are receiving this email because you hold an EnzoBank account. If you believe this was sent in error, contact support@enzobank.org.
      </p>
    </td>
  </tr>
</table>
</x-slot:footer>
</x-mail::layout>