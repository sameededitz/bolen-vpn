<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use App\Models\Option;
use App\Models\Server;
use App\Models\Slider;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function servers()
    {
        $servers = Server::all();
        return response()->json([
            'status' => true,
            'servers' => $servers
        ], 200);
    }

    public function plans()
    {
        $plans = Plan::where('id', '!=', 1)
            ->get();
        return response()->json([
            'status' => true,
            'plans' => PlanResource::collection($plans),
        ]);
    }

    public function sliders()
    {
        $sliders = Slider::active()->get();
        return response()->json([
            'status' => true,
            'sliders' => SliderResource::collection($sliders),
        ]);
    }

    public function options()
    {
        // Retrieve the current content of the Privacy Policy and Terms of Service
        $privacyPolicyContent = Option::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Option::where('key', 'tos')->value('value') ?? '';

        // Return the content as JSON
        return response()->json([
            'privacy_policy' => $privacyPolicyContent,
            'tos' => $tosContent,
        ]);
    }

    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'rating' => 'required|in:good,bad',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:20420',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ], 400);
        }

        $feedback = UserFeedback::create($request->only([
            'email',
            'subject',
            'message',
            'rating',
        ]));

        if ($request->hasFile('image')) {
            $feedback->addMedia($request->file('image'))
                ->usingFileName($request->file('image')->getClientOriginalName())
                ->toMediaCollection('image');
        }

        return response()->json([
            'status' => true,
            'message' => 'Feedback submitted successfully!',
            'feedback' => $feedback
        ], 201);
    }
}
