@extends('layout.admin-guest')
@section('title')
    Email Verified
@endsection
@section('admin-guest')
    <section class="auth bg-base d-flex flex-wrap justify-content-center align-items-center">
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div class="card basic-data-table">
                    <div class="card-body py-80 px-32 text-center">
                        <img src="{{ asset('admin_assets/svg/email.jpg') }}" width="200px" alt="email-verified-img" class="mb-24">
                        <h6 class="mb-16">Email Verified</h6>
                        <p class="text-secondary-light">
                            Your email has been successfully verified! You can now access all features of your account.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
