<x-email.shell
  :title="'Welcome to YieldEmpire'"
  :preheader="'Your account is verified. Here are your international banking details.'"
>
  <p class="greeting" style="margin:0 0 16px;color:#0b1f4d;font-size:18px;font-weight:700;">Congratulations {{ $user->fullname ?? 'there' }}!</p>

  <p class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    Welcome to YieldEmpire. Your account has been created and verified successfully, and we are excited to have you on board.
  </p>

  <p class="lead" style="margin:0 0 8px;color:#475569;font-size:15px;line-height:1.65;">
    These are your international banking details. Share them with friends, family or business partners anywhere in the world to receive instant transfers straight into your YieldEmpire account.
  </p>

  <table role="presentation" cellpadding="0" cellspacing="0" class="summary" style="width:100%;max-width:540px;margin:22px 0;border-collapse:collapse;background:#f8faff;border:1px solid #dbe3ff;border-radius:14px;overflow:hidden;font-family:Arial,Helvetica,sans-serif;">
    <tr>
      <td style="padding:18px 22px;background:#0b1f4d;color:#ffffff;font-size:16px;font-weight:700;">Your YieldEmpire International Banking Details</td>
    </tr>
    @foreach($rows as $index => $row)
    <tr style="background:{{ $index % 2 === 0 ? '#ffffff' : '#f1f5ff' }};">
      <td style="padding:13px 18px;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#64748b;font-weight:700;width:42%;border-bottom:1px solid #e8edf7;">{{ $row[0] }}</td>
      <td style="padding:13px 18px;font-size:15px;color:#0b1f4d;font-weight:600;font-family:Consolas,Menlo,monospace;border-bottom:1px solid #e8edf7;">{{ $row[1] }}</td>
    </tr>
    @endforeach
  </table>

  <p class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    You can now send and receive international transfers, manage virtual cards, and track all your transactions from your secure dashboard.
  </p>

  <p class="muted" style="color:#64748b;margin:18px 0 0;font-size:13px;line-height:1.6;">
    Need assistance? Email <a href="mailto:support@yieldempire.org" style="color:#3b5bdb;text-decoration:underline;">support@yieldempire.org</a>.
  </p>

  @if(!empty($unsubscribe_url))
  <p class="muted" style="color:#94a3b8;margin:14px 0 0;font-size:12px;line-height:1.6;">
    Prefer not to receive transactional emails? <a href="{{ $unsubscribe_url }}" style="color:#3b5bdb;text-decoration:underline;">Unsubscribe</a>.
  </p>
  @endif
</x-email.shell>
