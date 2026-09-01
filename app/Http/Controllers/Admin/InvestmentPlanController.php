<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPlan;
use Illuminate\Http\Request;

class InvestmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Investment Plans');
        $q = InvestmentPlan::query();
        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->where('name', 'like', "%{$term}%");
        }
        $plans = $q->orderBy('min_amount')->paginate(20);
        return view('admin.sections.investment-plans.index', compact('page_title', 'plans'));
    }

    public function create()
    {
        $page_title = __('Create Investment Plan');
        return view('admin.sections.investment-plans.create', compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'roi_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ]);

        InvestmentPlan::create([
            'name' => $request->name,
            'description' => $request->description,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'roi_percent' => $request->roi_percent,
            'duration_days' => $request->duration_days,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.investment.plans.index')->with('success', __('Investment plan created'));
    }

    public function edit($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $page_title = __('Edit Investment Plan');
        return view('admin.sections.investment-plans.edit', compact('page_title', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'roi_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'is_active' => ['required', 'boolean'],
        ]);

        $plan->update([
            'name' => $request->name,
            'description' => $request->description,
            'min_amount' => $request->min_amount,
            'max_amount' => $request->max_amount,
            'roi_percent' => $request->roi_percent,
            'duration_days' => $request->duration_days,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.investment.plans.index')->with('success', __('Investment plan updated'));
    }

    public function destroy($id)
    {
        $plan = InvestmentPlan::findOrFail($id);
        $plan->delete();
        return back()->with('success', __('Investment plan deleted'));
    }
}
