 <!-- Navbar start -->
 <div class="container-fluid fixed-top">
     <div class="container topbar bg-primary d-none d-lg-block">
         <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-2 py-1 text-white">
             <div class="top-info text-center text-md-start mb-2 mb-md-0">
                 <small class="me-3 d-block d-md-inline">
                     <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
                     <a href="#" class="text-white">Nigeria</a>
                 </small>
                 <small class="me-3 d-block d-md-inline">
                     <i class="fas fa-envelope me-2 text-secondary"></i>
                     <a href="#" class="text-white">Email@Example.com</a>
                 </small>
             </div>
             <div class="top-link text-center text-md-end">
                 <a href="#" class="text-white d-block d-md-inline"><small class="mx-4"><i
                             class="fas fa-shipping-fast me-1 text-secondary"></i>Track Order</small></a>
                 @auth
                     @if (Auth::user()->role == "admin")
                         <a href="{{ route("admin") }}" class="text-white d-block d-md-inline"><small class="mx-2"><i
                                     class="fas fa-user me-1 text-secondary"></i> Dashboard</small>/</a>
                     @else
                         <a href="" class="text-white d-block d-md-inline"><small class="mx-2"><i
                                     class="fas fa-user me-1 text-secondary"></i> Dashboard</small>/</a>
                     @endif
                     <a href="{{ route("user.logout") }}" class="text-white d-block d-md-inline"><small class="mx-2"><i
                                 class="fas fa-power-off me-1 text-secondary"></i>Logout</small></a>
                 @else
                     <a href="{{ route("login.form") }}" class="text-white d-block d-md-inline"><small class="mx-1"><i
                                 class="fas fa-power-off me-1 text-secondary"></i>Login</small>/</a>
                     <a href="{{ route("register.form") }}" class="text-white d-block d-md-inline"><small class="mx-1"><i
                                 class="fas fa-registered me-1 text-secondary"></i>Register</small></a>
                 @endauth
             </div>
         </div>

     </div>
     <div class="container px-0">
         <nav class="navbar navbar-light bg-white navbar-expand-xl">
             <a href="index.html" class="navbar-brand">
                 <img src={{ asset("asset/img/CharisAgroBase-Logo.png") }} alt="charisenterpriselogo" class="img-fluid"
                     style="max-height: 80px;">
             </a>

             <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                 data-bs-target="#navbarCollapse">
                 <span class="fa fa-bars text-primary"></span>
             </button>
             <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                 <div class="navbar-nav mx-auto">
                     <a href="index.html" class="nav-item nav-link active">Home</a>
                     <a href="shop.html" class="nav-item nav-link">About Us</a>
                     <a href="shop-detail.html" class="nav-item nav-link">Products</a>
                     <div class="nav-item dropdown">
                         <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Category</a>
                         <div class="dropdown-menu m-0 bg-secondary rounded-0">
                             @foreach ($categories as $category)
                                 <a href="{{ route("category.products", $category->slug) }}" class="dropdown-item">
                                     {{ $category->title }}
                                 </a>
                             @endforeach
                         </div>
                     </div>
                     <a href="contact.html" class="nav-item nav-link">Blog</a>
                     <a href="contact.html" class="nav-item nav-link">Contact Us</a>
                 </div>
                 <div class="d-flex align-items-center m-3 me-0">
                     <a href="#" class="position-relative me-4">
                         <i class="far fa-heart fa-lg"></i>
                         <span
                             class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark"
                             style="top: -5px; left: 12px; height: 18px; min-width: 18px; font-size: 12px;">3</span>
                     </a>

                     <a href="#" class="position-relative me-4">
                         <i class="fa fa-shopping-bag fa-lg"></i>
                         <span
                             class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark"
                             style="top: -5px; left: 12px; height: 18px; min-width: 18px; font-size: 12px;">3</span>
                     </a>

                     <button class="btn border border-secondary btn-sm rounded-circle bg-white" data-bs-toggle="modal"
                         data-bs-target="#searchModal" style="width: 38px; height: 38px;">
                         <i class="fas fa-search text-primary"></i>
                     </button>
                 </div>

             </div>
         </nav>
     </div>
 </div>
 <!-- Navbar End -->


 <!-- Modal Search Start -->
 <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-fullscreen">
         <div class="modal-content rounded-0">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body d-flex align-items-center">
                 <div class="input-group w-75 mx-auto d-flex">
                     <input type="search" class="form-control p-3" placeholder="keywords"
                         aria-describedby="search-icon-1">
                     <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Modal Search End -->
