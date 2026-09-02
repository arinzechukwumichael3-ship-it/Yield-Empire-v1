{{ $user->firstname ?? 'there' }},

Welcome to YieldEmpire! Your account has been verified successfully.

Your International Banking Details:

@foreach($rows as $row)
{{ $row[0] }}: {{ $row[1] }}
@endforeach

You can now send and receive international transfers, manage virtual cards, and track all your transactions from your secure dashboard.

Go to your dashboard: {{ route('user.dashboard') }}

Need help? Contact support at support@yieldempire.org.

---
YieldEmpire · Secure Financial Technology
{{ $unsubscribe_url ? 'Unsubscribe: ' . $unsubscribe_url : '' }}
