<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function Options()
    {
        $privacyPolicyContent = Options::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Options::where('key', 'tos')->value('value') ?? '';
        return view('admin.all-options', compact('privacyPolicyContent', 'tosContent'));
    }

    public function saveOptions(Request $request)
    {
        $request->validate([
            'privacy_policy' => 'required',
            'tos' => 'required',
        ]);

        // Save the content to the database or file system
        Options::updateOrCreate(
            ['key' => 'privacy_policy'],
            ['value' => $request->input('privacy_policy')]
        );

        Options::updateOrCreate(
            ['key' => 'tos'],
            ['value' => $request->input('tos')]
        );

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Options saved successfully',
        ]);
    }

    public function getOptions()
    {
        // Retrieve the current content of the Privacy Policy and Terms of Service
        $privacyPolicyContent = Options::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Options::where('key', 'tos')->value('value') ?? '';

        // Return the content as JSON
        return response()->json([
            'privacy_policy' => $privacyPolicyContent,
            'tos' => $tosContent,
        ]);
    }
}
