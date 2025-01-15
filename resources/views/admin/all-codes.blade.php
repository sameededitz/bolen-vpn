@extends('layout.admin-layout')
@section('admin_content')
    @if (session('status'))
        <div class="row py-3">
            <div class="col-6">
                <x-alert :type="session('status', 'info')" :message="session('message', 'Operation completed successfully.')" />
            </div>
        </div>
    @endif

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
            <li class="fw-medium">Activation Codes</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">All Codes</h5>
            <button type="button" data-bs-toggle="modal" data-bs-target="#keyModal"
                class="btn rounded-pill btn-outline-info-600 radius-8 px-20 py-11">Generate</button>
        </div>
        <div class="card-body scroll-sm" style="overflow-x: scroll">
            <table class="table display responsive bordered-table mb-0" id="myTable" data-page-length='10'>
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
                    @foreach ($codes as $code)
                        <tr>
                            <td><a href="javascript:void(0)" class="text-primary-600"> {{ $loop->iteration }} </a></td>
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
                                    <form action="{{ route('delete-code', $code->code) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="keyModal" tabindex="-1" aria-labelledby="keyLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Generate Codes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="py-2">
                            @foreach ($errors->all() as $error)
                                <x-alert type="danger" :message="$error" />
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('generate-code') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="plan" class="form-label">Plan</label>
                            <select name="plan" class="form-select">
                                <option value="" selected>Select Plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('plan') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('admin_scripts')
    <script>
        @if (session('form'))
            var myModal = new bootstrap.Modal(document.getElementById('keyModal'));
            myModal.show();
        @endif
        $('#myTable').DataTable({
            responsive: true
        });
    </script>
@endsection
