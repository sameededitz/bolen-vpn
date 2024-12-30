<div>
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Sliders</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin-home') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Sliders</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Edit Slider</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="py-2">
                            @foreach ($errors->all() as $error)
                                <x-alert type="danger" :message="$error" />
                            @endforeach
                        </div>
                    @endif
                    <form wire:submit.prevent="submit">
                        <div class="row gy-3">
                            <div class="col-12">
                                <label class="form-label">Image</label>
                                <div class="upload-image-wrapper d-flex align-items-center gap-3">
                                    <div
                                        class="uploaded-img position-relative h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50">
                                        @if ($image)
                                            <button type="button" wire:click="removeImage"
                                                class="uploaded-img__remove position-absolute top-0 end-0 z-1 text-2xxl line-height-1 me-8 mt-8 d-flex">
                                                <iconify-icon icon="radix-icons:cross-2"
                                                    class="text-xl text-danger-600"></iconify-icon>
                                            </button>
                                        @endif
                                        <img id="uploaded-img__preview" class="w-100 h-100 object-fit-cover"
                                            src="{{ $image ? $image->temporaryUrl() : $slider->getFirstMediaUrl('image') }}"
                                            alt="server_image">
                                    </div>
                                    <label
                                        class="upload-file h-120-px w-120-px border input-form-light radius-8 overflow-hidden border-dashed bg-neutral-50 bg-hover-neutral-200 d-flex align-items-center flex-column justify-content-center gap-1"
                                        for="upload-file">
                                        <iconify-icon icon="solar:camera-outline"
                                            class="text-xl text-secondary-light"></iconify-icon>
                                        <span class="fw-semibold text-secondary-light">Upload</span>
                                        <input id="upload-file" wire:model.live="image" type="file" hidden>
                                        <div wire:loading wire:target="image">Uploading...</div>
                                    </label>
                                </div>
                                <p class="text-sm mt-1 mb-0">The Image Should be Less than 20MB.</p>

                            </div>
                            <div class="col-12">
                                <label class="form-label">Title</label>
                                <input type="text" wire:model.blur="title" class="form-control" placeholder="Title">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" wire:model.blur="description" class="form-control" cols="10" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary-600">Add</button>
                        </div>
                    </form>
                </div>
            </div><!-- card end -->
        </div>
    </div>
</div>
