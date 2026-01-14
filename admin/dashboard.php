<?php
$pagename = "dashboard.php";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
</head>
<?php include('inc/css-link.php') ?>
<!-- Bootstrap Icons (PUT THIS HERE) -->
<style>

</style>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container pt-3">
            <div class="table-responsive" style="height: 500px;">
                <table class="table table-bordered">
                    <thead class="position-sticky top-0">
                        <tr>
                            <th class="nowrap text-center blue headcol">Delivery Date</th>
                            <th class="nowrap text-center blue"> Mon 5 Jan</th>
                            <th class="nowrap text-center blue"> Sun 4 Jan</th>
                            <th class="nowrap text-center blue"> Sat 3 Jan</th>
                            <th class="nowrap text-center blue"> Fri 2 Jan</th>
                            <th class="nowrap text-center blue"> Thu 1 Jan</th>
                            <th class="nowrap text-center blue"> Wed 31 Dec</th>
                            <th class="nowrap text-center blue"> Tue 30 Dec</th>
                            <th class="nowrap text-center blue"> Mon 29 Dec</th>
                            <th class="nowrap text-center blue"> Sun 28 Dec</th>
                            <th class="nowrap text-center blue"> Sat 27 Dec</th>
                            <th class="nowrap text-center blue"> Fri 26 Dec</th>
                        </tr>
                        <tr>
                            <th class="nowrap text-center purple headcol">Total [124]</th>
                            <th class="nowrap text-center purple"></th>
                            <th class="nowrap text-center purple">1 - 4 pc</th>
                            <th class="nowrap text-center purple">19 - 135 pc</th>
                            <th class="nowrap text-center purple">25 - 128 pc</th>
                            <th class="nowrap text-center purple">27 - 178 pc</th>
                            <th class="nowrap text-center purple">20 - 131 pc</th>
                            <th class="nowrap text-center purple">9 - 66 pc</th>
                            <th class="nowrap text-center purple">9 - 55 pc</th>
                            <th class="nowrap text-center purple">3 - 5 pc</th>
                            <th class="nowrap text-center purple">11 - 63 pc</th>
                            <th class="nowrap text-center purple"></th>
                        </tr>
                        <tr>
                            <th class="nowrap text-center black headcol">Processing [92]</th>
                            <th class="nowrap text-center black"></th>
                            <th class="nowrap text-center black">1 - 4 pc</th>
                            <th class="nowrap text-center black">19 - 135 pc</th>
                            <th class="nowrap text-center black">25 - 128 pc</th>
                            <th class="nowrap text-center black">27 - 178 pc</th>
                            <th class="nowrap text-center black">17 - 124 pc</th>
                            <th class="nowrap text-center black">2 - 23 pc</th>
                            <th class="nowrap text-center black">1 - 1 pc</th>
                            <th class="nowrap text-center black">0 - 0 pc</th>
                            <th class="nowrap text-center black">0 - 0 pc</th>
                            <th class="nowrap text-center black"></th>
                        </tr>
                        <tr>
                            <th class="nowrap text-center green headcol">Ready [31]</th>
                            <th class="nowrap text-center green"></th>
                            <th class="nowrap text-center green">0 - 0 pc</th>
                            <th class="nowrap text-center green">0 - 0 pc</th>
                            <th class="nowrap text-center green">0 - 0 pc</th>
                            <th class="nowrap text-center green">0 - 0 pc</th>
                            <th class="nowrap text-center green">3 - 7 pc</th>
                            <th class="nowrap text-center green">7 - 43 pc</th>
                            <th class="nowrap text-center green">8 - 54 pc</th>
                            <th class="nowrap text-center green">3 - 5 pc</th>
                            <th class="nowrap text-center green">10 - 58 pc</th>
                            <th class="nowrap text-center green"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($i = 0; $i < 20; $i++) {
                        ?>
                            <tr>
                                <td class="nowrap text-center fs-14"></td>
                                <td class="nowrap text-center fs-14"></td>
                                <td class="nowrap text-center fs-14">1. 1690279 - 4 pc - ₹523 - 06:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;ALI</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                                <td class="nowrap text-center fs-14">1. 1688569 - 7 pc - ₹714 - 02:00 PM
                                    <span class="bg-body-secondary p-1 pe-2 ps-2 pointer-event cursor" data-bs-toggle="modal" data-bs-target="#customer-detail"><i class="bi bi-telephone-fill"></i>&nbsp;Naved</span>&nbsp;<i class="bi bi-check2 fs-4 text-success border-bottom cursor" data-bs-toggle="modal" data-bs-target="#check-detail"></i>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card mt-5 mb-4 border-0">
                <div class="card-header bg-white">
                    <!-- HIDDEN DATE INPUT -->
                    <input type="date"
                        id="datePicker"
                        class="position-absolute opacity-0"
                        style="pointer-events:none; width:0; height:0;">

                    <!-- BUTTON -->
                    <a class="btn btn-success rounded-1"
                        onclick="openDatePicker()">
                        <i class="bi bi-calendar4-event"></i>
                        <span id="dateLabel">WED 10 DEC</span>
                    </a>
                    <a class="btn btn-outline rounded-1 border" data-bs-toggle="modal" data-bs-target="#store-selection"> <span>Store [1]</span></a>
                    <span class="float-end pt-2 fs-14">Refreshed At 05:07:46 PM</span>
                </div>
            </div>
            <!-- SUMMARY BAR -->
            <div class="d-flex flex-wrap gap-2 mb-3 small">
                <span class="badge bg-body-secondary cursor text-dark">Royalty ₹1,013</span>
                <span class="badge bg-body-secondary cursor text-dark">Gross Sale ₹14,465</span>
                <span class="badge bg-body-secondary cursor text-dark">Discount ₹3,717</span>
                <span class="badge bg-body-secondary cursor text-dark">Express ₹268</span>
                <span class="badge bg-body-secondary cursor text-dark">Total Sale ₹11,016</span>
                <span class="badge bg-body-secondary cursor text-dark">Total Payment ₹16,035</span>
                <span class="badge bg-body-secondary cursor text-dark">Rpz Payment ₹0</span>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-strip align-middle small report-table">

                    <thead class="table-light text-center">
                        <tr>
                            <th style="width:140px" class="text-secondary">T</th>
                            <th class="text-secondary">WO [20 - 144]</th>
                            <th class="text-secondary">P-P [0]</th>
                            <th class="text-secondary">C-P [0]</th>
                            <th class="text-secondary">P-NRHD [6 - 52]</th>
                            <th class="text-secondary">P-RHD [1 - 5]</th>
                            <th class="text-secondary">C-HD [0]</th>
                            <th class="text-secondary">P-NRWD [8 - 64]</th>
                            <th class="text-secondary">P-RWD [4 - 4]</th>
                            <th class="text-secondary">C-WD [3 - 65]</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php for ($i = 0; $i < 5; $i++) {

                        ?>
                            <tr>
                                <td>7 AM - 8 AM</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                            </tr>

                            <tr>
                                <td>10 AM - 11 AM</td>
                                <td class="text-center fw-semibold">2 <sub>34</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                            </tr>

                            <tr>
                                <td>3 PM - 4 PM</td>
                                <td class="text-center fw-semibold">6 <sub>49</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-danger fw-semibold">1 <sub>11</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center fw-semibold">1 <sub>28</sub></td>
                            </tr>

                            <tr>
                                <td>4 PM - 5 PM</td>
                                <td class="text-center fw-semibold">1 <sub>4</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-danger fw-semibold">5 <sub>41</sub></td>
                                <td class="text-center text-danger fw-semibold">1 <sub>5</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-danger fw-semibold">5 <sub>49</sub></td>
                                <td class="text-center fw-semibold">2 <sub>2</sub></td>
                                <td class="text-center fw-semibold">1 <sub>6</sub></td>
                            </tr>

                            <tr class="table-success">
                                <td>5 PM - 6 PM</td>
                                <td class="text-center fw-semibold">1 <sub>1</sub></td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center text-muted">0</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="text-center">
                        <tr>
                            <th style="width:140px" class="text-secondary">T</th>
                            <th class="text-secondary">WO [20 - 144]</th>
                            <th class="text-secondary">P-P [0]</th>
                            <th class="text-secondary">C-P [0]</th>
                            <th class="text-secondary">P-NRHD [6 - 52]</th>
                            <th class="text-secondary">P-RHD [1 - 5]</th>
                            <th class="text-secondary">C-HD [0]</th>
                            <th class="text-secondary">P-NRWD [8 - 64]</th>
                            <th class="text-secondary">P-RWD [4 - 4]</th>
                            <th class="text-secondary">C-WD [3 - 65]</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>



    <!-- Modal Section  Start-->

    <!--Customet Detail Modal Start-->
    <div class="modal fade custom-modal" id="customer-detail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="p-3 pb-0">
                    <h6 class="modal-title">Call ali</h6>
                    <small> *Recommended way to connect to the customer</small>
                </div>
                <div class="modal-body">
                    How this work?
                    <hr class="mt-1 mb-2">
                    <small>1. You will receive a call on 7714260085 after clicking on call button</small><br>
                    <small>2. Customer will get a call on 8349895599</small><br>
                    <small>Once you receive the call, wait for the call to connect to the customer</small>
                </div>
                <div class="modal-footer border-top-0">
                    <a data-bs-dismiss="modal" class="text-danger cursor">Close</a>
                    <a class="text-success cursor ms-2">Call Now</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Customet Detail Modal End -->

    <!-- check-detail Modal Start-->
    <div class="modal fade custom-modal" id="check-detail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="p-3 pb-0">
                    <h6 class="modal-title">Mark Ready</h6>
                    <small> Orders: 1690279</small>
                </div>
                <div class="modal-body">
                    <h6 class="fw-normal text-secondary">Select Storage Label</h6>

                    <div class="container py-3">
                        <div class="d-flex flex-wrap gap-2">

                            <!-- Repeat buttons -->
                            <?php for ($i = 1; $i < 47; $i++) {
                            ?>
                                <button class="bg-body-secondary btn btn-light number-pill rounded-circle"><?php echo $i; ?></button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-0">
                        <a class="btn btn-success cursor ms-2">Mark As Ready</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- check-detail Modal ENd-->


    <!-- store-selection Modal Start-->
    <div class="modal fade" id="store-selection" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header border-0">
                    <h6 class="modal-title">
                        Select Stores <span class="text-muted">[Selected: 1]</span>
                    </h6>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body pt-0">

                    <!-- STATE CHIP -->
                    <span class="badge rounded-pill bg-body-secondary text-dark mb-2">
                        Chhattisgarh
                    </span>

                    <!-- CATEGORY CHIPS -->
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge bg-light text-dark">MACRO-FOFO</span>
                        <span class="badge bg-light text-dark">MACRO-FOCO</span>
                        <span class="badge bg-light text-dark">MACRO-COCO</span>
                        <span class="badge bg-light text-dark">PRIME-FOFO</span>
                        <span class="badge bg-light text-dark">PRIME-FOCO</span>
                        <span class="badge bg-light text-dark">PRIME-COCO</span>
                        <span class="badge bg-light text-dark">ELITE-FOFO</span>
                        <span class="badge bg-light text-dark">ELITE-FOCO</span>
                        <span class="badge bg-light text-dark">ELITE-COCO</span>
                        <span class="badge bg-light text-dark">ELG-FOCO</span>
                    </div>

                    <!-- SEARCH -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-0 border-bottom rounded-0"
                            placeholder="Search">
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="mb-3">
                        <button class="btn btn-light btn-sm me-2">SELECT ALL</button>
                        <button class="btn btn-light btn-sm">DESELECT ALL</button>
                    </div>

                    <hr>

                    <!-- STORE LIST -->
                    <div class="form-check d-flex align-items-start gap-3 mb-3">
                        <input class="form-check-input mt-1" type="checkbox" checked>
                        <div>
                            <strong>FAB-CHHOTAPARA-492001</strong>
                            <div class="text-muted small">
                                Rajeev Gandhi Chowk, Chhotapara Rd, Opposite Surana Bhawan,
                                beside H.P. Gas Agency, Chhotapara, Janta Colony,
                                Raipur, Chhattisgarh 492001
                            </div>
                        </div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-top">
                    <button class="btn btn-link text-danger" data-bs-dismiss="modal">
                        CANCEL
                    </button>
                    <button class="btn text-success text-decoration-underline">
                        SUBMIT
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- store-selection Modal ENd-->

</body>
<?php include('inc/js-link.php') ?>
<script>
    function openDatePicker() {
        document.getElementById('datePicker').showPicker();
    }

    document.getElementById('datePicker').addEventListener('change', function() {
        const date = new Date(this.value);

        const options = {
            weekday: 'short',
            day: '2-digit',
            month: 'short'
        };
        document.getElementById('dateLabel').innerText =
            date.toLocaleDateString('en-GB', options).toUpperCase();
    });
</script>

</html>