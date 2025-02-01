<?php

namespace App\Http\Controllers;

use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserFeedbackController extends Controller
{
    public function feedbacks()
    {
        $feedbacks = UserFeedback::latest()->get();

        return view('admin.all-feedback', compact('feedbacks'));
    }

    public function view(UserFeedback $feedback)
    {
        return response()->json([
            'message' => $feedback->message,
        ]);
    }

    public function store(Request $request)
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

    public function destroy(UserFeedback $feedback)
    {
        $feedback->delete();

        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Feedback deleted successfully'
        ]);
    }
}
