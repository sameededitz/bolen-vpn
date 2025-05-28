<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') | {{ config('app.name') }}</title>

<!-- Favicon for most browsers -->
<link rel="icon" href="{{ asset('admin_assets/icon/favicon.ico') }}" sizes="any" type="image/x-icon">

<!-- PNG icons for different sizes -->
<link rel="icon" href="{{ asset('admin_assets/icon/favicon-16x16.png') }}" sizes="16x16" type="image/png">
<link rel="icon" href="{{ asset('admin_assets/icon/favicon-32x32.png') }}" sizes="32x32" type="image/png">
<link rel="icon" href="{{ asset('admin_assets/icon/android-chrome-192x192.png') }}" sizes="192x192"
    type="image/png">
<link rel="icon" href="{{ asset('admin_assets/icon/android-chrome-512x512.png') }}" sizes="512x512"
    type="image/png">
<!-- Apple Touch Icon for iOS -->
<link rel="apple-touch-icon" href="{{ asset('admin_assets/icon/apple-touch-icon.png') }}">
<!-- Web Manifest for Android and Progressive Web Apps -->
<link rel="manifest" href="{{ asset('admin_assets/icon/site.webmanifest') }}">

<!-- remix icon font css -->
<link rel="stylesheet" href="{{ asset('admin_assets/css/remixicon.css') }}">
<!-- BootStrap css -->
<link rel="stylesheet" href="{{ asset('admin_assets/css/lib/bootstrap.min.css') }}">
<!-- Data Table css -->
{{-- <link rel="stylesheet" href="{{ asset('admin_assets/css/lib/dataTables.min.css') }}"> --}}
<link href="https://cdn.datatables.net/v/dt/dt-2.1.3/datatables.min.css" rel="stylesheet">
<!-- main css -->
<link rel="stylesheet" href="{{ asset('admin_assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('admin_assets/css/custom.css') }}">
