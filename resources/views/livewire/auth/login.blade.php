@section('title', 'Login')
<div>
    <section class="auth bg-base d-flex justify-content-center align-items-center">
        <div class="row w-100 justify-content-center align-items-center">
            <div class="col-lg-4 col-md-6 col-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <x-alert type="info" :message="session('status')" />
                        @endif
                        <div class="text-center mb-32">
                            <a href="{{ route('admin-home') }}" class="mb-12 max-w-290-px">
                                <img src="{{ asset('admin_assets/images/logo.png') }}" class="rounded" alt="">
                            </a>
                            <p class="mb-32 text-secondary-light text-lg">Welcome back! please enter your detail</p>
                        </div>
                        @if ($errors->any())
                            <div class="py-2">
                                @foreach ($errors->all() as $error)
                                    <x-alert type="danger" :message="$error" />
                                @endforeach
                            </div>
                        @endif
                        <form wire:submit.prevent="login" class="form">
                            <div class="icon-field mb-16">
                                <span class="icon top-50 translate-middle-y">
                                    <iconify-icon icon="mage:email"></iconify-icon>
                                </span>
                                <input type="text" name="email" wire:model="email"
                                    class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Email">
                            </div>
                            <div class="position-relative mb-20" x-data="{ show: false }">
                                <div class="icon-field">
                                    <span class="icon top-50 translate-middle-y">
                                        <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                    </span>
                                    <input :type="show ? 'text' : 'password'" name="password" wire:model="password"
                                        class="form-control h-56-px bg-neutral-50 radius-12" id="your-password"
                                        placeholder="Password">
                                </div>
                                <span
                                    class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                    @click="show = !show" x-bind:class="show ? 'ri-eye-off-line' : 'ri-eye-line'"
                                    style="font-size: 1.3em;" title="Toggle Password">
                                </span>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="form-check style-check d-flex align-items-center">
                                        <input class="form-check-input border border-neutral-300" type="checkbox"
                                            wire:model="remember" id="remember">
                                        <label class="form-check-label" for="remember">Remember me </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                                Sign
                                In</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>