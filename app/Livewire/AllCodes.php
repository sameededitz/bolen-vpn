<?php

namespace App\Livewire;

use App\Models\Plan;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivationCode;

class AllCodes extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 5;

    public $plan;
    public $quantity = 1;

    public function resetForm()
    {
        $this->reset([
            'plan',
            'quantity',
        ]);
        $this->resetValidation();
    }

    protected function rules()
    {
        return [
            'plan' => 'required|exists:plans,id',
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }

    public function generateCodes()
    {
        $this->validate();

        $plan = Plan::find($this->plan);
        if (!$plan) {
            $this->dispatch('sweetAlert', title: 'Error!', message: 'Plan not found!', type: 'error');
            return;
        }
        ActivationCode::generateCodes($plan, $this->quantity);

        $this->dispatch('closeModel');
        $this->dispatch('sweetAlert', title: 'Success!', message: 'Codes generated successfully!', type: 'success');
        $this->resetPage();
        $this->resetForm();
    }

    public function deleteCode($codeId)
    {
        $code = ActivationCode::find($codeId);
        if ($code) {
            if ($code->isUsed()) {
                $this->dispatch('sweetAlert', title: 'Error!', message: 'Cannot delete a used code!', type: 'info');
                return;
            }
            $code->delete();
            $this->dispatch('sweetAlert', title: 'Success!', message: 'Code deleted successfully!', type: 'success');
        } else {
            $this->dispatch('sweetAlert', title: 'Error!', message: 'Code not found!', type: 'error');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $plans = Plan::where('id', '!=', 1)->get();

        $codes = ActivationCode::query()
            ->with(['plan', 'user'])
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.all-codes', compact('codes', 'plans'));
    }
}
