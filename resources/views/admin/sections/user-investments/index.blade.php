@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __($page_title) }}</h5>
        <a href="{{ route('admin.user.investments.earnings') }}" class="btn btn--info btn-sm">{{ __('Earnings Logs') }}</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" name="q" class="form-control" placeholder="{{ __('Search by user or plan') }}" value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn--base w-100">{{ __('Filter') }}</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.user.investments.index') }}" class="btn btn--secondary w-100">{{ __('Reset') }}</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Expected Return') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Maturity Date') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($investments as $inv)
                    <tr>
                        <td>
                            <div>{{ $inv->user->fullname ?? $inv->user->email }}</div>
                            <small class="text-muted">{{ $inv->user->email }}</small>
                        </td>
                        <td>{{ $inv->plan->name }}</td>
                        <td>{{ number_format($inv->amount, 2) }}</td>
                        <td>{{ number_format($inv->expected_return, 2) }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'badge--warning',
                                    'active' => 'badge--info',
                                    'completed' => 'badge--success',
                                    'cancelled' => 'badge--danger',
                                ];
                                $color = $statusColors[$inv->status] ?? 'badge--secondary';
                            @endphp
                            <span class="badge {{ $color }}">{{ ucfirst($inv->status) }}</span>
                        </td>
                        <td>{{ $inv->maturity_date ? $inv->maturity_date->format('Y-m-d') : '-' }}</td>
                        <td>{{ $inv->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.user.investments.show', $inv->id) }}" class="btn btn-sm btn--info">{{ __('View') }}</a>
                            @if($inv->status === 'pending')
                            <form action="{{ route('admin.user.investments.approve', $inv->id) }}" method="POST" class="d-inline ms-1">
                                @csrf
                                <button type="submit" class="btn btn-sm btn--success" onclick="return confirm('{{ __('Approve this investment?') }}')">{{ __('Approve') }}</button>
                            </form>
                            <button type="button" class="btn btn-sm btn--danger ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{$inv->id}}">{{ __('Reject') }}</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted">{{ __('No investments found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $investments->links() }}
    </div>
</div>

@foreach($investments as $inv)
    @if($inv->status === 'pending')
    <div class="modal fade" id="rejectModal{{$inv->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Reject Investment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.user.investments.reject', $inv->id) }}" method="POST">
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
@endforeach
@endsection
