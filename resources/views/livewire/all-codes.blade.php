@section('title', 'All Codes')
<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0"></h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin-home') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Codes</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">All Codes</h5>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#codeModel" wire:click="resetForm"
                        class="btn rounded-pill btn-outline-info-600 radius-8 px-20 py-11">Generate
                        Code</button>
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
                                            <label class="mb-1">Used/Unused</label>
                                            <select class="form-select form-control-sm" wire:model.live="isUsed">
                                                <option value="">All</option>
                                                <option value="1">Used</option>
                                                <option value="0">Unused</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1">Plan</label>
                                            <select class="form-select form-control-sm" wire:model.live="selectedPlan">
                                                <option value="">All Plans</option>
                                                @foreach ($plans as $plan)
                                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                @endforeach
                                            </select>
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
                                    <th scope="col">#</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Plan</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Is Used</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($codes as $code)
                                    <tr>
                                        <td>{{ $code->id }}</td>
                                        <td>{{ $code->code }}</td>
                                        <td>{{ $code->plan->name }}</td>
                                        <td>
                                            @if ($code->user_id)
                                                {{ ucfirst($code->user->name) }}
                                            @else
                                                <span
                                                    class="badge text
                                    -sm fw-semibold text-info-600 bg-info-100 px-20 py-9 radius-4 text-white">Not
                                                    Used</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($code->is_used)
                                                <span
                                                    class="badge text-sm fw-semibold text-success-600 bg-success-100 px-20 py-9 radius-4 text-white">Yes</span>
                                            @else
                                                <span
                                                    class="badge text-sm fw-semibold text-danger-600 bg-danger-100 px-20 py-9 radius-4 text-white">No</span>
                                            @endif
                                        <td>
                                            {{ $code->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <button type="button"
                                                    wire:click="$js.confirmDelete({{ $code->id }})"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No Codes found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-24">
                        {{ $codes->links('components.pagination', data: ['scrollTo' => '#paginated-table']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="codeModel" tabindex="-1" wire:ignore.self aria-labelledby="codeModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Generate Codes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" wire:click="resetForm"
                        aria-label="Close"></button>
                </div>
                <form class="row g-2" wire:submit.prevent="generateCodes">
                    <div class="modal-body">
                        <div class="col-12 mb-2">
                            <label for="name" class="form-label">Plan</label>
                            <select wire:model.defer="plan" class="form-select">
                                <option selected>Select Plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}</option>
                                @endforeach
                            </select>
                            @error('plan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" wire:model.defer="quantity">
                            @error('quantity')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-outline-info d-flex align-items-center justify-content-center"
                            wire:click="resetForm" data-bs-dismiss="modal">Close</button>
                        <button type="submit"
                            class="btn btn-outline-success d-flex align-items-center justify-content-center">
                            Generate
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
                    $wire.deleteCode(id);
                }
            });
        });

        $wire.on('closeModel', (event) => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('codeModel'));
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
