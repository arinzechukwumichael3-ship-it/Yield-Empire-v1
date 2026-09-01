@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __($page_title) }}</h5>
        <a href="{{ route('admin.investment.plans.create') }}" class="btn btn--base">{{ __('Create') }}</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Min Amount') }}</th>
                        <th>{{ __('Max Amount') }}</th>
                        <th>{{ __('ROI %') }}</th>
                        <th>{{ __('Duration (Days)') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-end"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($plans as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ number_format($p->min_amount, 2) }}</td>
                        <td>{{ $p->max_amount ? number_format($p->max_amount, 2) : __('Unlimited') }}</td>
                        <td>{{ number_format($p->roi_percent, 2) }}%</td>
                        <td>{{ $p->duration_days }}</td>
                        <td><span class="badge {{ $p->is_active ? 'badge--success' : 'badge--danger' }}">{{ $p->is_active ? __('Active') : __('Inactive') }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.investment.plans.edit', $p->id) }}" class="btn btn-sm btn--info">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.investment.plans.delete', $p->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn--danger" onclick="return confirm('{{ __('Delete this plan?') }}')">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted">{{ __('No investment plans found') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $plans->links() }}
    </div>
</div>
@endsection
