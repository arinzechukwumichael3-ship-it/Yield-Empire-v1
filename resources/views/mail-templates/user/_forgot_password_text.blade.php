Hi {{ $username }},

We received a request to reset the password for your YieldEmpire account.

Your password reset code is: {{ $code }}

This code expires in 15 minutes. If you did not request a password reset, please ignore this email or contact our support team — your password will remain unchanged.

---
Need help? Email: support@yieldempire.org · WhatsApp: +44 7464 483316
© {{ date('Y') }} YieldEmpire. All rights reserved.
@if(!empty($unsubscribe_url))
Prefer not to receive these emails? Unsubscribe: {{ $unsubscribe_url }}
@endif
