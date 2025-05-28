<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    @include('partials.admin-head')
</head>

<body>
    @include('partials.admin-sidebar')

    <main class="dashboard-main">
        @include('partials.admin-navbar')

        <div class="dashboard-main-body">
            @yield('content')
        </div>

        <footer class="d-footer">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <p class="mb-0">© {{ date('Y') }}. All Rights Reserved.</p>
                </div>
                <div class="col-auto">
                    <p class="mb-0">Made by <a href="https://www.instagram.com/not_sameed52/" target="_blank"
                            class="text-primary-600">Sameed</a></p>
                </div>
            </div>
        </footer>
    </main>

    @include('partials.admin-scripts')
    
</body>

</html>
