@extends('admin.layouts.master')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">{{ __($page_title) }}</h5></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.investment.plans.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">{{ __('Name') }}</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">{{ __('Description') }}</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Min Amount') }}</label>
                <input type="number" step="0.01" min="0" name="min_amount" class="form-control" value="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Max Amount (Optional)') }}</label>
                <input type="number" step="0.01" min="0" name="max_amount" class="form-control" placeholder="0 for unlimited">
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('ROI %') }}</label>
                <input type="number" step="0.01" min="0" max="100" name="roi_percent" class="form-control" value="0" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">{{ __('Duration (Days)') }}</label>
                <input type="number" min="1" name="duration_days" class="form-control" value="30" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="is_active" class="form-select">
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn--base">{{ __('Save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
