<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function Plans()
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
            'message' => 'Plan Deleted Successfully. Make sure to Delete Product from Stripe Dashboard',
        ]);
    }
}
