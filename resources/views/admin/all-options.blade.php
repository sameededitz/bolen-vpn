@extends('layout.admin-layout')
@section('title')
    Options | Admin
@endsection
@section('admin_content')
    @if (session('status'))
        <div class="row py-3">
            <div class="col-6">
                <x-alert :type="session('status', 'info')" :message="session('message', 'Operation completed successfully.')" />
            </div>
        </div>
    @endif
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Options</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin-home') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Options</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-12">
            <div class="card">
                <div
                    class="card-header pt-16 pb-0 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                    <h6 class="text-lg mb-0">Settings</h6>
                    <ul class="nav bordered-tab d-inline-flex nav-pills mb-0" id="pills-tab-six" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-16 py-10 active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true" tabindex="-1">Titles</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-16 py-10" id="pills-details-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-details" type="button" role="tab" aria-controls="pills-details"
                                aria-selected="false">TOS</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-24 pt-16 mt-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div>
                                @if ($errors->any())
                                    <div class="py-2">
                                        @foreach ($errors->all() as $error)
                                            <x-alert type="danger" :message="$error" />
                                        @endforeach
                                    </div>
                                @endif
                                <form action="{{ route('save-titles') }}" method="POST">
                                    @csrf
                                    <div class="row mb-24 gy-3 align-items-center">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label mb-0 col-sm-3 w-100">Main Title</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" name="main_title" class="form-control"
                                                placeholder="Main Title" value="{{ $titles['main_title'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-24 gy-3 align-items-center">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label mb-0 col-sm-3 w-100">Title 1</label>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="title_1" class="form-control"
                                                value="{{ $titles['title_1'] ?? '' }}" required placeholder="Title 1">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="subtitle_1" class="form-control"
                                                value="{{ $titles['subtitle_1'] ?? '' }}" required
                                                placeholder="Sub Title 1">
                                        </div>
                                    </div>
                                    <div class="row mb-24 gy-3 align-items-center">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label mb-0 col-sm-3 w-100">Title 2</label>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="title_2" class="form-control"
                                                value="{{ $titles['title_2'] ?? '' }}" required placeholder="Title 2">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="subtitle_2" class="form-control"
                                                value="{{ $titles['subtitle_2'] ?? '' }}" required
                                                placeholder="Sub Title 2">
                                        </div>
                                    </div>
                                    <div class="row mb-24 gy-3 align-items-center">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label mb-0 col-sm-3 w-100">Title 3</label>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="title_3" class="form-control"
                                                value="{{ $titles['title_3'] ?? '' }}" required placeholder="Title 3">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="subtitle_3" class="form-control"
                                                value="{{ $titles['subtitle_3'] ?? '' }}" required
                                                placeholder="Sub Title 3">
                                        </div>
                                    </div>
                                    <div class="row mb-24 gy-3 align-items-center">
                                        <div class="col-12 col-sm-3">
                                            <label class="form-label mb-0 col-sm-3 w-100">Title 4</label>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="title_4" class="form-control"
                                                value="{{ $titles['title_4'] ?? '' }}" required placeholder="Title 4">
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <input type="text" name="subtitle_4" class="form-control"
                                                value="{{ $titles['subtitle_4'] ?? '' }}" required
                                                placeholder="Sub Title 4">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary-600">Submit</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab" tabindex="0">
                            <div>
                                @if ($errors->any())
                                    <div class="py-2">
                                        @foreach ($errors->all() as $error)
                                            <x-alert type="danger" :message="$error" />
                                        @endforeach
                                    </div>
                                @endif
                                <form id="contentForm" action="{{ route('save-options') }}" method="POST">
                                    @csrf
                                    <div class="row gy-3">
                                        <div class="col-12">
                                            <label class="form-label" for="privacy_policy">Privacy Policy</label>
                                            <textarea name="privacy_policy" id="privacy_policy" class="form-control tinymce-editor">{{ $privacyPolicyContent }}</textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="terms_of_service">Terms of Service</label>
                                            <textarea id="tosEditor" name="tos" class="form-control tinymce-editor">{{ $tosContent }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary-600">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
@section('admin_scripts')
    <script src="https://cdn.tiny.cloud/1/profov2dlbtwaoggjfvbncp77rnjhgyfnl3c2hx3kzpmhif1/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-editor',
            setup: function(editor) {
                editor.on('blur', function() {
                    editor.save(); // Automatically save content to the textarea when focus is lost
                });
            }
        });

        document.getElementById('contentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            tinymce.triggerSave(); // Save the content of all TinyMCE editors to their respective textareas
            form.submit(); // Now submit the form
        });
    </script>
@endsection
