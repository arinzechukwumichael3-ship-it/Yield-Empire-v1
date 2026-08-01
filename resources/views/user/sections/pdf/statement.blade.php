<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ __('Statement') }} | {{ $basic_settings->site_name }}</title>
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

    /* ── Account meta ── */
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

    /* ── Summary band ── */
    .summary {
        display: flex;
        flex-wrap: wrap;
        margin-top: 16px;
        border-radius: 8px;
        overflow: hidden;
        background: #F4F6FB;
        border: 1px solid #E5E7EB;
    }
    .sum-cell { flex: 1 1 25%; min-width: 150px; padding: 14px 18px; }
    .sum-cell + .sum-cell { border-left: 1px solid #E5E7EB; }
    .sum-label { font-size: 9px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #6B7280; }
    .sum-value { font-size: 15px; font-weight: 800; margin-top: 4px; }
    .sum-value.credit { color: #047857; }
    .sum-value.debit { color: #B91C1C; }
    .sum-value.balance { color: #0B2A5B; }

    /* ── Ledger table ── */
    .ledger-title { font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #0B2A5B; margin: 26px 0 10px; }
    table.ledger { width: 100%; border-collapse: collapse; font-size: 11px; }
    table.ledger thead th {
        background: #0B2A5B;
        color: #FFFFFF;
        text-align: left;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 9px 12px;
    }
    table.ledger thead th.num { text-align: right; }
    table.ledger tbody td { padding: 9px 12px; border-bottom: 1px solid #EEF1F5; vertical-align: top; }
    table.ledger tbody tr:nth-child(even) { background: #FAFBFD; }
    .tx-desc { font-weight: 700; color: #111827; }
    .tx-id { color: #9CA3AF; font-size: 9px; margin-top: 2px; }
    .num { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
    .credit { color: #047857; font-weight: 700; }
    .debit { color: #B91C1C; font-weight: 700; }
    .muted { color: #C7CDD6; }
    .balance { font-weight: 700; color: #111827; }
    .status {
        display: inline-block;
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 2px 7px;
        border-radius: 100px;
    }
    .status.success { background: #D1FAE5; color: #047857; }
    .status.pending { background: #FEF3C7; color: #92400E; }
    .status.hold { background: #DBEAFE; color: #1D4ED8; }
    .status.rejected { background: #FEE2E2; color: #B91C1C; }

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
    .sign-line { width: 100%; min-width: 250px; border-top: 1px solid #374151; }
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
</style>
</head>
<body>
<div class="page">
    @php
        $user = Auth::user();
        $periodFrom = $date['from_date'] ?? null;
        $periodTo = $date['to_date'] ?? null;

        $creditTypes = [
            'ADD-MONEY', 'BONUS', 'COMMISSION', 'CAPITAL-RETURN',
            'TRANSFER-MONEY', 'Salary Disbursement', 'Salary-Disbursement',
        ];
        $transferTypes = [
            \App\Constants\PaymentGatewayConst::TYPE_OTHER_BANK_TRANSFER,
            \App\Constants\PaymentGatewayConst::TYPE_OWN_BANK_TRANSFER,
        ];
        function pdfStmtIsCredit($tx, $creditTypes, $transferTypes) {
            $type = $tx->type ?? '';
            if (in_array($type, $transferTypes)) {
                return ($tx->receiver_id ?? null) == Auth::id();
            }
            return in_array($type, $creditTypes);
        }
        function pdfStmtTypeLabel($type) {
            $map = [
                'ADD-MONEY' => 'Deposit', 'MONEY-OUT' => 'Withdrawal', 'WITHDRAW' => 'Withdrawal',
                'BONUS' => 'Referral Bonus', 'COMMISSION' => 'Commission',
                'OWN-BANK-TRANSFER' => 'Own Account Transfer', 'OTHER-BANK-TRANSFER' => 'Bank Transfer',
                'TRANSFER-MONEY' => 'Transfer', 'MONEY-EXCHANGE' => 'Currency Exchange',
                'ADD-SUBTRACT-BALANCE' => 'Balance Adjustment', 'MAKE-PAYMENT' => 'Payment',
                'CAPITAL-RETURN' => 'Capital Return', 'VIRTUAL-CARD' => 'Virtual Card',
                'MOBILE-WALLET-TRANSFER' => 'Mobile Wallet', 'Salary Disbursement' => 'Salary',
            ];
            return $map[$type] ?? ucwords(str_replace(['-', '_'], ' ', strtolower($type)));
        }
        function pdfStmtStatusClass($status) {
            return match((int) $status) {
                1 => 'success', 3 => 'hold', 4 => 'rejected',
                default => 'pending',
            };
        }

        $txs = $transactions->sortBy('created_at')->values();
        $totalCredit = 0; $totalDebit = 0;
        foreach ($txs as $t) {
            if (pdfStmtIsCredit($t, $creditTypes, $transferTypes)) {
                $totalCredit += (float) $t->request_amount;
            } else {
                $totalDebit += (float) $t->request_amount;
            }
        }
        $closing = $txs->count() ? (float) $txs->last()->available_balance : 0;
        $opening = $closing - ($totalCredit - $totalDebit);
        $default_symbol = get_default_currency_symbol();
        $stmtId = 'ST-' . strtoupper(substr(md5($user->id . now()), 0, 10));
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
            <h1>{{ __('Account Statement') }}</h1>
            <div class="doc-sub">{{ __('Statement Ref') }}: {{ $stmtId }}</div>
        </div>
    </div>

    <!-- Account meta -->
    <div class="meta">
        <div class="meta-cell">
            <div class="meta-label">{{ __('Account Holder') }}</div>
            <div class="meta-value">{{ $user->fullname }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Account Number') }}</div>
            <div class="meta-value">{{ $user->account_no }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Statement Period') }}</div>
            <div class="meta-value">{{ $periodFrom && $periodTo ? $periodFrom . ' → ' . $periodTo : __('All Time') }}</div>
        </div>
        <div class="meta-cell">
            <div class="meta-label">{{ __('Date Generated') }}</div>
            <div class="meta-value">{{ dateFormat('d M Y', now()) }}</div>
        </div>
    </div>

    <!-- Summary band -->
    <div class="summary">
        <div class="sum-cell">
            <div class="sum-label">{{ __('Opening Balance') }}</div>
            <div class="sum-value balance">{{ $default_symbol }}{{ get_amount($opening) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Total Credits') }}</div>
            <div class="sum-value credit">+{{ $default_symbol }}{{ get_amount($totalCredit) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Total Debits') }}</div>
            <div class="sum-value debit">-{{ $default_symbol }}{{ get_amount($totalDebit) }}</div>
        </div>
        <div class="sum-cell">
            <div class="sum-label">{{ __('Closing Balance') }}</div>
            <div class="sum-value balance">{{ $default_symbol }}{{ get_amount($closing) }}</div>
        </div>
    </div>

    <!-- Ledger -->
    <div class="ledger-title">{{ __('Transaction History') }}</div>
    <table class="ledger">
        <thead>
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Transaction') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="num">{{ __('Debit') }}</th>
                <th class="num">{{ __('Credit') }}</th>
                <th class="num">{{ __('Balance') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($txs as $item)
            @php
                $isCredit = pdfStmtIsCredit($item, $creditTypes, $transferTypes);
                $isSend = $item->userTrxType == \App\Constants\PaymentGatewayConst::SEND;
            @endphp
            <tr>
                <td>{{ $item->created_at->format('d M Y') }}<br>{{ $item->created_at->format('h:i A') }}</td>
                <td>
                    <div class="tx-desc">{{ pdfStmtTypeLabel($item->type) }}</div>
                    <div class="tx-id">{{ $item->trx_id }}</div>
                </td>
                <td><span class="status {{ pdfStmtStatusClass($item->status) }}">{{ __($item->string_status->value) }}</span></td>
                <td class="num">
                    @if(!$isCredit)
                        <span class="debit">{{ get_amount($isSend ? $item->total_payable : $item->request_amount, $item->request_currency) }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="num">
                    @if($isCredit)
                        <span class="credit">{{ get_amount($item->request_amount, $item->request_currency) }}</span>
                    @else
                        <span class="muted">—</span>
                    @endif
                </td>
                <td class="num balance">{{ get_amount($item->available_balance, $item->request_currency) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="empty-note">{{ __('No transactions recorded for this period.') }}</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="doc-footer">
        <div class="sign-block">
            <div class="sign-label">{{ __('Authorized Signature') }}</div>
            <div class="sign-line-block">
                <div class="sign-line"></div>
                <div class="sign-name">{{ $user->fullname }}</div>
                <div class="sign-title">{{ __('Account Holder') }}</div>
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
            {{ __('This is a computer-generated statement and does not require a physical signature.') }}<br>
            {{ __('For inquiries, contact') }} <a href="#0">{{ $basic_settings->site_name }}</a>.
        </div>
    </div>
</div>
</body>
</html>
