<div>
    <div class="py-2">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-alert type="danger" :message="$error" />
            @endforeach
        @endif
    </div>
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        <div class="row gy-3">
            <div class="col-12">
                <label class="form-label">Name</label>
                <input type="text" wire:model.live="name" class="form-control" placeholder="Server Name">
            </div>
            <div class="col-12">
                <label class="form-label">Username</label>
                <input type="text" wire:model.blur="username" class="form-control" placeholder="Username">
            </div>
            <div class="col-12">
                <label class="form-label">Password</label>
                <input type="text" wire:model.blur="password" class="form-control" placeholder="Password">
            </div>
            <div class="col-12">
                <label class="form-label">Config</label>
                <textarea name="config" wire:model.blur="config" class="form-control" cols="10" rows="2"
                    placeholder="Config"></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary-600">Submit</button>
            </div>
        </div>
    </form>
</div>
