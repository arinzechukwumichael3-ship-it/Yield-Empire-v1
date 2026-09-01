Congratulations {{ $user->fullname ?? 'there' }},

Welcome to YieldEmpire. Your account has been created and verified successfully.

Your international banking details:
@foreach($rows as $row)
{{ $row[0] }}: {{ $row[1] }}
@endforeach

You can now send and receive international transfers, manage virtual cards, and track all your transactions from your secure dashboard.

Need assistance? Email: support@yieldempire.org

@if(!empty($unsubscribe_url))
Prefer not to receive transactional emails? Unsubscribe: {{ $unsubscribe_url }}
@endif
