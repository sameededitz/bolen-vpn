<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function Options()
    {
        $trial_days = Option::where('key', 'trial_days')->value('value') ?? '';
        $vpn_timeout = Option::where('key', 'vpn_timeout')->value('value') ?? '';

        $privacyPolicyContent = Option::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Option::where('key', 'tos')->value('value') ?? '';
        return view('admin.all-options', compact('privacyPolicyContent', 'tosContent', 'trial_days', 'vpn_timeout'));
    }

    public function saveInfo(Request $request)
    {
        $request->validate([
            'trial_days' => 'required|integer|min:1',
            'vpn_timeout' => 'required|integer|min:1',
        ]);

        // Save the content to the database or file system
        Option::updateOrCreate(
            ['key' => 'trial_days'],
            ['value' => $request->input('trial_days')]
        );
        Option::updateOrCreate(
            ['key' => 'vpn_timeout'],
            ['value' => $request->input('vpn_timeout')]
        );

        return redirect()->back()->with([
            'success' => 'success',
            'message' => 'Options saved successfully',
        ]);
    }

    public function saveOptions(Request $request)
    {
        $request->validate([
            'privacy_policy' => 'required',
            'tos' => 'required',
        ]);

        // Save the content to the database or file system
        Option::updateOrCreate(
            ['key' => 'privacy_policy'],
            ['value' => $request->input('privacy_policy')]
        );

        Option::updateOrCreate(
            ['key' => 'tos'],
            ['value' => $request->input('tos')]
        );

        return redirect()->back()->with([
            'success' => 'success',
            'message' => 'Options saved successfully',
        ]);
    }

    public function getOptions()
    {
        // Retrieve the current content of the Privacy Policy and Terms of Service
        $privacyPolicyContent = Option::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Option::where('key', 'tos')->value('value') ?? '';
        $vpnTimeout = Option::where('key', 'vpn_timeout')->value('value') ?? '';

        // Return the content as JSON
        return response()->json([
            'privacy_policy' => $privacyPolicyContent,
            'tos' => $tosContent,
            'vpn_timeout' => $vpnTimeout,
        ]);
    }
}
