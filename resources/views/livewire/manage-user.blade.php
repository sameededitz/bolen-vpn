@section('title', 'Manage User')
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
            <li>-</li>
            <li class="fw-medium">Manage</li>
        </ul>
    </div>

    <div class="row gy-4">
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Personal Info</h5>
                </div>
                <div class="card-body">
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Status</span>
                            <span class="w-70 fw-semibold">:
                                <span
                                    class="badge text-sm fw-semibold px-14 py-8 radius-4 {{ $user->isOnline() ? 'text-success-600 bg-success-100' : 'text-danger-600 bg-danger-100' }}">
                                    {{ $user->isOnline() ? 'Online' : 'Offline' }}
                                </span>
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Name</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ Str::title($user->name) }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Email</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->email }} </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Role</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ Str::title($user->role) }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Last Login</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                                {{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }} </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Last Seen</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                                {{ $user->isOnline() ? $user->online_at->diffForHumans() : 'N/A' }} </span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Verified</span>
                            <span class="w-70 text-secondary-light fw-normal">:
                                {{ $user->hasVerifiedEmail() ? $user->email_verified_at->toDayDateTimeString() : 'No' }}
                            </span>
                        </li>
                        @if ($user->isBanned())
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Banned At</span>
                                <span class="w-70 text-secondary-light fw-normal">:
                                    {{ $user->banned_at->toDayDateTimeString() }} </span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light"> Ban Reason</span>
                                <span class="w-70 text-secondary-light fw-normal">:
                                    {{ $user->ban_reason }} </span>
                            </li>
                        @endif
                        <li class="d-flex align-items-center gap-1 mb-0">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Joined</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                                {{ $user->created_at->toDayDateTimeString() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Actions</h5>
                </div>
                <div class="card-body d-flex flex-column gap-2">

                    {{-- Email Verification Actions --}}
                    @if (!$user->hasVerifiedEmail())
                        <button class="btn btn-outline-success _effect--ripple" wire:click="verifyEmailManually">
                            <i class="bx bx-check-circle me-1"></i>
                            Verify Email Manually
                        </button>

                        <button class="btn btn-outline-primary _effect--ripple" wire:click="resendVerificationEmail">
                            <i class="bx bx-mail-send me-1"></i>
                            Resend Verification Email
                        </button>
                    @endif

                    <button class="btn btn-outline-primary _effect--ripple" wire:click="$js.confirmResetPassword()">
                        <i class="bx bx-mail-send me-1"></i>
                        Send Password Reset Link
                    </button>

                    <button class="btn btn-outline-warning" wire:click="$js.confirmRevokeAllDevices()">
                        <i class="bx bx-user-x me-1"></i>
                        Revoke All Access
                    </button>

                    @if ($user->isBanned())
                        <button class="btn btn-outline-success _effect--ripple" wire:click="unbanUser">
                            <i class="bx bx-user-check me-1"></i>
                            Unban User
                        </button>
                    @else
                        <button class="btn btn-outline-danger _effect--ripple" wire:click="$js.confirmBanUser()">
                            <i class="bx bx-user-x me-1"></i>
                            Ban User
                        </button>
                    @endif

                    <button class="btn btn-outline-danger _effect--ripple" wire:click="$js.confirmDelete()">
                        <i class="bx bx-trash me-1"></i>
                        Delete User
                    </button>

                </div>
            </div>
        </div>
        <div class="col-md-6 col-12">
            <div class="card h-100 p-0">
                <div class="card-header border-bottom bg-base py-16 px-24">
                    <h6 class="text-lg fw-semibold mb-0">Devices</h6>
                </div>
                <div class="card-body p-24">
                    <div class="scrollable-pretty mh-300 overflow-y-auto">
                        <p class="text-secondary-light mb-16">
                            Below are the devices currently associated with this user. You can revoke access to any
                            device if necessary.
                        </p>
                        <ul class="list-group radius-8">
                            @forelse ($user->devices as $device)
                                <li class="list-group-item border text-secondary-light p-16 bg-base">
                                    <div
                                        class="d-flex justify-content-between align-items-start flex-column flex-md-row gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($device->device_type === 'web')
                                                <iconify-icon icon="arcticons:emoji-web" width="48"
                                                    height="48"></iconify-icon>
                                            @elseif ($device->device_type === 'mobile')
                                                <iconify-icon icon="mdi:cellphone" width="48"
                                                    height="48"></iconify-icon>
                                            @elseif ($device->device_type === 'desktop')
                                                <iconify-icon icon="mdi:desktop-classic" width="48"
                                                    height="48"></iconify-icon>
                                            @else
                                                <iconify-icon icon="mdi:devices" width="48"
                                                    height="48"></iconify-icon>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $device->device_name }}</div>
                                                <small>
                                                    {{ $device->platform }} | {{ ucfirst($device->device_type) }} |
                                                    IP:
                                                    {{ $device->ip_address }}
                                                </small><br>
                                                <small>
                                                    Last Active:
                                                    {{ $device->last_active_at ? $device->last_active_at->diffForHumans() : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger mt-2 mt-md-0"
                                            wire:click="$js.confirmRevokeDevice({{ $device->id }})">
                                            Revoke
                                        </button>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item border text-secondary-light p-16 bg-base">
                                    No devices found.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @if ($user->role == 'user')
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Manage Plan</h5>
                    </div>
                    <div class="card-body">
                        <h6>Active Plan</h6>
                        <ul class="ps-0 fs-6 border-bottom pb-2 mb-16">
                            @if ($user->activePlan)
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 fw-semibold text-primary-light">Plan</span>
                                    <span class="w-70 fw-medium">: {{ $user->activePlan->plan->name }} </span>
                                </li>
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-30 text-md fw-semibold text-primary-light"> Expires At </span>
                                    <span class="w-70 text-secondary-light fw-normal">:
                                        {{ $user->activePlan->end_date->toDayDateTimeString() }} </span>
                                </li>
                                <button class="btn btn-outline-info mb-2 _effect--ripple waves-effect waves-light"
                                    wire:click="$js.confirmCancelPlan()">
                                    Cancel Plan
                                </button>
                            @else
                                <li class="d-flex align-items-center gap-1 mb-12">
                                    <span class="w-20 text-md fw-semibold text-primary-light">Plan</span>
                                    <span class="w-80 text-secondary-light fw-normal">: No Active Plan </span>
                                </li>
                            @endif
                        </ul>
                        <h6>Add or Extend Plan</h6>
                        @if ($errors->any())
                            <x-alert type="warning" message="{{ $errors->first() }}" />
                        @endif
                        <div class="form-group mb-3">
                            <select class="form-select w-100" id="exampleFormControlSelect1"
                                wire:model="selectedPlan">
                                <option value="" selected>Select Plan</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}
                                        ({{ $plan->duration }}
                                        {{ Str::plural($plan->duration_unit, $plan->duration) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-outline-info _effect--ripple waves-effect waves-light"
                            wire:click="$js.confirmAddPlan()">
                            @if ($user->activePlan)
                                Extend Plan
                            @else
                                Add Plan
                            @endif
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment History</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group radius-8">
                            @forelse ($user->purchases->sortByDesc('created_at') as $purchase)
                                <li
                                    class="list-group-item border text-secondary-light p-16 bg-base d-flex align-items-center justify-content-between">
                                    <div class="me-auto">
                                        <div class="fw-bold title">{{ $purchase->plan->name }}</div>
                                        <p class="sub-title mb-0">
                                            ({{ $purchase->start_date->toFormattedDateString() }}
                                            -
                                            {{ $purchase->end_date->toFormattedDateString() }})
                                            - {{ Str::title($purchase->status) }}</p>
                                    </div>
                                    <span
                                        class="pay-pricing align-self-center me-3">${{ $purchase->amount_paid }}</span>
                                </li>
                            @empty
                                <li class="list-group-item border text-secondary-light p-16 bg-base border-bottom-0">
                                    <p class="mb-0">No purchase history available.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@script
    <script>
        $js('confirmCancelPlan', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to cancel this plan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.cancelPurchase();
                }
            });
        });

        $js('confirmAddPlan', () => {
            Swal.fire({
                title: 'Confirm Plan Change?',
                text: 'Are you sure you want to add or extend this plan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.addPlan();
                }
            });
        });

        $js('confirmBanUser', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will prevent the user from accessing the system.',
                icon: 'warning',
                input: 'text',
                inputLabel: 'Reason for banning',
                inputPlaceholder: 'Enter reason for banning',
                showCancelButton: true,
                confirmButtonText: 'Yes, ban user!',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Reason is required');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    $wire.banUser(result.value);
                }
            });
        });

        $js('confirmDelete', () => {
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
                    $wire.deleteUser();
                }
            });
        });

        $js('confirmRevokeDevice', (deviceId) => {
            Swal.fire({
                title: 'Revoke Access?',
                text: 'Are you sure you want to logout this device?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, revoke',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeDevice(deviceId);
                }
            });
        });

        $js('confirmRevokeAllDevices', () => {
            Swal.fire({
                title: 'Revoke All Access?',
                text: 'This will logout the user from all devices. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, revoke all',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.revokeAllDevices();
                }
            });
        });

        $js('confirmResetPassword', () => {
            Swal.fire({
                title: 'Send Password Reset Link?',
                text: 'This will also revoke all current ongoing sessions. Continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, send link',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.sendPasswordResetLink();
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

        $wire.on('redirect', (event) => {
            setTimeout(() => {
                window.location.href = event.url;
            }, timeout = 2000);
        });
    </script>
@endscript
