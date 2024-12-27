<?php

namespace App\Livewire;

use App\Models\Server;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ServerCreate extends Component
{
    #[Validate]
    public $name;

    #[Validate]
    public $config;
    #[Validate]
    public $username;
    #[Validate]
    public $password;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'config' => 'required|string',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ];
    }

    public function submit()
    {
        if (Server::exists()) {
            return redirect()->route('all-servers')->with([
                'status' => 'error',
                'message' => 'Only one server can be added.',
            ]);
        }

        $this->validate();
        $server = Server::create([
            'name' => $this->name,
            'config' => $this->config,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        return redirect()->route('all-servers')->with([
            'status' => 'success',
            'message' => 'Server Added Successfully',
        ]);
    }
    public function render()
    {
        return view('livewire.server-create');
    }
}
