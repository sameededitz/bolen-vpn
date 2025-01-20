<?php

namespace App\Livewire;

use App\Models\Server;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ServerEdit extends Component
{
    public $server;

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

    public function mount(Server $server)
    {
        $this->server = $server;
        $this->name = $server->name;
        $this->config = $server->config;
        $this->username = $server->username;
        $this->password = $server->password;
    }

    public function update()
    {
        $this->server->update([
            'name' => $this->name,
            'config' => $this->config,
            'username' => $this->username,
            'password' => $this->password,
        ]);

        return redirect()->route('all-servers')->with([
            'status' => 'success',
            'message' => 'Server Updated Successfully',
        ]);
    }

    public function render()
    {
        return view('livewire.server-edit');
    }
}
