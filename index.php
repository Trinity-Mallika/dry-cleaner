        <?php //include("action.php");
        $btn_name = "Login";
        $error = isset($_GET['error']) ? $_GET['error'] : 0;
        ?>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Dry Cleaner</title>
        </head>
        <!-- MINIMAL CSS (ONLY WHAT BOOTSTRAP CANNOT DO) -->
        <link rel="stylesheet" href="admin/assets/css/style.css">
        <link rel="stylesheet" href="admin/assets/css/main.css">
        <!-- Minimal Custom CSS -->
        <style>
            .brand-bg {
                background: #39cf9a;
                height: 260px;
            }

            .login-card {
                width: 580px;
                position: absolute;
                right: 0;
                top: -35px;
                height: 340px;
            }

            @media (max-width: 992px) {
                .brand-bg {
                    display: none;
                }

                .login-card {
                    position: static;
                    width: 100%;
                }
            }
        </style>

        <body class="min-vh-100 bg-white">

            <div class="container-fluid pt-4">
                <form action="checklogin.php" method="post">

                    <!-- LOGO -->
                    <div class="text-center mb-5">
                        <img src="admin/img/logo.png" alt="FABRICO" class="w-25">
                    </div>
                    <br>
                    <div class="row justify-content-center">
                        <div class="col-lg-9 position-relative">

                            <!-- LEFT GREEN PANEL -->
                            <div class="brand-bg d-flex align-items-center ps-5">
                                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <h5 class="ps-3"> Manage Your Laundry Business</h5>
                                        </div>
                                        <div class="carousel-item">
                                            <h5 class="ps-3"> Transparent Billing System </h5>
                                        </div>
                                        <div class="carousel-item">
                                            <h5 class="ps-3"> Automated Messages To Customer </h5>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- LOGIN CARD -->
                            <div class="card shadow border-0 login-card">
                                <?php if ($error == 1) { ?>
                                    <h6 class="bg-danger-subtle text-danger p-3 text-center position-absolute w-100">
                                        Invalid Login
                                    </h6>
                                <?php } ?>
                                <!-- <h6 class="bg-danger-subtle text-danger p-3 text-center position-absolute w-100">Invalid Login</h6> -->
                                <div class="card-body">
                                    <div class="mt-5">
                                        <div class="mb-4">
                                            <input type="text"
                                                class="form-control form-control-lg border-dark-subtle rounded-0"
                                                placeholder="Username" name="username" id="username">
                                        </div>
                                        <div class="mb-4">
                                            <input type="password"
                                                class="form-control form-control-lg border-dark-subtle rounded-0"
                                                placeholder="Password" name="password" id="password">
                                        </div>
                                        <input type="submit" onclick="return checkinputmaster('username,password')" name="login" class="btn btn-success w-100 py-2 fw-semibold" value="<?php echo $btn_name; ?>">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </body>
        <script src="admin/assets/js/jquery-3.6.0.min.js"></script>
        <script src="admin/assets/js/script.js"></script>
        <script src="admin/assets/js/commonfun.js"></script>

        </html>