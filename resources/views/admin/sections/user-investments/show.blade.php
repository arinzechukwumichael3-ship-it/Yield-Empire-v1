@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __($page_title) }}</h5>
        <div>
            @if($investment->status === 'pending')
            <form action="{{ route('admin.user.investments.approve', $investment->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn--success me-1" onclick="return confirm('{{ __('Approve this investment?') }}')">{{ __('Approve') }}</button>
            </form>
            <button type="button" class="btn btn-sm btn--danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{$investment->id}}">{{ __('Reject') }}</button>
            @endif
            <a href="{{ route('admin.user.investments.index') }}" class="btn btn--secondary btn-sm ms-2">{{ __('Back') }}</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>{{ __('Investment Info') }}</h6>
                <table class="table table-borderless">
                    <tr><th>{{ __('ID') }}</th><td>{{ $investment->id }}</td></tr>
                    <tr><th>{{ __('User') }}</th><td>{{ $investment->user->fullname ?? $investment->user->email }} ({{ $investment->user->email }})</td></tr>
                    <tr><th>{{ __('Plan') }}</th><td>{{ $investment->plan->name }}</td></tr>
                    <tr><th>{{ __('Amount') }}</th><td>{{ number_format($investment->amount, 2) }}</td></tr>
                    <tr><th>{{ __('Expected Return') }}</th><td>{{ number_format($investment->expected_return, 2) }}</td></tr>
                    <tr><th>{{ __('Status') }}</th><td>
                        @php
                            $statusColors = [
                                'pending' => 'badge--warning',
                                'active' => 'badge--info',
                                'completed' => 'badge--success',
                                'cancelled' => 'badge--danger',
                            ];
                            $color = $statusColors[$investment->status] ?? 'badge--secondary';
                        @endphp
                        <span class="badge {{ $color }}">{{ ucfirst($investment->status) }}</span>
                    </td></tr>
                    <tr><th>{{ __('Payment Method') }}</th><td>{{ $investment->payment_method ?? '-' }}</td></tr>
                    <tr><th>{{ __('Wallet Address') }}</th><td>{{ $investment->wallet_address_used ?? '-' }}</td></tr>
                    <tr><th>{{ __('TX Hash') }}</th><td>{{ $investment->tx_hash ?? '-' }}</td></tr>
                    <tr><th>{{ __('Maturity Date') }}</th><td>{{ $investment->maturity_date ? $investment->maturity_date->format('Y-m-d H:i') : '-' }}</td></tr>
                    <tr><th>{{ __('Created At') }}</th><td>{{ $investment->created_at->format('Y-m-d H:i') }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                @if($investment->proof_url)
                <h6>{{ __('Proof Document') }}</h6>
                <div>
                    <a href="{{ $investment->proof_url }}" target="_blank" class="btn btn--base btn-sm">{{ __('View Proof') }}</a>
                </div>
                @endif
            </div>
        </div>

        <hr>

        <h6>{{ __('Earnings Logs') }}</h6>
        @if($investment->earnings->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Credited At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($investment->earnings as $earning)
                    <tr>
                        <td>{{ number_format($earning->amount, 2) }}</td>
                        <td>{{ $earning->credited_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-muted">{{ __('No earnings recorded yet') }}</p>
        @endif
    </div>
</div>

@if($investment->status === 'pending')
<div class="modal fade" id="rejectModal{{$investment->id}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Reject Investment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.user.investments.reject', $investment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Reason') }}</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                        @error('reason')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn--danger">{{ __('Reject') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
