<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Our Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Contact Us</a>
                    </li>
                </ul>
                <a href="#0" class="btn btn-primary">Call Now</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h1 class="title">Laundry made Easy</h1>
            <p class="sub-title mt-3">
                Effortless laundry, fresh clothes – delivered to your door.<br>
                Ready to simplify your life?
            </p>
            <a href="#" class="btn btn-primary mt-3">Schedule Pickup</a>
        </div>
    </section>

    <!-- slider section  -->
    <section class="slider">
        <!-- Swiper1 -->
        <div class="swiper mySwiper1">
            <div class="swiper-wrapper">
                <div class="swiper-slide"> <img src="img/1.jpg" alt=""> </div>
                <div class="swiper-slide"> <img src="img/2.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/3.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/4.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/5.jpg" alt=""></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <!-- Swiper2 -->
        <div class="swiper mySwiper2">
            <div class="swiper-wrapper">
                <div class="swiper-slide"> <img src="img/6.jpg" alt=""> </div>
                <div class="swiper-slide"> <img src="img/7.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/8.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/9.jpg" alt=""></div>
                <div class="swiper-slide"> <img src="img/10.jpg" alt=""></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Services -->
    <section class="space text-center">
        <div class="container">
            <h2 class="fw-bold title">Our Services</h2>
            <p class="sub-title mb-5">
                Explore our laundry services – washing, drying, ironing, and more.
            </p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card service-card">
                        <img src="img/9.jpg" class="card-img-top">
                        <div class="card-body">
                            <h3>Dry Cleaning</h3>
                            <p class="text-muted">Expert stain removal and fabric care.</p>
                            <!-- <a href="#" class="text-primary text-decoration-none">Learn more →</a> -->
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card service-card">
                        <img src="img/4.jpg" class="card-img-top">
                        <div class="card-body">
                            <h3>Wash & Fold</h3>
                            <p class="text-muted">Fresh, clean, and neatly folded clothes.</p>
                            <!-- <a href="#" class="text-primary text-decoration-none">Learn more →</a> -->
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card service-card">
                        <img src="img/5.jpg" class="card-img-top">
                        <div class="card-body">
                            <h3>Ironing</h3>
                            <p class="text-muted">Perfectly pressed clothes every time.</p>
                            <!-- <a href="#" class="text-primary text-decoration-none">Learn more →</a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="space why-us text-center">
        <div class="container">
            <h2 class="fw-bold mb-4 text-white">Why Choose Us?</h2>
            <p class="mb-5 sub-title text-white">Reliable, fast, and affordable laundry services.</p>

            <div class="row">
                <div class="col-md-4">
                    <div class="why-box mb-3"><img src="img/delivery.svg" alt=""></div>
                    <h5>Fast Delivery</h5>
                </div>
                <div class="col-md-4">
                    <div class="why-box mb-3"><img src="img/save-money.svg" alt=""></div>
                    <h5>Affordable Price</h5>
                </div>
                <div class="col-md-4">
                    <div class="why-box mb-3"><img src="img/eco-friendly.svg" alt=""></div>
                    <h5>Eco-Friendly</h5>
                </div>
            </div>
        </div>
    </section>

    <footer class="space">
        <section class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-3">
                    <h3>Laundry made Easy</h3>
                    <p>Effortless laundry, fresh clothes - delivered to your door.
                        Ready to simplify your life?</p>
                </div>
                <div class="col-md-7">
                    <h4>Map</h4>

                </div>
                <div class="col-md-5">
                    <h4>Contact Us</h4>
                </div>
            </div>
        </section>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>

    <script src="assets/script.js"></script>

</body>

</html>