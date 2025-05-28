@section('title', 'All Users')
<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0"></h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Users</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">All Users</h5>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#userModel" wire:click="resetForm"
                        class="btn rounded-pill btn-outline-info-600 radius-8 px-20 py-11">Add
                        User</button>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 flex-wrap gap-3 align-items-center">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <select class="form-select" wire:model.live="perPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="navbar-search">
                                <input type="text" class="bg-base h-40-px w-auto" name="search"
                                    wire:model.live.500ms="search" placeholder="Search">
                                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-rounded d-flex" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <iconify-icon icon="line-md:filter" width="24" height="24"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" wire:ignore.self>
                                    <li>
                                        <div
                                            class="dropdown-header text-start d-flex align-items-center justify-content-between mb-3 px-0">
                                            <h6 class="text-uppercase mb-0 text-md">Filters</h6>
                                            <p class="text-danger mb-0 text-md cursor-pointer"
                                                wire:click="resetFilters">Reset</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1">Email Verified</label>
                                            <select class="form-select form-control-sm" wire:model.live="emailVerified">
                                                <option value="">All</option>
                                                <option value="1">Verified</option>
                                                <option value="0">Not Verified</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1">Registered From</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model.live="registeredStart">
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1">Registered To</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model.live="registeredEnd">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive scrollable-pretty overflow-x-auto" id="paginated-table">
                        <table class="table display responsive bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Plan</th>
                                    <th>Last Login</th>
                                    <th>Joined</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->activePlan ? $user->activePlan->plan->name : 'N/A' }} </td>
                                        <td>{{ $user->last_login ? $user->last_login->diffForHumans() : 'N/A' }}
                                        </td>
                                        <td>{{ $user->created_at->toFormattedDateString() }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('manage-user', $user->id) }}"
                                                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center bg-info-focus text-info-main">
                                                    <iconify-icon icon="ic:round-manage-accounts"></iconify-icon>
                                                </a>
                                                <button type="button" wire:click="editUser({{ $user->id }})"
                                                    data-bs-toggle="modal" data-bs-target="#userModel"
                                                    class="w-32-px me-4 h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </button>
                                                <button type="button"
                                                    wire:click="$js.confirmDelete({{ $user->id }})"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-24">
                        {{ $users->links('components.pagination', data: ['scrollTo' => '#paginated-table']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userModel" tabindex="-1" wire:ignore.self aria-labelledby="userModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        {{ $isEdit ? 'Edit User' : 'Add New User' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"
                        aria-label="Close"></button>
                </div>
                <form class="row g-2" wire:submit.prevent="saveUser">
                    <div class="modal-body">
                        <div class="col-12 mb-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Name"
                                wire:model.defer="name">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email"
                                wire:model.defer="email">
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!$isEdit)
                            <div class="col-sm-12 mb-2">
                                <div class="row g-2">
                                    <div class="col-sm-12">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="text" class="form-control" id="password"
                                            wire:model="password" placeholder="Password">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($isEdit)
                            <div class="col-12 mb-2">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select w-100" id="role" wire:model.defer="role">
                                    <option value="">Select Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                                @error('role')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-outline-info d-flex align-items-center justify-content-center"
                            wire:click="resetForm" data-bs-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn btn-outline-success d-flex align-items-center justify-content-center">
                            {{ $isEdit ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        $js('confirmDelete', (id) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteUser(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModel'));
            modal.hide();
        });

        $wire.on('sweetAlert', (event) => {
            Swal.fire({
                title: event.title,
                text: event.message,
                icon: event.type,
                timer: 2000,
                showConfirmButton: false
            });
        });
    </script>
@endscript
