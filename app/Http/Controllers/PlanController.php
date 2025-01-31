<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('admin.all-plans', compact('plans'));
    }

    public function AddPlan()
    {
        return view('admin.add-plan');
    }

    public function EditPlan(Plan $plan)
    {
        return view('admin.edit-plan', compact('plan'));
    }

    public function deletePlan(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('all-plans')->with([
            'status' => 'success',
            'message' => 'Plan Deleted Successfully.',
        ]);
    }

    public function plans()
    {
        $plans = Plan::all();
        return response()->json([
            'status' => true,
            'plans' => $plans,
        ]);
    }
}
