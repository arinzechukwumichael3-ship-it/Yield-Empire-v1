@extends('user.layouts.rise-master')

@push('css')
<style>
/* ── Subtitle under page title ── */
.stm-subtitle {
    font-size: 13px;
    color: var(--text-muted);
    margin: 4px 0 0;
}

/* ── Header actions ── */
.stm-header-actions { display: flex; gap: 10px; align-items: center; }
.stm-download {
    width: auto;
    padding: 11px 22px;
    border-radius: 100px;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

/* ── Account summary meta grid ── */
.stm-meta-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}
.stm-meta { min-width: 0; }
.stm-meta-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
    margin-bottom: 4px;
}
.stm-meta-value {
    font-size: 15px;
    font-weight: 700;
    color: var(--text-primary);
    word-break: break-word;
}
.stm-meta-value.accent { color: var(--accent); }

/* ── Filter form ── */
.stm-filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    align-items: end;
}
.stm-filter-actions {
    grid-column: 1 / -1;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 4px;
}
.stm-btn-ghost {
    width: auto;
    padding: 14px 26px;
    border-radius: 12px;
    background: transparent;
    border: 1px solid var(--border-strong);
    color: var(--text-secondary);
    font-size: 15px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.15s;
}
.stm-btn-ghost:hover { border-color: var(--accent); color: var(--accent); }

/* ── Results toolbar ── */
.stm-result-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}
.stm-result-count {
    font-size: 13px;
    color: var(--text-secondary);
}
.stm-result-count strong { color: var(--text-primary); }

/* ── Ledger table ── */
.stm-ledger-wrap { overflow-x: auto; margin: 0 -20px; }
.stm-ledger {
    width: 100%;
    border-collapse: collapse;
    min-width: 720px;
}
.stm-ledger th {
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
    padding: 10px 20px;
    border-bottom: 1px solid var(--border-color);
    white-space: nowrap;
}
.stm-ledger td {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border-color);
    font-size: 13px;
    color: var(--text-secondary);
    vertical-align: middle;
}
.stm-ledger tbody tr { transition: background 0.15s; }
.stm-ledger tbody tr:hover { background: var(--accent-soft); }
.stm-ledger tbody tr:last-child td { border-bottom: none; }
.stm-date { color: var(--text-muted); white-space: nowrap; font-size: 12px; }
.stm-desc { color: var(--text-primary); font-weight: 600; }
.stm-trx { color: var(--text-muted); font-size: 11px; margin-top: 2px; }
.stm-amount {
    text-align: right;
    font-variant-numeric: tabular-nums;
    font-weight: 700;
    white-space: nowrap;
}
.stm-amount.debit { color: var(--danger-text); }
.stm-amount.credit { color: var(--success-text); }
.stm-amount.muted { color: var(--text-muted); font-weight: 500; }
.stm-balance {
    text-align: right;
    font-weight: 600;
    color: var(--text-primary);
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}

/* ── Status pills ── */
.stm-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}
.stm-status::before {
    content: "";
    width: 6px; height: 6px; border-radius: 50%;
    background: currentColor;
}
.stm-status.success { background: var(--success-bg); color: var(--success-text); }
.stm-status.pending { background: var(--warning-bg); color: var(--warning-text); }
.stm-status.hold    { background: var(--info-bg); color: var(--info); }
.stm-status.rejected { background: var(--danger-bg); color: var(--danger-text); }

/* ── Summary footer ── */
.stm-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px solid var(--border-color);
}
.stm-sum-item {
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 14px 16px;
}
.stm-sum-label {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 6px;
}
.stm-sum-value { font-size: 17px; font-weight: 800; color: var(--text-primary); }
.stm-sum-value.credit { color: var(--success-text); }
.stm-sum-value.debit { color: var(--danger-text); }

