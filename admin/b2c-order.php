<?php include("../adminsession.php");
$pagename = "b2c-order.php";
$title = "Walk In Order";
$module = "Walk In Order";
$submodule = "Walk In Order";
$btn_name = "Save";
$tblname = "orders";
$tblpkey = "order_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

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
    table,
    tr,
    td {
        padding: 4px 30px !important;
    }
</style>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-3">
            <span class="text-black h4">Order</span>
            <span class="ms-2 dropdown">
                <i class="bi bi-clipboard-plus-fill text-body-tertiary" data-bs-toggle="dropdown"
                    aria-expanded="false"></i>
                <!-- DROPDOWN -->
                <div class="dropdown-menu p-2 shadow" style="width:260px; overflow:scroll; height:400px;">
                    <!-- item0 -->
                    <label class="d-flex justify-content-between align-items-center">
                        <span>Action</span>
                        <input class="form-check-input" type="checkbox" checked>
                    </label>
                    <!-- ITEM1-->
                    <?php for ($i = 0; $i < 8; $i++) {
                    ?>
                        <h6 class="mt-4 border-bottom pb-2">Order</h6>
                        <label class="d-flex justify-content-between align-items-center mt-2">
                            <span>Order No.</span>
                            <input class="form-check-input" type="checkbox" checked>
                        </label>
                        <label class="d-flex justify-content-between align-items-center mt-2">
                            <span>Order Status</span>
                            <input class="form-check-input" type="checkbox">
                        </label>
                        <label class="d-flex justify-content-between align-items-center mt-2">
                            <span>Franchise</span>
                            <input class="form-check-input" type="checkbox">
                        </label>
                        <label class="d-flex justify-content-between align-items-center mt-2">
                            <span>Vendor Identifier</span>
                            <input class="form-check-input" type="checkbox">
                        </label>
                    <?php } ?>
                </div>
            </span>
            <span class="ms-2"><i class="bi bi-file-earmark-check-fill text-body-tertiary"></i></span>
            <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Cancelled <span class="text-danger">[10]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    New Order <span class="text-danger">[0]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Unassigned Pickup <span class="text-danger">[1]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Assigned Pickup <span class="text-danger">[2]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Out For Pickup <span class="text-danger">[8]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Pickup Done By Rider <span class="text-danger">[8]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Untagged <span class="text-danger">[3]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Tagged <span class="text-danger">[117]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Processing At Store <span class="text-danger">[1]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Processing At Vendor <span class="text-danger">[0]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Ready Order <span class="text-danger">[188]</span>
                </a>

                <a class="badge p-1 fs-10 bg-secondary-subtle cursor text-black text-decoration-none rounded-1">
                    Delivered <span class="text-danger">[8220]</span>
                </a>

            </div>
            <div class="d-flex flex-wrap mt-4">
                <span class="ms-2 dropdown">
                    <i class="bi bi-funnel-fill" data-bs-toggle="dropdown"
                        aria-expanded="false"></i>
                    <!-- DROPDOWN -->
                    <div class="dropdown-menu p-2 shadow" style="width:260px; overflow:scroll; height:400px;">
                        <!-- item0 -->
                        <label class="d-flex justify-content-between align-items-center">
                            <span>Action</span>
                            <input class="form-check-input" type="checkbox" checked>
                        </label>
                        <!-- ITEM1-->
                        <?php for ($i = 0; $i < 8; $i++) {
                        ?>
                            <h6 class="mt-4 border-bottom pb-2">Order</h6>
                            <label class="d-flex justify-content-between align-items-center mt-2">
                                <span>Order No.</span>
                                <input class="form-check-input" type="checkbox" checked>
                            </label>
                            <label class="d-flex justify-content-between align-items-center mt-2">
                                <span>Order Status</span>
                                <input class="form-check-input" type="checkbox">
                            </label>
                            <label class="d-flex justify-content-between align-items-center mt-2">
                                <span>Franchise</span>
                                <input class="form-check-input" type="checkbox">
                            </label>
                            <label class="d-flex justify-content-between align-items-center mt-2">
                                <span>Vendor Identifier</span>
                                <input class="form-check-input" type="checkbox">
                            </label>
                        <?php } ?>
                    </div>
                </span>
                <?php for ($i = 0; $i < 5; $i++) {
                ?>
                    <span class="ms-2"><a href="" class="text-decoration-none badge border text-black">Order No.<i class="bi bi-x-circle-fill ps-2"></i></a> </span>
                <?php } ?>
            </div>

            <div class="table-responsive mt-5">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <td class="fw-semibold fs-14">Actions</td>
                            <td class="fw-semibold fs-14">Order No.</td>
                            <td class="fw-semibold fs-14">Order Status</td>
                            <td class="fw-semibold fs-14">Pickup Date</td>
                            <td class="fw-semibold fs-14">Pickup Timeslot</td>
                            <td class="fw-semibold fs-14">Delivery Date</td>
                            <td class="fw-semibold fs-14">Delivery Timeslot</td>
                            <td class="fw-semibold fs-14">D Rider Name</td>
                            <td class="fw-semibold fs-14">D Rider Mobile</td>
                            <td class="fw-semibold fs-14">Customer Name</td>
                            <td class="fw-semibold fs-14">Customer Mobile</td>
                            <td class="fw-semibold fs-14">Customer Address</td>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        <?php $res = $obj->executequery("Select * from orders order by order_id desc");
                        foreach ($res as $key) { ?>
                            <tr>
                                <td class="nowrap">
                                    <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none">PROCESS AT STORE</a>
                                    <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none">PROCESS AT VENDOR</a>
                                    <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none">MARK DELIVERED</a>
                                    <!-- Dropdown Start -->
                                    <span class="dropdown">
                                        <a class="badge rounded-1 text-black cursor border fs-10 p-1 text-decoration-none"><i class="bi bi-three-dots-vertical"></i> More</a>
                                        <select name="" id="">select Option
                                            <option value="">qwe</option>
                                            <option value="">qwe</option>
                                            <option value="">qwe</option>
                                            <option value="">qwe</option>
                                            <option value="">qwe</option>
                                        </select>
                                    </span>
                                    <!-- Dropdown End -->
                                </td>
                                <td class="fs-12"><?= $key['order_no'] ?></td>
                                <td class="fs-12"><span class="badge bg-warning text-dark">Tagged</span></td>
                                <td class="fs-12">2026-01-12</td>
                                <td class="fs-12">Walkin</td>
                                <td class="fs-12"><?= $obj->dateformatindia($key['delivery_date']) ?></td>
                                <td class="fs-12"><?= $key['delivery_slot'] ?></td>
                                <td class="fs-12">Shekhar Mishra</td>
                                <td class="fs-12">8085144513</td>
                                <td class="fs-12"><?= $key['customer_name'] ?></td>
                                <td class="fs-12"><?= $key['mobile'] ?></td>
                                <td class="fs-12">Walkin</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
<?php include('inc/js-link.php') ?>

</html>