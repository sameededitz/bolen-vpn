<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Purchase;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AllUsers extends Component
{
    public $users;
    public $selectedUser;

    public $plans;
    #[Validate]
    public $plan_id;

    protected  function rules()
    {
        return [
            'plan_id' => 'required|exists:plans,id',
        ];
    }

    public function mount()
    {
        // Load all users with the 'customer' role
        $this->users = User::where('role', 'customer')
            ->with(['purchases' => function ($query) {
                $query->latest()->first();
            }])
            ->get();
        $this->plans = Plan::all();
    }

    public function addPurchase()
    {
        $this->validate();

        $plan = Plan::find($this->plan_id);

        $this->selectedUser->purchases()->create([
            'plan_id' => $plan->id,
            'started_at' => now(),
            'expires_at' => now()->addMonths($plan->duration),
            'is_active' => true,
        ]);

        $this->selectedUser = null;
        $this->plan_id = '';

        // Reload users to update the table
        $this->mount();

        $this->dispatch('close-modal');
        $this->dispatch('alert_add', ['type' => 'success', 'message' => 'Purchase added successfully!']);
    }

    public function openModal(User $user)
    {
        $this->selectedUser = $user;
        $this->plan_id = '';
        $this->dispatch('open-modal');
    }

    public function clearPurchase(User $user)
    {
        $user->purchases()->delete();
        // Reload users to update the table
        $this->mount();

        $this->dispatch('alert_clear', ['type' => 'success', 'message' => 'Purchase cleared successfully!']);
    }

    public function render()
    {
        return view('livewire.all-users');
    }
}