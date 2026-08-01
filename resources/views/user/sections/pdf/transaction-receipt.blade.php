<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('Transaction Receipt') }} | {{ $basic_settings->site_name }}</title>
<style>
    * { box-sizing: border-box; }
    body {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        color: #1F2937;
        margin: 0;
        padding: 0;
        font-size: 12px;
        line-height: 1.5;
    }
    .page { padding: 42px 46px 36px; }

    /* ── Header ── */
    .doc-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #0B2A5B;
        padding-bottom: 16px;
    }
    .brand { display: flex; align-items: center; gap: 12px; }
    .brand img { height: 42px; }
    .brand-name { font-size: 20px; font-weight: 800; color: #0B2A5B; letter-spacing: 0.3px; }
    .brand-tag { font-size: 10px; color: #6B7280; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; }
    .doc-title { text-align: right; }
    .doc-title h1 { margin: 0; font-size: 22px; font-weight: 800; color: #0B2A5B; letter-spacing: 1px; text-transform: uppercase; }
    .doc-title .doc-sub { font-size: 11px; color: #6B7280; margin-top: 3px; }

    /* ── Transaction meta ── */
    .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        margin-top: 22px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        overflow: hidden;
    }
    .meta-cell {
        flex: 1 1 25%;
        min-width: 160px;
        padding: 14px 18px;
        border-right: 1px solid #E5E7EB;
    }
    .meta-cell:last-child { border-right: none; }
    .meta-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #9CA3AF; }
    .meta-value { font-size: 13px; font-weight: 700; color: #111827; margin-top: 4px; }

    /* ── Amount band ── */
    .amount-band {
        display: flex;
        flex-wrap: wrap;
        margin-top: 16px;
        border-radius: 8px;
        overflow: hidden;
        background: #F4F6FB;
        border: 1px solid #E5E7EB;
    }
    .amt-cell { flex: 1 1 33.33%; min-width: 140px; padding: 14px 18px; text-align: center; }
    .amt-cell + .amt-cell { border-left: 1px solid #E5E7EB; }
    .amt-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6B7280; }
    .amt-value { font-size: 18px; font-weight: 800; margin-top: 4px; }
    .amt-value.credit { color: #047857; }
    .amt-value.debit { color: #B91C1C; }
    .amt-value.neutral { color: #0B2A5B; }

    /* ── Details table ── */
    .details-title { font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #0B2A5B; margin: 26px 0 10px; }
    table.details { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.details thead th {
        background: #0B2A5B;
        color: #FFFFFF;
        text-align: left;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 9px 12px;
    }
    table.details tbody td { padding: 9px 12px; border-bottom: 1px solid #EEF1F5; vertical-align: top; }
    table.details tbody tr:nth-child(even) { background: #FAFBFD; }
    .detail-key { font-weight: 700; color: #111827; white-space: nowrap; width: 35%; }
    .detail-value { color: #374151; word-break: break-word; }
    .detail-value.highlight { color: #1D4ED8; font-family: monospace; }

    /* ── Footer ── */
    .doc-footer {
        margin-top: 50px;
        padding-top: 24px;
        border-top: 2px solid #0B2A5B;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 40px;
    }
    .sign-block {
        flex: 1;
        text-align: left;
    }
    .sign-label { font-size: 9px; color: #6B7280; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 16px; }
    .sign-line-block {
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-width: 300px;
    }
    .sign-line { width: 100%; min-width: 250px; border-top: 1.5px solid #1F2937; }
    .sign-name { font-size: 13px; font-weight: 700; color: #111827; margin-top: 4px; }
    .sign-title { font-size: 10px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; }
    .sign-date { font-size: 10px; color: #9CA3AF; margin-top: 8px; }

    .bank-stamp {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        text-align: right;
    }
    .stamp-circle {
        width: 100px;
        height: 100px;
        border: 2px solid #0B2A5B;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        background: #FAFBFD;
    }
    .stamp-text { font-size: 9px; font-weight: 700; color: #0B2A5B; letter-spacing: 0.5px; text-transform: uppercase; text-align: center; line-height: 1.2; }
    .stamp-date { font-size: 9px; color: #6B7280; margin-top: 4px; }

    .legal { font-size: 9px; color: #9CA3AF; line-height: 1.6; }
    .legal a { color: #1D4ED8; text-decoration: none; }

    .empty-note { text-align: center; color: #9CA3AF; padding: 30px 0; font-style: italic; }

    @media print {
        .doc-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .stamp-circle { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="page">
    @php
        $user = Auth::user();
        $details = $transaction->details;
        $isCredit = in_array($transaction->type, ['ADD-MONEY','BONUS','COMMISSION','CAPITAL-RETURN','TRANSFER-MONEY','Salary Disbursement','Salary-Disbursement'])
            && (!in_array($transaction->type, ['TRANSFER-MONEY','OWN-BANK-TRANSFER','OTHER-BANK-TRANSFER']) || ($transaction->receiver_id ?? null) == Auth::id());
        $txLabel = match($transaction->type) {
            'ADD-MONEY' => 'Deposit', 'MONEY-OUT' => 'Withdrawal', 'WITHDRAW' => 'Withdrawal',
            'BONUS' => 'Referral Bonus', 'COMMISSION' => 'Commission',
            'OWN-BANK-TRANSFER' => 'Own Account Transfer', 'OTHER-BANK-TRANSFER' => 'Bank Transfer',
            'TRANSFER-MONEY' => 'Transfer', 'MONEY-EXCHANGE' => 'Currency Exchange',
            'ADD-SUBTRACT-BALANCE' => 'Balance Adjustment', 'MAKE-PAYMENT' => 'Payment',
            'CAPITAL-RETURN' => 'Capital Return', 'VIRTUAL-CARD' => 'Virtual Card',
            'MOBILE-WALLET-TRANSFER' => 'Mobile Wallet', 'Salary Disbursement' => 'Salary',
            default => ucwords(str_replace(['-', '_'], ' ', strtolower($transaction->type))),
        };
        $statusClass = match((int) $transaction->status) {
            1 => 'success', 3 => 'hold', 4 => 'rejected',
            default => 'pending',
        };
        $statusText = match((int) $transaction->status) {
            1 => 'Completed', 2 => 'Pending', 3 => 'On Hold', 4 => 'Rejected', 5 => 'Waiting',
            default => 'Unknown',
        };
        $stmtId = 'RX-' . strtoupper(substr(md5($transaction->trx_id . now()), 0, 10));
    @endphp

    <!-- Header -->
    <div class="doc-header">
        <div class="brand">
            <div>
                <div class="brand-name">{{ $basic_settings->site_name }}</div>
                <div class="brand-tag">{{ __('International Banking') }}</div>
            </div>
        </div>
        <div class="doc-title">
            <h1>{{ __('Transaction Receipt') }}</h1>
            <div class="doc-sub">{{ __('Receipt Ref') }}: {{ $stmtId }}</div>
        </div>
    </div>

    <!-- Transaction meta -->
    <div class="meta">
        <div class="meta-cell">
            <div class="meta-label">{{ __('Transaction ID') }}</div>
            <div class="meta-value highlight">{{ $transaction->trx_id }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Type') }}</div>
            <div class="meta-value">{{ $txLabel }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Status') }}</div>
            <div class="meta-value">
                <span class="status {{ $statusClass }}">{{ __($statusText) }}</span>
            </div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Date') }}</div>
            <div class="meta-value">{{ $transaction->created_at ? $transaction->created_at->format('d M Y h:i A') : '' }}</div>
        </div>
    </div>

    <!-- Amount band -->
    <div class="amount-band">
        <div class="amt-cell">
            <div class="amt-label">{{ __('Request Amount') }}</div>
            <div class="amt-value {{ $isCredit ? 'credit' : 'debit' }}">
                {{ $isCredit ? '+' : '-' }}{{ get_default_currency_symbol() }}{{ get_amount($transaction->request_amount, $transaction->request_currency) }}
            </div>
        </div>
        <div class="amt-cell">
            <div class="amt-label">{{ __('Fee') }}</div>
            <div class="amt-value debit">{{ get_default_currency_symbol() }}{{ get_amount($transaction->total_charge ?? 0, $transaction->request_currency) }}</div>
        </div>
        <div class="amt-cell">
            <div class="amt-label">{{ __('Total') }}</div>
            <div class="amt-value neutral {{ $isCredit ? 'credit' : 'debit' }}">
                {{ $isCredit ? '+' : '-' }}{{ get_default_currency_symbol() }}{{ get_amount($transaction->total_payable ?? $transaction->request_amount, $transaction->request_currency) }}
            </div>
        </div>
    </div>

    <!-- Details table -->
    <div class="details-title">{{ __('Transaction Details') }}</div>
    <table class="details">
        <tbody>
            <tr>
                <td class="detail-key">{{ __('Account Holder') }}</td>
                <td class="detail-value">{{ $user->fullname }}</td>
            </tr>
            <tr>
                <td class="detail-key">{{ __('Account Number') }}</td>
                <td class="detail-value highlight">{{ $user->account_no }}</td>
            </tr>
            @if($details && $details->description)
            <tr>
                <td class="detail-key">{{ __('Description') }}</td>
                <td class="detail-value">{{ $details->description }}</td>
            </tr>
            @endif
            @if($details && ($details->bank_name ?? $details->receiver_bank ?? $details->sender_bank ?? false))
            <tr>
                <td class="detail-key">{{ __('Bank') }}</td>
                <td class="detail-value">{{ $details->bank_name ?? $details->receiver_bank ?? $details->sender_bank }}</td>
            </tr>
            @endif
            @if($details && ($details->receiver_name ?? $details->sender_name ?? false))
            <tr>
                <td class="detail-key">{{ $isCredit ? __('Sender') : __('Recipient') }}</td>
                <td class="detail-value">{{ $details->receiver_name ?? $details->sender_name }}</td>
            </tr>
            @endif
            @if($details && ($details->receiver_account ?? $details->sender_account ?? false))
            <tr>
                <td class="detail-key">{{ $isCredit ? __('Sender Account') : __('Recipient Account') }}</td>
                <td class="detail-value highlight">{{ $details->receiver_account ?? $details->sender_account }}</td>
            </tr>
            @endif
            @if($details && isset($details->swift_bic))
            <tr>
                <td class="detail-key">{{ __('SWIFT / BIC') }}</td>
                <td class="detail-value highlight">{{ $details->swift_bic }}</td>
            </tr>
            @endif
            <tr>
                <td class="detail-key">{{ __('Balance After') }}</td>
                <td class="detail-value highlight">{{ get_default_currency_symbol() }}{{ get_amount($transaction->available_balance, $transaction->request_currency) }}</td>
            </tr>
            <tr>
                <td class="detail-key">{{ __('Date & Time') }}</td>
                <td class="detail-value">{{ $transaction->created_at ? $transaction->created_at->format('d M Y h:i A') : '' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="doc-footer">
        <div class="sign-block">
            <div class="sign-label">{{ __('Authorized Signature') }}</div>
            <div class="sign-line-block">
                <div class="sign-line"></div>
                <div class="sign-name">{{ $basic_settings->site_name }} Operations</div>
                <div class="sign-title">{{ __('Digital Banking Services') }}</div>
                <div class="sign-date">{{ __('Date') }}: {{ dateFormat('d M Y', now()) }}</div>
            </div>
        </div>

        <div class="bank-stamp">
            <div class="stamp-circle">
                <div class="stamp-text">{{ __('OFFICIAL<br>BANK<br>STAMP') }}</div>
            </div>
            <div class="stamp-date">{{ __('Generated') }}: {{ dateFormat('d M Y H:i', now()) }}</div>
        </div>

        <div class="legal" style="max-width: 320px;">
            {{ __('This is a computer-generated receipt and is valid without a physical signature.') }}<br>
            {{ __('For verification, contact') }} <a href="#0">{{ $basic_settings->site_name }}</a> {{ __('with receipt reference') }} <strong>{{ $stmtId }}</strong>.
        </div>
    </div>
</div>
</body>
</html>