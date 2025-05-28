<?php

namespace App\Livewire;

use App\Models\Slider;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class SliderAdd extends Component
{
    use WithFileUploads;

    public $title;
    public $description;
    #[Validate]
    public $image;

    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'description' => 'required|max:9999',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ];
    }

    public function submit()
    {
        $this->validate();
        $slider = Slider::create([
            'title' => $this->title,
            'description' => $this->description,
        ]);

        if ($this->image) {
            $slider->addMedia($this->image->getRealPath())
                ->usingFileName($this->image->getClientOriginalName())
                ->toMediaCollection('image');
        }

        $this->reset(['title', 'description', 'image']);

        $this->dispatch('sweetAlert', title: 'Success', message: 'Slider added successfully!', type: 'success');
        $this->dispatch('redirect', url: route('all-sliders'));
        return;
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        /** @disregard @phpstan-ignore-line */
        return view('livewire.slider-add')
            ->extends('layout.app')
            ->section('content');
    }
}
