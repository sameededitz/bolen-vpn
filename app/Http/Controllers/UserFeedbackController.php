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
            'image' => $feedback->getFirstMediaUrl('image'),
        ]);
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