/* ── Info / empty state ── */
.stm-info {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px;
    border-radius: 14px;
    background: var(--accent-soft);
    border: 1px solid var(--border-color);
}
.stm-info-icon {
    width: 40px; height: 40px; flex-shrink: 0;
    border-radius: 12px;
    background: var(--accent);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
}
.stm-info-title { font-size: 15px; font-weight: 700; color: var(--text-primary); }
.stm-info-text { font-size: 13px; color: var(--text-secondary); margin-top: 2px; line-height: 1.6; }

.stm-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 50px 20px;
    text-align: center;
    gap: 8px;
}
.stm-empty-icon { color: var(--border-strong); margin-bottom: 6px; }
.stm-empty-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.stm-empty-sub { font-size: 13px; color: var(--text-muted); }

@media (max-width: 768px) {
    .stm-meta-grid { grid-template-columns: repeat(2, 1fr); }
    .stm-filter-grid { grid-template-columns: repeat(2, 1fr); }
    .stm-summary { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 460px) {
    .stm-meta-grid, .stm-filter-grid, .stm-summary { grid-template-columns: 1fr; }
    .stm-download { padding: 9px 16px; font-size: 13px; }
}
</style>
@endpush

@section('content')

@php
    $transactions = $transactions ?? collect([]);
    $hasResults = $transactions->count() > 0;

    $creditTypes = [
        'ADD-MONEY', 'BONUS', 'COMMISSION', 'CAPITAL-RETURN',
        'TRANSFER-MONEY', 'Salary Disbursement', 'Salary-Disbursement',
    ];
    $transferTypes = [
        payment_gateway_const()::TYPE_OTHER_BANK_TRANSFER,
        payment_gateway_const()::TYPE_OWN_BANK_TRANSFER,
    ];

    if (!function_exists('stmIsCredit')) {
    function stmIsCredit($tx, $creditTypes, $transferTypes) {
        $type = $tx->type ?? '';
        if (in_array($type, $transferTypes)) {
            return ($tx->receiver_id ?? null) == Auth::id();
        }
        return in_array($type, $creditTypes);
    }
    }
    if (!function_exists('stmTypeLabel')) {
    function stmTypeLabel($type) {
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
    }
    if (!function_exists('stmStatusClass')) {
    function stmStatusClass($status) {
        return match((int) $status) {
            1 => 'success', 3 => 'hold', 4 => 'rejected',
            default => 'pending',
        };
    }

    }
    $totalCredit = 0;
    $totalDebit = 0;
    foreach ($transactions as $tx) {
        if (stmIsCredit($tx, $creditTypes, $transferTypes)) {
            $totalCredit += (float) $tx->request_amount;
        } else {
            $totalDebit += (float) $tx->request_amount;
        }
    }
    $netMovement = $totalCredit - $totalDebit;
    $currentBalance = $hasResults ? (float) $transactions->first()->available_balance : 0;

    $exportParams = request()->only(['trx_id','from_date','to_date','type','status']);
    $exportUrl = route('user.statements.export') . (count($exportParams) ? '?' . http_build_query($exportParams) : '');
    $default_symbol = get_default_currency_symbol();

    $periodFrom = request('from_date');
    $periodTo = request('to_date');
    $hasFilters = request()->hasAny(['trx_id','from_date','to_date','type','status']);
@endphp

<div class="am-header">
    <div>
        <h1 class="am-header-title">{{ __('Bank Statement') }}</h1>
        <p class="stm-subtitle">{{ __('View and download your account activity') }}</p>
    </div>
    <div class="stm-header-actions">
        <a href="{{ $exportUrl }}" class="am-btn stm-download">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            {{ __('Download PDF') }}
        </a>
    </div>
</div>

<div class="am-body">

    <!-- Account summary -->
    <div class="am-card">
        <div class="am-card-title">{{ __('Account Overview') }}</div>
        <div class="stm-meta-grid">
            <div class="stm-meta">
                <div class="stm-meta-label">{{ __('Account Holder') }}</div>
                <div class="stm-meta-value">{{ Auth::user()->fullname }}</div>
            </div>
            <div class="stm-meta">
                <div class="stm-meta-label">{{ __('Account Number') }}</div>
                <div class="stm-meta-value accent">{{ Auth::user()->account_no }}</div>
            </div>
            <div class="stm-meta">
                <div class="stm-meta-label">{{ __('Statement Period') }}</div>
                <div class="stm-meta-value">{{ $periodFrom && $periodTo ? $periodFrom.' → '.$periodTo : __('All time') }}</div>
            </div>
            <div class="stm-meta">
                <div class="stm-meta-label">{{ __('Generated') }}</div>
                <div class="stm-meta-value">{{ now()->format('d M Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="am-card">
        <div class="am-card-title">{{ __('Filter Statement') }}</div>
        <form method="GET" action="{{ setRoute('user.statements.filter') }}">
            <div class="stm-filter-grid">
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('From Date') }}</label>
                    <div class="am-input-wrap">
                        <input type="date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                </div>
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('To Date') }}</label>
                    <div class="am-input-wrap">
                        <input type="date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                </div>
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('Transaction ID') }}</label>
                    <div class="am-input-wrap">
                        <input type="text" name="trx_id" placeholder="{{ __('Trx ID') }}" value="{{ request('trx_id') }}">
                    </div>
                </div>
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('Type') }}</label>
                    <div class="am-input-wrap">
                        <select name="type">
                            <option {{ request('type') == '*' || !request('type') ? 'selected' : '' }} value="*">{{ __('All') }}</option>
                            <option {{ request('type') == payment_gateway_const()::TYPEADDMONEY ? 'selected' : '' }} value="{{ payment_gateway_const()::TYPEADDMONEY }}">{{ payment_gateway_const()::TYPEADDMONEY }}</option>
                            <option {{ request('type') == payment_gateway_const()::TYPEMONEYOUT ? 'selected' : '' }} value="{{ payment_gateway_const()::TYPEMONEYOUT }}">{{ payment_gateway_const()::TYPEMONEYOUT }}</option>
                            <option {{ request('type') == payment_gateway_const()::TYPEADDSUBTRACTBALANCE ? 'selected' : '' }} value="{{ payment_gateway_const()::TYPEADDSUBTRACTBALANCE }}">{{ payment_gateway_const()::TYPEADDSUBTRACTBALANCE }}</option>
                            <option {{ request('type') == 'FUND-TRANSFER' ? 'selected' : '' }} value="FUND-TRANSFER">FUND-TRANSFER</option>
                            <option {{ request('type') == payment_gateway_const()::TYPE_OWN_BANK_TRANSFER ? 'selected' : '' }} value="{{ payment_gateway_const()::TYPE_OWN_BANK_TRANSFER }}">{{ payment_gateway_const()::TYPE_OWN_BANK_TRANSFER }}</option>
                            <option {{ request('type') == payment_gateway_const()::TYPE_OTHER_BANK_TRANSFER ? 'selected' : '' }} value="{{ payment_gateway_const()::TYPE_OTHER_BANK_TRANSFER }}">{{ payment_gateway_const()::TYPE_OTHER_BANK_TRANSFER }}</option>
                        </select>
                    </div>
                </div>
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('Status') }}</label>
                    <div class="am-input-wrap">
                        <select name="status">
                            <option {{ request('status') == '*' || !request('status') ? 'selected' : '' }} value="*">{{ __('All') }}</option>
                            <option {{ request('status') == '1' ? 'selected' : '' }} value="1">{{ __('Success') }}</option>
                            <option {{ request('status') == '2' ? 'selected' : '' }} value="2">{{ __('Pending') }}</option>
                            <option {{ request('status') == '3' ? 'selected' : '' }} value="3">{{ __('Hold') }}</option>
                            <option {{ request('status') == '4' ? 'selected' : '' }} value="4">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                </div>
                <div class="am-field-group" style="margin-bottom:0;">
                    <label class="am-label">{{ __('Reference') }}</label>
                    <div class="am-input-wrap">
                        <input type="text" disabled value="{{ Auth::user()->account_no }}" style="opacity:.7">
                    </div>
                </div>
                <div class="stm-filter-actions">
                    <button type="submit" class="am-btn" style="width:auto;padding:14px 30px;border-radius:100px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;vertical-align:-2px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('user.statements.index') }}" class="stm-btn-ghost">
                        {{ __('Reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    @if($hasResults)
    <!-- Results -->
    <div class="am-card">
        <div class="stm-result-head">
            <div class="stm-result-count">{{ __('Showing') }} <strong>{{ $transactions->count() }}</strong> {{ __('transaction(s)') }}</div>
            <a href="{{ $exportUrl }}" class="stm-btn-ghost" style="padding:10px 18px;font-size:13px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                {{ __('Export PDF') }}
            </a>
        </div>

        <div class="stm-ledger-wrap">
            <table class="stm-ledger">
                <thead>
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Transaction') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th style="text-align:right">{{ __('Debit') }}</th>
                        <th style="text-align:right">{{ __('Credit') }}</th>
                        <th style="text-align:right">{{ __('Balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $item)
                    @php
                        $isCredit = stmIsCredit($item, $creditTypes, $transferTypes);
                        $isSend = $item->userTrxType == payment_gateway_const()::SEND;
                    @endphp
                    <tr>
                        <td class="stm-date">{{ $item->created_at->format('d M Y') }}<br>{{ $item->created_at->format('h:i A') }}</td>
                        <td>
                            <div class="stm-desc">{{ stmTypeLabel($item->type) }}</div>
                            <div class="stm-trx">{{ $item->trx_id }}</div>
                        </td>
                        <td>
                            <span class="stm-status {{ stmStatusClass($item->status) }}">{{ __($item->string_status->value) }}</span>
                        </td>
                        <td class="stm-amount debit">
                            @if(!$isCredit)
                                {{ get_amount($isSend ? $item->total_payable : $item->request_amount, $item->request_currency) }}
                            @else
                                <span class="stm-amount muted">—</span>
                            @endif
                        </td>
                        <td class="stm-amount credit">
                            @if($isCredit)
                                {{ get_amount($item->request_amount, $item->request_currency) }}
                            @else
                                <span class="stm-amount muted">—</span>
                            @endif
                        </td>
                        <td class="stm-balance">{{ get_amount($item->available_balance, $item->request_currency) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="stm-summary">
            <div class="stm-sum-item">
                <div class="stm-sum-label">{{ __('Total Credits') }}</div>
                <div class="stm-sum-value credit">+{{ get_amount($totalCredit) }}</div>
            </div>
            <div class="stm-sum-item">
                <div class="stm-sum-label">{{ __('Total Debits') }}</div>
                <div class="stm-sum-value debit">-{{ get_amount($totalDebit) }}</div>
            </div>
            <div class="stm-sum-item">
                <div class="stm-sum-label">{{ __('Net Movement') }}</div>
                <div class="stm-sum-value {{ $netMovement >= 0 ? 'credit' : 'debit' }}">
                    {{ $netMovement >= 0 ? '+' : '-' }}{{ get_amount(abs($netMovement)) }}
                </div>
            </div>
            <div class="stm-sum-item">
                <div class="stm-sum-label">{{ __('Current Balance') }}</div>
                <div class="stm-sum-value">{{ get_amount($currentBalance) }}</div>
            </div>
        </div>
    </div>
    @elseif($hasFilters)
    <div class="am-card">
        <div class="stm-empty">
            <div class="stm-empty-icon">
                <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
            </div>
            <span class="stm-empty-title">{{ __('No transactions found') }}</span>
            <span class="stm-empty-sub">{{ __('Try adjusting your filters or date range.') }}</span>
        </div>
    </div>
    @else
    <div class="am-card">
        <div class="stm-info">
            <div class="stm-info-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <div class="stm-info-title">{{ __('Generate your statement') }}</div>
                <div class="stm-info-text">{{ __('Choose a date range or apply filters above, then download a formatted PDF of your account activity. Leaving all filters empty exports your full history.') }}</div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
