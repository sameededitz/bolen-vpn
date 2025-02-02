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
            <li class="fw-medium">FeedBack</li>
        </ul>
    </div>

    <div class="card basic-data-table">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">All Feedbacks</h5>
        </div>
        <div class="card-body scroll-sm" style="overflow-x: scroll">
            <table class="table display responsive bordered-table mb-0" id="myTable" data-page-length='10'>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Image</th>
                        <th scope="col">Email</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Message</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $feedback)
                        <tr>
                            <td><a href="javascript:void(0)" class="text-primary-600"> {{ $loop->iteration }} </a></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $feedback->getFirstMediaUrl('image') }}" alt="server-logo"
                                        class="w-64-px flex-shrink-0 me-12 radius-8">
                                </div>
                            </td>
                            <td>{{ $feedback->email }}</td>
                            <td>{{ $feedback->subject }}</td>
                            <td>{{ Str::limit($feedback->message, 10) }}</td>
                            <td>{{ Str::title($feedback->rating) }}</td>
                            <td>
                                C:{{ $feedback->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <button type="button" data-id="{{ $feedback->id }}" data-bs-toggle="modal"
                                        data-bs-target="#keyModal"
                                        class="view-message-btn w-32-px me-4 h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lets-icons:view-light"></iconify-icon>
                                    </button>
                                    <form action="{{ route('delete-feedback', $feedback->id) }}" method="POST">
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
                    <h6 class="modal-title" id="purchaseModalLabel">View Message</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('admin_scripts')
    <script>
        $(document).ready(function() {
            $('.view-message-btn').on('click', function() {
                const submissionId = $(this).data('id'); // Get the ID from data-id attribute
                const modalBody = $('#keyModal .modal-body'); // Select the modal body

                // Clear previous content
                modalBody.html('<p>Loading...</p>');

                // Make an AJAX request to fetch the message
                $.ajax({
                    url: `/admin/feedbacks/${submissionId}`,
                    method: 'GET',
                    success: function(response) {
                        let content = `<p>${response.message}</p>`;

                        // Check if there's an image URL and display it
                        if (response.image) {
                            content +=
                                `<img src="${response.image}" alt="Feedback Image" class="img-fluid mt-3" />`;
                        }

                        // Populate the modal body with the message and image (if any)
                        modalBody.html(content);
                    },
                    error: function() {
                        modalBody.html(
                            '<p class="text-danger">Failed to load the message. Please try again.</p>'
                        );
                    }
                });
            });
        });

        $('#myTable').DataTable({
            responsive: true
        });
    </script>
@endsection
