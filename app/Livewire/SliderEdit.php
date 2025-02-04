<?php

namespace App\Livewire;

use App\Models\Slider;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class SliderEdit extends Component
{
    use WithFileUploads;

    public $slider;

    #[Validate]
    public $title;
    #[Validate]
    public $description;
    #[Validate]
    public $image;

    protected function rules()
    {
        return [
            'title' => 'required|min:3',
            'description' => 'required|max:9999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ];
    }

    public function mount(Slider $slider)
    {
        $this->slider = $slider;
        $this->title = $slider->title;
        $this->description = $slider->description;
    }

    public function submit()
    {
        $this->validate();
        $this->slider->update([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        if ($this->image) {
            $this->slider->clearMediaCollection('image');
            $this->slider->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }
        return redirect()->route('all-sliders')->with([
            'status' => 'success',
            'message' => 'Slider Updated Successfully',
        ]);
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.slider-edit');
    }
}
