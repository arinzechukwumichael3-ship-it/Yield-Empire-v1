@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ __($page_title) }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.investment.plans.update', $plan->id) }}" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6">
                <label class="form-label">{{ __('Name') }}</label>
                <input name="name" class="form-control" value="{{ $plan->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Description') }}</label>
                <textarea name="description" class="form-control" rows="3">{{ $plan->description }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Min Amount') }}</label>
                <input type="number" step="0.01" min="0" name="min_amount" class="form-control" value="{{ $plan->min_amount }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Max Amount (Optional)') }}</label>
                <input type="number" step="0.01" min="0" name="max_amount" class="form-control" value="{{ $plan->max_amount ?? '' }}" placeholder="0 for unlimited">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('ROI %') }}</label>
                <input type="number" step="0.01" min="0" max="100" name="roi_percent" class="form-control" value="{{ $plan->roi_percent }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Duration (Days)') }}</label>
                <input type="number" min="1" name="duration_days" class="form-control" value="{{ $plan->duration_days }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ $plan->is_active ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="0" {{ !$plan->is_active ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn--base">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
