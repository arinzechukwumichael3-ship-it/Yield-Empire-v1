<x-email.shell
  :title="$subject ?? 'Message from YieldEmpire'"
  :preheader="$subject ?? 'You have a new message from YieldEmpire.'"
>
  <p class="greeting" style="margin:0 0 16px;color:#0b1f4d;font-size:18px;font-weight:700;">Hi {{ $name }},</p>

  <div class="lead" style="margin:0 0 18px;color:#475569;font-size:15px;line-height:1.65;">
    {!! $mail_body ?? '' !!}
  </div>

  <p class="muted" style="color:#64748b;margin:24px 0 0;font-size:13px;line-height:1.6;">
    This message was sent from the YieldEmpire team. If you have any questions, reply to this email or contact support@yieldempire.org.
  </p>
</x-email.shell>
