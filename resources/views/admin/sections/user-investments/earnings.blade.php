@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __($page_title) }}</h5>
        <a href="{{ route('admin.user.investments.index') }}" class="btn btn--secondary btn-sm">{{ __('Back to Investments') }}</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" placeholder="{{ __('Search by user') }}" value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn--base w-100">{{ __('Filter') }}</button>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.user.investments.earnings') }}" class="btn btn--secondary w-100">{{ __('Reset') }}</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Plan') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Credited At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings as $earning)
                    <tr>
                        <td>
                            <div>{{ $earning->user->fullname ?? $earning->user->email }}</div>
                            <small class="text-muted">{{ $earning->user->email }}</small>
                        </td>
                        <td>{{ $earning->investment->plan->name ?? '-' }}</td>
                        <td>{{ number_format($earning->amount, 2) }}</td>
                        <td>{{ $earning->credited_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted">{{ __('No earnings logs found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $earnings->links() }}
    </div>
</div>
@endsection
