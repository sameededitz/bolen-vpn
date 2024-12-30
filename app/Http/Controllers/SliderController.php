<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.all-sliders', compact('sliders'));
    }

    public function destroy(Slider $slider)
    {
        $slider->clearMediaCollection('image');
        $slider->delete();
        return redirect()->route('all-sliders')->with([
            'status' => 'success',
            'message' => 'Slider Deleted Successfully',
        ]);
    }

    public function sliders()
    {
        $sliders = Slider::all();
        return response()->json([
            'status' => true,
            'sliders' => $sliders,
        ]);
    }
}
