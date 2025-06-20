<!DOCTYPE html>
<html lang="zxx">
<head>
	@include('frontend.layouts.head')
</head>
<body class="js">

	<!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->
	<!-- Header -->
	@include('frontend.layouts.header')
	<!--/ End Header -->
    @include('frontend.layouts.notification')
	@yield('main-content')

	@include('frontend.layouts.footer')

</body>
</html>