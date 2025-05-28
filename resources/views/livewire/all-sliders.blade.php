@section('title', 'All Sliders')
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
            <li class="fw-medium">Sliders</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">All Sliders</h5>
                    <a href="{{ route('add-slider') }}">
                        <button type="button" class="btn rounded-pill btn-outline-info-600 radius-8 px-20 py-11">Add
                            Slider</button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-start flex-wrap gap-3 mb-3">
                        <select class="form-select form-select-sm w-auto ps-12 py-9 radius-12 h-40-px"
                            wire:model.live="perPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <select class="form-select form-select-sm w-auto ps-12 py-9 radius-12 h-40-px"
                            wire:model.live="filterStatus">
                            <option value="">All</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="table-responsive scrollable-pretty overflow-x-auto" id="paginated-table">
                        <table class="table display responsive bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sliders as $slider)
                                    <tr>
                                        <td>
                                            {{ $slider->id }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $slider->getFirstMediaUrl('image') }}" alt="slider_image"
                                                    class="w-64-px flex-shrink-0 me-12 radius-8">
                                            </div>
                                        </td>
                                        <td>{{ $slider->title }}</td>
                                        <td>{{ Str::limit($slider->description, 10) }}</td>
                                        <td>
                                            <span
                                                class="badge text-sm fw-semibold px-14 py-8 radius-4 {{ $slider->is_active ? 'text-success-600 bg-success-100' : 'text-danger-600 bg-danger-100' }}">
                                                {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button"
                                                    class="w-32-px h-32-px rounded-circle d-inline-flex align-items-center justify-content-center
                                                        {{ $slider->is_active ? 'bg-warning-focus text-warning-main' : 'bg-info-focus text-info-main' }}"
                                                    wire:click="$js.confirmToggle({{ $slider->id }}, {{ $slider->is_active ? 'true' : 'false' }})">
                                                    <iconify-icon
                                                        icon="{{ $slider->is_active ? 'lsicon:disable-outline' : 'hugeicons:tick-02' }}"></iconify-icon>
                                                </button>
                                                <a href="{{ route('edit-slider', $slider->id) }}"
                                                    class="w-32-px me-4 h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                                </a>
                                                <button type="button"
                                                    wire:click="$js.confirmDelete({{ $slider->id }})"
                                                    class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No sliders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-24">
                        {{ $sliders->links('components.pagination', data: ['scrollTo' => '#paginated-table']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@script
    <script>
        $js('confirmToggle', (id, status) => {
            Swal.fire({
                title: 'Are you sure?',
                text: status ? "Deactivate this slider?" : "Activate this slider?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: status ? 'Yes, deactivate it!' : 'Yes, activate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.toggleSliderStatus(id);
                }
            });
        })

        $js('confirmDelete', (id) => {
            Swal.fire({
                title: 'Delete this slider?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteSlider(id);
                }
            });
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
