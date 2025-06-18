@extends("frontend.layouts.master")

@section("title", "About Us")

@section("main-content")
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">About Us</h1>
</div>

<section class="container-fluid py-5 ">
    <div class="container">
        <div class="row">
            <!-- Content -->
            <div class="col-lg-6 col-12">
                <div class="about-content">
                    <h3>Welcome To <span class="text-primary">CHARIS AGROBASE</span></h3>
                    <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde omnis iste natus error sit voluptatem Excepteu sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspi deserunt mollit anim id est laborum. sed ut perspi.</p>
                </div>
            </div>

            <!-- Image -->
            <div class="col-lg-6 col-12">
                <div class="about-img overlay">
                    <img src="{{ asset('asset/img/CharisAgroBase-Logo.png') }}" alt="About Image" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
