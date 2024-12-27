<?php

namespace App\Http\Controllers;

use App\Models\Options;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function Options()
    {
        $titles = Options::whereIn('key', [
            'title_1',
            'subtitle_1',
            'title_2',
            'subtitle_2',
            'title_3',
            'subtitle_3',
            'title_4',
            'subtitle_4'
        ])->pluck('value', 'key')->toArray();

        $privacyPolicyContent = Options::where('key', 'privacy_policy')->value('value') ?? '';
        $tosContent = Options::where('key', 'tos')->value('value') ?? '';
        return view('admin.all-options', compact('privacyPolicyContent', 'tosContent', 'titles'));
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

    public function titles(Request $request)
    {
        $validated = $request->validate([
            'title_1'    => 'required|string|max:255',
            'subtitle_1' => 'nullable|string|max:255',
            'title_2'    => 'required|string|max:255',
            'subtitle_2' => 'nullable|string|max:255',
            'title_3'    => 'required|string|max:255',
            'subtitle_3' => 'nullable|string|max:255',
            'title_4'    => 'required|string|max:255',
            'subtitle_4' => 'nullable|string|max:255',
        ]);

        // Prepare the titles and subtitles array
        $titles = [
            'title_1'    => $validated['title_1'],
            'subtitle_1' => $validated['subtitle_1'] ?? null,
            'title_2'    => $validated['title_2'],
            'subtitle_2' => $validated['subtitle_2'] ?? null,
            'title_3'    => $validated['title_3'],
            'subtitle_3' => $validated['subtitle_3'] ?? null,
            'title_4'    => $validated['title_4'],
            'subtitle_4' => $validated['subtitle_4'] ?? null,
        ];
        foreach ($titles as $key => $value) {
            Options::updateOrCreate(
                ['key' => $key], // Match based on the key
                ['value' => $value] // Update or insert the value
            );
        }
        return redirect()->back()->with([
            'status' => 'success',
            'message' => 'Titles saved successfully',
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
    public function getTitles()
    {
        // Fetch the titles from the options table
        $titles = Options::whereIn('key', [
            'title_1',
            'subtitle_1',
            'title_2',
            'subtitle_2',
            'title_3',
            'subtitle_3',
            'title_4',
            'subtitle_4'
        ])->pluck('value', 'key')->toArray();

        // Return the titles as a JSON response
        return response()->json($titles);
    }
}
