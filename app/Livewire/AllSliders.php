<?php

namespace App\Livewire;

use App\Models\Slider;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AllSliders extends Component
{
    use WithPagination;

    public $perPage = 5;

    #[Url('status')]
    public $filterStatus = '';

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function toggleSliderStatus($sliderId)
    {
        $slider = Slider::find($sliderId);
        if ($slider) {
            $slider->is_active = !$slider->is_active;
            $slider->save();
            $this->dispatch('sweetAlert', title: 'Success', message: 'Slider status updated successfully!', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Slider not found!', type: 'error');
        }
    }

    public function deleteSlider($sliderId)
    {
        $slider = Slider::find($sliderId);
        if ($slider) {
            $slider->clearMediaCollection('image');
            $slider->delete();
            $this->dispatch('sweetAlert', title: 'Success', message: 'Slider deleted successfully!', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error', message: 'Slider not found!', type: 'error');
        }
    }

    public function render()
    {
        $sliders = Slider::query()
            ->when($this->filterStatus !== '', function ($query) {
                return $query->where('is_active', $this->filterStatus);
            })
            ->latest('created_at')
            ->paginate($this->perPage);

        /** @disregard @phpstan-ignore-line */
        return view('livewire.all-sliders', compact('sliders'))
            ->extends('layout.app')
            ->section('content');
    }
}
