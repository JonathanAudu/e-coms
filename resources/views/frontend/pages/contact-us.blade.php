@extends("frontend.layouts.master")

@section("title", "Contact Us")

@section("main-content")
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Contact Us</h1>
    </div>

    <!-- Contact Start -->
    <div class="container-fluid contact py-5">
        <div class="container">
            <div class="p-5 bg-light rounded">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="text-center mx-auto" style="max-width: 700px;">
                            <h1 class="text-primary">Get in touch</h1>
                            <p class="mb-4">
                                We’re here to help and answer any questions you might have. Whether you're looking for more information about our products, services, or just want to say hello — we’d love to hear from you. Reach out to us anytime, and our team will get back to you as soon as possible.
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row g-4">
                            <!-- Address -->
                            <div class="col-md-4">
                                <div class="d-flex p-4 rounded bg-white h-100">
                                    <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                                    <div>
                                        <h4>Address</h4>
                                        <p class="mb-2">123 Street Nigeria</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <div class="d-flex p-4 rounded bg-white h-100">
                                    <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                                    <div>
                                        <h4>Mail Us</h4>
                                        <p class="mb-2">info@example.com</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-4">
                                <div class="d-flex p-4 rounded bg-white h-100">
                                    <i class="fa fa-phone-alt fa-2x text-primary me-4"></i>
                                    <div>
                                        <h4>Telephone</h4>
                                        <p class="mb-2">(+012) 3456 7890</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center mt-4">
                                    <a href="" target="_blank" class="btn btn-outline-primary rounded-circle me-4">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                    <a href="" target="_blank" class="btn btn-outline-primary rounded-circle me-4">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="" target="_blank" class="btn btn-outline-primary rounded-circle">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Contact End -->
@endsection
