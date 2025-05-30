<?php

namespace App\Http\Controllers;

use App\Models\ActivationCode;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActivationCodeController extends Controller
{
    public function codes()
    {
        $codes = ActivationCode::with('plan')->get();
        $plans = Plan::all();
        return view('admin.all-codes', compact('codes', 'plans'));
    }

    public function generateActivationCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required|exists:plans,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('form', 'error');
        }

        $codes = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            do {
                $code = Str::upper(Str::random(10));
            } while (ActivationCode::where('code', $code)->exists());

            $codes[] = ActivationCode::create([
                'plan_id' => $request->plan,
                'code' => $code,
            ]);
        }

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Activation codes generated successfully.',
        ]);
    }


    public function destroy(ActivationCode $code)
    {
        $code->delete();
        return back()->with([
            'status' => 'success',
            'message' => 'Code deleted successfully'
        ]);
    }
}
