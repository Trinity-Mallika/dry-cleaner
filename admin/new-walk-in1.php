<?php
$pagename = "new-walk-in.php";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
</head>
<?php include('inc/css-link.php') ?>
<!-- Bootstrap Icons (PUT THIS HERE) -->

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-5 mb-5">
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card card-body rounded-1">
                        <div class="d-flex justify-content-between">
                            <div class="mb-2">
                                <label for="" class="fw-bold">Mobile No.</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label for="" class="fw-bold">Name</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div style="overflow:scroll;height: 280px;">
                            <?php for ($i = 0; $i < 10; $i++) {
                            ?>
                                <div class="bg-body-tertiary mb-2 p-2 rounded">
                                    <!-- TOP ROW -->
                                    <div class="d-flex justify-content-between align-items-start">
                                        <!-- LEFT -->
                                        <div>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-pencil text-success" data-bs-toggle="modal" data-bs-target="#add-product"></i>
                                                <i class="bi bi-x text-danger"></i>
                                                <strong class="fs-14">Jacket/Blazer [Leather]</strong>
                                            </div>

                                            <div class="small text-black mt-1">
                                                Services: <span class="fs-13">DC, SP</span>
                                            </div>

                                            <div class="small text-black">
                                                Comments: <span class="fs-13">CS, FS</span>
                                            </div>
                                        </div>

                                        <!-- RIGHT -->
                                        <div class="text-end">
                                            <strong>1 X 628 = 628.00</strong>

                                            <div class="small text-black mt-1">
                                                Req: <span class="fs-13">FLD, HGR, HGR[PC]</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ACTION BUTTONS -->
                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                        <a class="badge p-2 border text-decoration-none text-secondary">
                                            <i class="bi bi-check2"></i> Washing Area
                                        </a>

                                        <a class="badge p-2 border text-decoration-none text-secondary">
                                            <i class="bi bi-check2"></i> Pressing Area
                                        </a>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>
                        <hr>
                        <a href="" class="btn btn-sm bg-body-secondary" data-bs-toggle="modal" data-bs-target="#coupon"><i class="bi bi-ticket-perforated-fill"></i>&nbsp;Add Coupon</a>

                        <h6 class="text-secondary fw-bold mt-3">Gross Total <span class="float-end">0</span></h6>
                        <h6 class="text-secondary fw-bold">Discount Amount: <span class="float-end">0</span></h6>
                        <h6 class="text-secondary fw-bold">Express Amount: <span class="float-end">0</span></h6>
                        <h6 class="text-secondary fw-bold">Total Count: <span class="float-end">0pc</span></h6>
                        <h6 class="fw-bold mt-3">Total Amount: <span class="float-end">0</span></h6>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div class="mb-2">
                                <label for="" class="fw-bold">Delivery Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label for="" class="fw-bold">Delivery TimeSlot</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <a href="#0" class="btn btn-success btn-sm mt-3 fw-semibold">
                            Create Order
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card card-body rounded-1">
                        <?php
                        // Example: jis letter ka data available nahi hai
                        $disabledLetters = [];
                        $activeLetter = 'A'; // current selected
                        ?>
                        <div class="d-flex flex-wrap gap-2">

                            <?php foreach (range('A', 'Z') as $char): ?>

                                <?php
                                $isDisabled = in_array($char, $disabledLetters);
                                $isActive   = ($char === $activeLetter);
                                ?>

                                <a href="<?= $isDisabled ? 'javascript:void(0)' : '?alpha=' . $char ?>"
                                    class="d-flex align-items-center justify-content-center fw-semibold text-decoration-none rounded <?= $isActive ? 'bg-success text-white' : ($isDisabled ? 'text-secondary' : 'text-success') ?>"
                                    style="width:28px;height:28px;font-size:13px;  <?= $isDisabled ? 'pointer-events:none;opacity:.5;' : '' ?>">
                                    <?= $char ?>
                                </a>

                            <?php endforeach; ?>
                        </div>
                        <div class="input-group mb-3 mt-4">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search products">
                        </div>

                        <div style="overflow:scroll;height: 620px;" class="bg-body-secondary p-1">
                            <div class="card mb-3 shadow-sm rounded-1 border-0">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-lg-9 col-12">
                                            <h6 class="mb-0 text-black">Jacket/Blazer</h6>
                                            <div class="mt-2">
                                                <?php for ($i = 0; $i < 15; $i++) {
                                                ?>
                                                    <a href="" class="badge p-1 bg-dark-subtle text-decoration-none text-black fs-11 rounded-1">Kids</a>
                                                <?php } ?>
                                                <hr class="mt-2 mb-3">
                                            </div>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <?php for ($i = 0; $i < 4; $i++) { ?>
                                                    <div class="service-item">
                                                        <h6 class="fs-13 mb-0">
                                                            Dry Cleaner<br>
                                                            <small class="fs-12 fw-bold text-black">213/PC</small>
                                                        </h6>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                        </div>
                                        <div class="col-lg-3 col-3 text-center">
                                            <img src="img/logo.png" width="90" class="mb-2" alt="">
                                            <a data-bs-toggle="modal" data-bs-target="#add-product" class="btn btn-success btn-sm w-100 p-0">Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-3 shadow-sm rounded-1 border-0">
                                <div class="card-body p-2">
                                    <div class="row">
                                        <div class="col-lg-9 col-12">
                                            <h6 class="mb-0 text-black">Laundry By Weight</h6>

                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <?php for ($i = 0; $i < 4; $i++) { ?>
                                                    <div class="service-item">
                                                        <h6 class="fs-13 mb-0">
                                                            Dry Cleaner<br>
                                                            <small class="fs-12 fw-bold text-black">213/PC</small>
                                                        </h6>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-3 text-center">
                                            <img src="img/logo.png" width="90" class="mb-2" alt="">
                                            <a data-bs-toggle="modal" data-bs-target="#garment" class="btn btn-success btn-sm w-100 p-0">Add</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- modal coupon Start -->
    <div class="modal fade" id="coupon" tabindex="-1" aria-labelledby="couponLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute w-25">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="couponLabel">Offers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="height: 300px;">
                    <div class="card p-2 border">
                        <h6 class="text-secondary">FABRICO30 <span class="cursor text-success float-end">Apply</span></h6>
                        <h6 class="text-secondary">Flat 30% Off </h6>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- modal coupon End -->


    <!-- Add garment Start -->
    <div class="modal fade" id="garment" tabindex="-1" aria-labelledby="garmentLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute" style="width: 30% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="garmentLabel">Offers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="">
                        <!-- Jacket Type -->
                        <div class="mb-3">
                            <label class="fw-semibold mb-2 d-block">Select Jacket/Blazer Type</label>
                            <div>
                                <a class="badge p-2 cursor bg-success text-decoration-none active mb-1 rounded-1">Kid</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Party</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Casual</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Cotton</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Formal</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Leather</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Sweat Jacket</a>
                            </div>
                        </div>
                        <a href="" data-bs-toggle="modal" data-bs-target="#garment-details" class="btn bg-body-secondary w-100">Add Garment</a>
                    </div>
                </div>
                <div class="text-center mb-3 mt-2">
                    <div class="d-flex">
                        <div class="count-box ms-2 mb-2">
                            <button class="count-btn" id="minusBtn">−</button>
                            <span class="fw-semibold" id="qty">0</span>
                            <span>Pc</span>
                            <button class="count-btn" id="plusBtn">+</button>
                        </div>
                        <div class="count-box ms-2 mb-2">
                            <button class="count-btn" id="minusBtn">−</button>
                            <span class="fw-semibold" id="qty">0</span>
                            <span>Pc</span>
                            <button class="count-btn" id="plusBtn">+</button>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                        <label class="form-check-label" for="inlineRadio1">Washing Area</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <label class="form-check-label" for="inlineRadio2">Pressing Area</label>
                    </div><br>
                    <a href="" class="btn btn-success w-75">ADD</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Add garment End -->

    <!-- Add Garment Details Start -->
    <div class="modal fade" id="garment-details" tabindex="-1" aria-labelledby="garment-detailsLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container-fluid bg-white">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <button class="btn btn-light text-danger fs-4" data-bs-dismiss="modal">×</button>
                            <input type="text" class="form-control w-25" placeholder="Search garments">
                            <!-- A-Z Filter -->
                            <div class="d-flex flex-wrap gap-3">
                                <?php foreach (range('A', 'Z') as $char) { ?>
                                    <span class="text-success fw-semibold small cursor-pointer"><?= $char ?></span>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Category Tabs -->
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <span class="badge rounded-pill bg-light text-dark px-3">Men</span>
                            <span class="badge rounded-pill bg-light text-dark px-3">Women</span>
                            <span class="badge rounded-pill bg-light text-dark px-3">Woolen</span>
                            <span class="badge rounded-pill bg-light text-dark px-3">Kids</span>
                            <span class="badge rounded-pill bg-light text-dark px-3">Accessories</span>
                            <span class="badge rounded-pill bg-light text-dark px-3">Home & Living</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid mt-4">
                        <div class="row g-3">

                            <?php
                            $items = [
                                "Jacket/Blazer",
                                "Shirt",
                                "Blanket",
                                "Trouser/Jeans",
                                "T-Shirt",
                                "Sherwani",
                                "Sweater",
                                "Kurta",
                                "Saree",
                                "Lehenga",
                                "Gown/Anarkali",
                                "Skirt",
                                "Dress",
                                "Dupatta",
                                "Purse",
                                "Tie"
                            ];

                            foreach ($items as $item) {
                            ?>
                                <div class="col-xl-3 col-lg-4 col-md-6">
                                    <div class="card h-100 shadow-sm border">

                                        <div class="card-body d-flex justify-content-between">

                                            <div>
                                                <h6 class="fw-bold"><?= $item ?></h6>

                                                <div class="d-flex flex-wrap gap-1 mt-2">
                                                    <span class="badge bg-success-subtle text-success">Kid</span>
                                                    <span class="badge bg-secondary-subtle text-dark">Cotton</span>
                                                    <span class="badge bg-secondary-subtle text-dark">Party</span>
                                                    <span class="badge bg-secondary-subtle text-dark">Formal</span>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <img src="https://cdn-icons-png.flaticon.com/512/892/892458.png"
                                                    width="40" class="mb-2">
                                                <br>
                                                <button class="btn btn-success btn-sm px-3">Add</button>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Add Garment Details End -->

    <!-- modal add-product Start -->
    <div class="modal fade" id="add-product" tabindex="-1" aria-labelledby="add-productLabel" aria-hidden="true">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute" style="width: 30% !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="add-productLabel">Offers</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div style="">
                        <!-- Jacket Type -->
                        <div class="mb-3">
                            <label class="fw-semibold mb-2 d-block">Select Jacket/Blazer Type</label>
                            <div>
                                <a class="badge p-2 cursor bg-success text-decoration-none active mb-1 rounded-1">Kid</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Party</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Casual</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Cotton</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Formal</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Leather</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1">Sweat Jacket</a>
                            </div>
                        </div>

                        <!-- Services -->
                        <div class="mb-3">
                            <label class="fw-semibold mb-2 d-block">
                                Select one or more services <span class="text-danger">*</span>
                            </label>
                            <div>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1"> Dry Clean span [₹ 150 / pc]</a>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1"> Dry Clean span [₹ 150 / pc]</a>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label class="fw-semibold mb-2 d-block">Requirements</label>
                            <div>
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1"> Fold [₹ 0.00 / pc]</a>
                            </div>
                        </div>
                        <!-- Requirements -->
                        <div class="mb-3">
                            <label class="fw-semibold d-block">Comments</label>
                            <small class="fw-11 text-secondary">Customer will be notified for these comments and it is advised to wait for atleast 30 min to start processing this order after tagging</small>
                            <div class="mt-2">
                                <a class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none mb-1 rounded-1"> Fold [₹ 0.00 / pc]</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-3 mt-2">
                    <div class="count-box mb-2">
                        <button class="count-btn" id="minusBtn">−</button>
                        <span class="fw-semibold" id="qty">0</span>
                        <span>Pc</span>
                        <button class="count-btn" id="plusBtn">+</button>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                        <label class="form-check-label" for="inlineRadio1">Washing Area</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <label class="form-check-label" for="inlineRadio2">Pressing Area</label>
                    </div><br>
                    <a href="" class="btn btn-success w-75">ADD</a>
                </div>
            </div>
        </div>
    </div>
    <!-- modal add-product End -->

</body>
<?php include('inc/js-link.php') ?>

</html>