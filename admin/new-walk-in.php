<?php include("../adminsession.php");
$pagename = "new-walk-in.php";
$title = "Walk In Order";
$module = "Walk In Order";
$submodule = "Walk In Order";
$btn_name = "Save";
$tblname = "orders";
$tblpkey = "order_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';

if (isset($_POST['submit'])) {

    $mobile_no            = $obj->test_input($_POST['mobile_no'] ?? '');
    $cust_name            = $obj->test_input($_POST['cust_name'] ?? '');
    $customer_id            = $obj->test_input($_POST['customer_id'] ?? 0);

    $is_home_delivery     = (int)($_POST['is_home_delivery'] ?? 0);
    $delivery_address_type = $obj->test_input($_POST['delivery_address_type'] ?? '');
    $delivery_address     = $obj->test_input($_POST['delivery_address'] ?? '');
    $address_id     = $obj->test_input($_POST['address_id'] ?? 0);

    $is_express_delivery  = (int)($_POST['is_express_delivery'] ?? 0);
    $express_percent      = (float)($_POST['express_percent'] ?? 0);
    $express_amount       = (float)($_POST['express_amount'] ?? 0);

    $delivery_date        = $obj->test_input($_POST['delivery_date'] ?? '');
    $delivery_slot        = $obj->test_input($_POST['delivery_slot'] ?? '');

    $coupon_master_id     = (int)($_POST['coupon_master_id'] ?? 0);
    $coupon_percent       = (float)($_POST['coupon_percent'] ?? 0);

    $gross_total          = (float)($_POST['gross_total'] ?? 0);
    $discount_amt         = (float)($_POST['discount_amt'] ?? 0);
    $final_total          = (float)($_POST['final_total'] ?? 0);

    $total_count          = (int)($_POST['total_count'] ?? 0);

    $order_no = $obj->getcode("orders", "order_no", "1=1");

    if ($keyvalue == 0) {
        if ($customer_id == 0) {
            $customer_id = $obj->insert_record_lastid("m_customer", [
                'customer_name' => $cust_name,
                "mobile" => $mobile_no,
                "ipaddress" => $ipaddress,
                "createdby" => $loginid,
                "createdate" => $createdate
            ]);
        }
        if ($is_home_delivery == 1 && $address_id == 0) {
            $address_id = $obj->insert_record_lastid("m_address", [
                "customer_id"   => $customer_id,
                "address"       => $delivery_address,
                "address_type"  => $delivery_address_type,
                "ipaddress"     => $ipaddress,
                "createdby"     => $loginid,
                "createdate"    => $createdate
            ]);
        }

        $lastid = $obj->insert_record_lastid("orders", [
            'order_no'          => $order_no,
            'customer_id'       => $customer_id,
            'address_id'       => $address_id,
            'is_home_delivery'       => $is_home_delivery,
            'is_express_delivery'       => $is_express_delivery,
            "delivery_date"     => $delivery_date,
            "delivery_slot"     => $delivery_slot,
            "gross_total"       => $gross_total,
            "discount_amt"      => $discount_amt,
            "final_total"       => $final_total,
            "coupon_master_id"  => $coupon_master_id,
            "coupon_percent"    => $coupon_percent,
            "express_percent"   => $express_percent,
            "express_amount"    => $express_amount,
            "total_count"       => $total_count,
            "ipaddress"         => $ipaddress,
            "createdby"         => $loginid,
            "createdate"        => $createdate,
            "createtime"        => date("H:i:s"),
            "sessionid"         => $sessionid
        ]);

        $obj->update_record("order_item", [
            'createdby' => $loginid,
            "order_id"  => 0
        ], ["order_id" => $lastid]);
        $obj->update_record("order_item_laundry", [
            'createdby' => $loginid,
            "order_id"  => 0
        ], ["order_id" => $lastid]);
        $obj->insert_record("order_reschedule", [
            "order_id"  => $lastid,
            "delivery_date"     => $delivery_date,
            "delivery_slot"     => $delivery_slot,
            "reason"            => "Requested By Customer",
            "ipaddress"         => $ipaddress,
            "createdby"         => $loginid,
            "createdate"        => $createdate,
        ]);

        $keyvalue = $lastid;
        $action = 1;
    } else {
        if ($customer_id == 0) {
            $customer_id = $obj->insert_record_lastid("m_customer", [
                'customer_name' => $cust_name,
                "mobile" => $mobile_no,
                "ipaddress" => $ipaddress,
                "createdby" => $loginid,
                "createdate" => $createdate
            ]);
        }
        if ($is_home_delivery == 1 && $address_id == 0) {
            $address_id = $obj->insert_record_lastid("m_address", [
                "customer_id"   => $customer_id,
                "address"       => $delivery_address,
                "address_type"  => $delivery_address_type,
                "ipaddress"     => $ipaddress,
                "createdby"     => $loginid,
                "createdate"    => $createdate
            ]);
        }

        $obj->update_record("orders", ["order_id" => $keyvalue], [
            'customer_id'       => $customer_id,
            'address_id'        => $address_id,
            'is_home_delivery'  => $is_home_delivery,
            'is_express_delivery' => $is_express_delivery,
            "delivery_date"     => $delivery_date,
            "delivery_slot"     => $delivery_slot,
            "gross_total"       => $gross_total,
            "discount_amt"      => $discount_amt,
            "final_total"       => $final_total,
            "coupon_master_id"  => $coupon_master_id,
            "coupon_percent"    => $coupon_percent,
            "express_percent"   => $express_percent,
            "express_amount"    => $express_amount,
            "total_count"       => $total_count,
            "ipaddress"         => $ipaddress,
            "createdby"         => $loginid,
            "lastupdated"       => $createdate,
            "sessionid"         => $sessionid
        ]);

        $action = 2;
    }
    echo "<script>location='$pagename?action=$action&orderno=$order_no&orderid=$keyvalue'</script>";
}

if (isset($_GET[$tblpkey])) {
    $where = array($tblpkey => $keyvalue);
    $sqledit = $obj->select_record($tblname, $where);
    $customer_id = $sqledit['customer_id'];
    $cust_name = $obj->getvalfield("m_customer", "customer_name", "customer_id='$customer_id'");
    $mobile = $obj->getvalfield("m_customer", "mobile", "customer_id='$customer_id'");
    $address_id = $sqledit['address_id'];
    $delivery_address_type = $obj->getvalfield("m_address", "address_type", "address_id='$address_id'");
    $delivery_address = $obj->getvalfield("m_address", "address", "address_id='$address_id'");
    $order_no = $sqledit['order_no'];
    $delivery_date = $sqledit['delivery_date'];
    $delivery_slot = $sqledit['delivery_slot'];
    $is_home_delivery = $sqledit['is_home_delivery'];
    $is_express_delivery = $sqledit['is_express_delivery'];
    $express_percent = $sqledit['express_percent'];
    $coupon_master_id = $sqledit['coupon_master_id'];
    $coupon_percent = $sqledit['coupon_percent'];
    $readonly = "readonly";
} else {
    $customer_id = $express_percent = $coupon_master_id = $coupon_percent = $is_home_delivery = $is_express_delivery = 0;
    $cust_name = $mobile = $delivery_address_type = $delivery_address = $delivery_slot = $delivery_date = "";
    $readonly = "";
}
$coupon_name = '';
if ($coupon_master_id > 0) {
    $coupon_name = $obj->getvalfield(
        "coupon_master",
        "coupon_name",
        "coupon_master_id='$coupon_master_id'"
    );
}

?>
<?php if (isset($_GET['action']) && $_GET['action'] == 1) { ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const orderNo = "<?= $_GET['orderno'] ?>";
            const orderId = "<?= $_GET['orderid'] ?>";

            // inject order number
            document.getElementById("successOrderNo").innerText = orderNo;

            // update print links
            document.getElementById("printReceiptBtn").href =
                "receipt_pdf.php?order_id=" + orderId;

            document.getElementById("printTagBtn").href =
                "tag_print.php?order_id=" + orderId;

            // show modal
            const modal = new bootstrap.Modal(
                document.getElementById('orderSuccessModal')
            );

            modal.show();


            modal.addEventListener('hidden.bs.modal', function() {
                location.href = "<?= $pagename ?>"; // clean reload (removes GET params)
            });

        });
    </script>
<?php } ?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
    <style>
        .count-box {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .count-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
            line-height: 5px;
        }

        .form-check-input:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }
    </style>
</head>
<?php include('inc/css-link.php') ?>

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
                        <form method="post">
                            <div class="d-flex justify-content-between">
                                <div class="mb-2">
                                    <label for="mobile_no" class="fw-bold">Mobile No.</label>
                                    <input
                                        type="tel"
                                        name="mobile_no"
                                        id="mobile_no"
                                        inputmode="numeric"
                                        maxlength="10"
                                        class="form-control"
                                        onchange="find_cust(this.value); setFinalBtn();" value="<?= $mobile ?>">
                                    <input type="hidden" name="customer_id" id="customer_id" value="<?= $customer_id ?>">
                                </div>
                                <div class="mb-2">
                                    <label for="cust_name" class="fw-bold">Name</label>
                                    <input
                                        type="text"
                                        name="cust_name"
                                        id="cust_name"
                                        class="form-control <?= ($readonly != '') ? "bg-body-secondary" : '' ?>"
                                        onchange="setFinalBtn();" value="<?= $cust_name; ?>" <?= $readonly; ?>>
                                </div>
                            </div>
                            <hr>
                            <div id="fetch_data">
                            </div>
                            <hr>
                            <div id="couponSection" class="text-center "> <a href="javascript:void(0)" id="addCouponBtn" class="btn btn-sm bg-body-secondary" data-bs-toggle="modal" data-bs-target="#coupon"> <i class="bi bi-ticket-perforated-fill"></i>&nbsp;Add Coupon </a>
                                <div id="appliedCouponBox" class="d-none align-items-center gap-2 bg-success-subtle text-success p-2 rounded mt-2"> <i class="bi bi-ticket-perforated-fill"></i> <strong id="appliedCouponName"></strong> <span class="badge bg-success"> <span id="appliedCouponPercent"></span>% OFF </span> <i class="bi bi-x-circle-fill cursor-pointer text-danger" onclick="removeCoupon()"></i> </div>
                            </div>
                            <h6 class="text-secondary fw-bold mt-3"> Gross Total <span class="float-end" id="grossTotal">0</span> </h6>
                            <h6 class="text-secondary fw-bold"> Discount Amount <span class="float-end" id="discountAmount">0</span> </h6>
                            <h6 class="text-secondary fw-bold" id="expressAmountDisplay">Express Amount: <span class="float-end" id="expressAmount">0</span></h6>
                            <h6 class="text-secondary fw-bold">Total Count: <span class="float-end" data-total-count>0pc</span></h6>
                            <h6 class="fw-bold mt-3"> Total Amount <span class="float-end" id="finalTotal">0</span> </h6> <input type="hidden" name="gross_total" id="gross_total"> <input type="hidden" name="discount_amt" id="discount_amt"> <input type="hidden" name="final_total" id="final_total"> <input type="hidden" name="coupon_master_id" id="coupon_master_id" value="<?= $coupon_master_id; ?>"> <input type="hidden" name="coupon_percent" id="coupon_percent" value="<?= $coupon_percent ?>"> <input type="hidden" name="total_count" id="total_count">
                            <hr>
                            <!-- Delivery Date & Slot -->
                            <div class="d-flex justify-content-between gap-3 mb-3">
                                <div class="flex-fill">
                                    <label class="fw-bold">Delivery Date</label>
                                    <input type="date" class="form-control" name="delivery_date" id="delivery_date" value="<?= $delivery_date; ?>" onchange="setFinalBtn();">
                                </div>

                                <div class="flex-fill">
                                    <label class="fw-bold">Delivery Timeslot</label>
                                    <select class="form-control" name="delivery_slot" id="delivery_slot" onchange="setFinalBtn();">
                                        <option value="">Select Slot</option>
                                        <option>7 AM - 8 AM</option>
                                        <option>8 AM - 9 AM</option>
                                        <option>9 AM - 10 AM</option>
                                        <option>10 AM - 11 AM</option>
                                        <option>11 AM - 12 PM</option>
                                        <option>12 PM - 1 PM</option>
                                        <option>1 PM - 2 PM</option>
                                        <option>2 PM - 3 PM</option>
                                        <option>3 PM - 4 PM</option>
                                        <option>4 PM - 5 PM</option>
                                        <option>5 PM - 6 PM</option>
                                        <option>6 PM - 7 PM</option>
                                        <option>7 PM - 8 PM</option>
                                        <option>8 PM - 9 PM</option>
                                        <option>9 PM - 10 PM</option>
                                    </select>
                                    <script>
                                        document.getElementById('delivery_slot').value = '<?= $delivery_slot; ?>';
                                    </script>
                                </div>
                            </div>
                            <hr>
                            <!-- Home Delivery -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="home_delivery" <?= ($is_home_delivery == 1) ? "checked" : "" ?> onchange="homeDelivery();">
                                        </div>
                                        <label class="fw-semibold mb-0" for="home_delivery">
                                            Home Delivery
                                        </label>
                                    </div>

                                    <!-- Address block -->
                                    <div id="addAddressBtn" class="d-none mt-2">
                                        <select class="form-control form-control-sm mb-1" id="delivery_address_type" name="delivery_address_type">
                                            <option value="Home" selected>Home</option>
                                            <option value="Office">Office</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <script>
                                            document.getElementById('delivery_address_type').value = '<?= $delivery_address_type; ?>';
                                        </script>
                                        <textarea class="form-control form-control-sm"
                                            id="delivery_address" name="delivery_address"
                                            rows="2"
                                            placeholder="Enter delivery address" onkeyup="setFinalBtn();"><?= $delivery_address; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <!-- Express Delivery -->
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="express_delivery" onchange="CheckExpress();" <?= ($is_express_delivery == 1) ? "checked" : "" ?>>
                                        </div>
                                        <label class="fw-semibold mb-0" for="express_delivery">
                                            Express Delivery
                                        </label>
                                    </div>

                                    <select class="form-control form-control-sm d-none mt-2" id="express_charge">
                                        <option value="0">Select</option>
                                        <option value="25">25% Extra</option>
                                        <option value="50">50% Extra</option>
                                        <option value="100">100% Extra</option>
                                    </select>
                                    <script>
                                        document.getElementById('express_charge').value = '<?= $express_percent; ?>';
                                    </script>
                                </div>
                            </div>
                            <input type="hidden" name="address_id" id="address_id" value="0">
                            <!-- Hidden fields -->
                            <input type="hidden" name="is_home_delivery" id="is_home_delivery" value="1">
                            <input type="hidden" name="is_express_delivery" id="is_express_delivery" value="1">
                            <input type="hidden" name="express_percent" id="express_percent" value="<?= $express_percent; ?>">
                            <input type="hidden" name="express_amount" id="express_amount">
                            <button
                                type="submit"
                                name="submit"
                                class="btn btn-success btn-sm mt-3 fw-semibold w-100"
                                id="submitBtn"
                                disabled>

                                <span id="btnText">Create Order</span>
                                <span id="btnLoader" class="spinner-border spinner-border-sm d-none ms-2"></span>
                            </button>

                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card card-body rounded-1" id="show_products">

                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="orderSuccessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="orderSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-4">

                    <!-- Order Number -->
                    <h5 class="mb-3">Order #<span id="successOrderNo"></span></h5>

                    <!-- Success Message -->
                    <p class="text-success mb-1">
                        Order created successfully.
                    </p>

                    <p class="text-muted mb-4">
                        Print the <strong>receipt</strong> and give one copy to the customer.
                    </p>

                    <!-- Actions -->
                    <div class="border-top d-flex flex-wrap justify-content-around">
                        <a href="<?= $pagename; ?>"
                            class="btn btn-link text-danger text-decoration-none p-0 fw-bold">
                            CLOSE
                        </a>
                        <a id="printReceiptBtn" class="btn btn-link text-success text-decoration-none p-0 fw-medium" target="_blank">
                            PRINT RECEIPT
                        </a>
                        <a id="printTagBtn" class="btn btn-link text-success text-decoration-none p-0 fw-medium" target="_blank">
                            PRINT TAG
                        </a>
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
                    <a type="button" data-bs-dismiss="modal"> <i class="bi bi-x fs-3 text-black"></i> </a>
                </div>
                <div class="modal-body" style="height: 300px;" id="fetch_discounts">

                </div>
            </div>
        </div>
    </div>
    <!-- modal coupon End -->

    <!-- Add Garment Details Start -->
    <div class="modal fade" id="garment-details" tabindex="-1" aria-labelledby="garment-detailsLabel">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute">
            <div class="modal-content" id="fetch_garment">

            </div>
        </div>
    </div>
    <!-- Add Garment Details End -->



    <!-- modal add-product Start -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute" style="width: 30% !important;">
            <div class="modal-content" id="modal-data">

            </div>
        </div>
    </div>
    <!-- modal add-product End -->


    <!-- modal add-product-laundry Start -->
    <div class="modal fade" id="laundryModal" tabindex="-1" aria-labelledby="laundryModalLabel" style="background: rgb(11 12 12 / 18%) !important;">
        <div class="end-0 modal-dialog modal-fullscreen position-absolute" style="width: 30% !important;" style="filter: drop-shadow(1px 4px 4px black);">
            <div class="modal-content" id="laundry-data">

            </div>
        </div>
    </div>
    <!-- modal add-product-laundry End -->

</body>
<?php include('inc/js-link.php') ?>
<script>
    document.querySelector("form").addEventListener("submit", function() {

        const btn = document.getElementById("submitBtn");
        const loader = document.getElementById("btnLoader");
        const text = document.getElementById("btnText");

        // btn.disabled = true;
        loader.classList.remove("d-none");
        text.innerText = "Processing...";
    });


    window.EDIT_COUPON = <?= ($coupon_master_id > 0) ? json_encode([
                                'id'      => $coupon_master_id,
                                'name'    => $coupon_name,
                                'percent' => $coupon_percent
                            ]) : 'null'; ?>;

    window.LAUDRY_STATE = {
        service: null,
        addons: [],
        qty: 0,
        areas: []
    };
    document.addEventListener('click', e => {

        const btn = e.target.closest('.count-btn');
        if (!btn) return;

        const box = btn.closest('.count-box');
        const qtyEl = box.querySelector('.qty-value');

        const rawQty = qtyEl ? (qtyEl.value || qtyEl.textContent) : 0;
        let qty = parseFloat(rawQty) || 0;

        if (btn.dataset.action === 'plus') qty++;
        if (btn.dataset.action === 'minus' && qty > 0) qty--;

        qtyEl.value = qty;

        if (box.closest('#productModal')) {
            window.LAUDRY_STATE.qty = qty;
        }

        updateAddButtonState();
    });


    $(document).ready(function() {
        show_products();
        fetch_details();
        if (window.EDIT_COUPON) {
            appliedCoupon = window.EDIT_COUPON;

            document.getElementById('addCouponBtn').classList.add('d-none');
            document.getElementById('appliedCouponBox').classList.remove('d-none');

            document.getElementById('appliedCouponName').innerText =
                appliedCoupon.name;

            document.getElementById('appliedCouponPercent').innerText =
                appliedCoupon.percent;

            document.getElementById('coupon_master_id').value =
                appliedCoupon.id;

            document.getElementById('coupon_percent').value =
                appliedCoupon.percent;

            recalculateTotals();
        }


        show_discounts();
        homeDelivery();


    });

    let appliedCoupon = null;
    window.LAUDRY_MODAL_LOADED = false;

    // total calculaton
    function recalculateTotals() {

        if (!window.ORDER_SUMMARY) {
            console.warn('ORDER_SUMMARY missing');
            return;
        }

        const gross = Number(ORDER_SUMMARY.gross) || 0;
        const count = Math.max(0, Number(ORDER_SUMMARY.count) || 0);

        const expressPercent = Number(document.getElementById('express_charge').value) || 0;

        let discount = 0;
        let expressAmount = 0;

        if (appliedCoupon?.percent) {
            discount = (gross * appliedCoupon.percent) / 100;
        }

        if (expressPercent > 0) {
            expressAmount = (gross * expressPercent) / 100;
        }

        const finalTotal = Math.max(0, gross - discount + expressAmount);
        document.getElementById('grossTotal').textContent = gross.toFixed(2);
        document.getElementById('discountAmount').textContent = discount.toFixed(2);
        document.getElementById('expressAmount').textContent = expressAmount.toFixed(2);
        document.getElementById('express_amount').value = expressAmount.toFixed(2);
        document.getElementById('finalTotal').textContent = finalTotal.toFixed(2);
        document.getElementById('total_count').value = count;
        document.getElementById('gross_total').value = gross.toFixed(2);
        document.getElementById('discount_amt').value = discount.toFixed(2);
        document.getElementById('final_total').value = finalTotal.toFixed(2);

        document.querySelectorAll('[data-total-count]')
            .forEach(el => el.textContent = `${count} pc`);
    }


    function loadProductsByAlpha(alpha) {
        show_products(alpha);
    }

    // for loading discounts
    function show_discounts() {
        $.ajax({
            type: 'POST',
            url: 'ajax/get_discounts.php',
            data: {
                id: 1,
            },
            dataType: 'html',
            success: function(data) {
                $('#fetch_discounts').html(data);
            }
        });
    }

    // for coupoun apply
    function applyCoupon(el) {

        appliedCoupon = {
            id: el.dataset.id,
            name: el.dataset.name,
            percent: parseFloat(el.dataset.amount)
        };

        $('#coupon').modal('hide');

        document.getElementById('addCouponBtn').classList.add('d-none');
        document.getElementById('appliedCouponBox').classList.remove('d-none');

        document.getElementById('appliedCouponName').innerText = appliedCoupon.name;
        document.getElementById('appliedCouponPercent').innerText = appliedCoupon.percent;
        document.getElementById('coupon_master_id').value = appliedCoupon.id;
        document.getElementById('coupon_percent').value = appliedCoupon.percent;

        recalculateTotals();

        Swal.fire({
            toast: true,
            position: 'top',
            icon: 'success',
            title: 'Coupon applied',
            timer: 1200,
            showConfirmButton: false
        });
    }

    // delete coupoun
    function removeCoupon() {
        appliedCoupon = null;
        document.getElementById('appliedCouponBox').classList.add('d-none');
        document.getElementById('addCouponBtn').classList.remove('d-none');

        recalculateTotals();
    }

    // fetch products
    function show_products(alpha = 'ALL', id = "1") {
        const isLaundryByWeight = (String(id) === '2');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_products.php',
            data: {
                id: id,
                alpha: alpha
            },
            success: function(data) {
                if (isLaundryByWeight) {
                    $('#fetch_garment').html(data);
                } else {
                    $('#show_products').html(data);
                    initDefaultServices();
                }
            }
        });
    }

    function show_garment_modal() {
        $('#productModal').modal("hide");
        $('#garment-details').modal("show");

        show_products("ALL", "2");
    }

    function close_modal_garment() {
        $('#garment-details').modal('hide');
        $('#productModal').modal('show');
        get_service(1, 2, null, 'modal');
    }

    function restoreLaundryModalFromState() {

        const st = window.LAUDRY_STATE;
        if (!st || !st.service) return;

        document.querySelectorAll('#modal-services .service-badge').forEach(el => {
            const isMatch = String(el.dataset.id) === String(st.service.id);

            el.classList.toggle('bg-success', isMatch);
            el.classList.toggle('text-white', isMatch);
            el.classList.toggle('selected', isMatch);

            el.classList.toggle('bg-dark-subtle', !isMatch);
            el.classList.toggle('text-black', !isMatch);
        });

        const addons = Array.isArray(st.addons) ? st.addons : [];
        document.querySelectorAll('.addon-check').forEach(chk => {
            chk.checked = addons.some(a => String(a.id) === String(chk.value));
        });

        const qtyEl = document.querySelector('#laundryModal .qty-value');
        if (qtyEl) qtyEl.value = Math.max(1, parseFloat(st.qty || 1));


        const areas = Array.isArray(st.areas) ? st.areas : [];
        document.querySelectorAll('.process-area').forEach(chk => {
            chk.checked = areas.includes(String(chk.value));
        });
        fetchlaundry_items();
        updateAddButtonState();
    }


    // product modal from garment modal
    function handleLaundryAdd(item_id, btn) {
        const card = btn.closest('.laundry-card');
        const activeType = card.querySelector('.item-type.active');
        const item_type_master_id = activeType ?
            activeType.dataset.type :
            '';
        $('#laundryModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal_laundry.php',
            data: {
                item_id: item_id,
                item_type_master_id: item_type_master_id,
                mode: 'add'
            },
            success: function(data) {
                $('#laundry-data').html(data);

                get_service(
                    item_type_master_id,
                    item_id,
                    null,
                    'modal'
                );

                const btn = document.getElementById('addItemBtn1');
                if (btn) {
                    btn.classList.remove('disabled');
                    btn.removeAttribute('aria-disabled');
                }
            }
        });
    }

    // edit a product laundry
    function handleLaundryEdit(order_item_laundry_id) {
        $('#laundryModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal_laundry.php',
            data: {
                mode: 'edit',
                order_item_laundry_id: order_item_laundry_id
            },
            success: function(data) {
                $('#laundry-data').html(data);
            }
        });
    }
    // update product laundry
    function handleLaundryUpdate(order_item_laundry_id, mode = '') {
        const payload = collectModalData1();

        $.ajax({
            type: 'POST',
            url: 'ajax/save_order_item_laundry.php',
            data: {
                order_item_laundry_id: order_item_laundry_id,
                keyvalue: '<?= $keyvalue ?>',
                data: payload
            },
            success: function(res) {
                if (res !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update failed',
                        text: res
                    });
                    return;
                }

                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Item updated',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#laundryModal').modal('hide');
                    if (mode == "edit") {
                        get_service(1, 2, null, 'modal');
                    }
                    fetchlaundry_items();
                });
            }
        });
    }

    function close_laundry_modal() {
        $('#laundryModal').modal('hide');
        get_service(1, 2, null, 'modal');
    }

    // delete laundry item
    function handleLaundryDel(order_item_laundry_id) {
        $.ajax({
            type: 'POST',
            url: 'ajax/delete_master.php',
            data: {
                id: order_item_laundry_id,
                tblname: 'order_item_laundry',
                tblpkey: 'order_item_laundry_id'
            },
            success: function(res) {
                fetchlaundry_items();
            }
        });
    }


    function itemSearch() {
        const search = document
            .querySelector('.modal-header input[type="text"]')
            .value
            .toLowerCase();

        filterLaundryItems(search, window.__ACTIVE_ALPHA || 'ALL');
    }

    window.__ACTIVE_ALPHA = 'ALL';

    document.addEventListener('click', function(e) {

        const alphaEl = e.target.closest('.alpha-filter');
        if (!alphaEl) return;

        window.__ACTIVE_ALPHA = alphaEl.dataset.alpha;

        // update active styles
        document.querySelectorAll('.alpha-filter').forEach(el => {
            el.classList.remove('bg-success', 'text-white');
            el.classList.add('text-success');
        });

        alphaEl.classList.add('bg-success', 'text-white');

        const search = document
            .querySelector('.modal-header input[type="text"]')
            .value
            .toLowerCase();

        filterLaundryItems(search, window.__ACTIVE_ALPHA);
    });


    function filterLaundryItems(search, alpha) {
        document.querySelectorAll('.laundry-card').forEach(card => {

            const name = card.dataset.name;
            const first = card.dataset.alpha;

            const matchSearch = !search || name.includes(search);
            const matchAlpha = alpha === 'ALL' || first === alpha;

            card.style.display =
                (matchSearch && matchAlpha) ? '' : 'none';
        });
    }


    // edit a product
    function editOrderItem(order_item_id, item_id) {
        $('#productModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal.php',
            data: {
                mode: 'edit',
                order_item_id: order_item_id
            },
            success: function(data) {
                $('#modal-data').html(data);
                fetchlaundry_items(order_item_id);
                if (item_id == 2) {
                    document.getElementById('addon-wrapper').classList.remove('d-none');
                    restoreLaundrySelection();
                }
            }
        });
    }

    function fetch_details() {
        $.ajax({
            type: 'POST',
            url: 'ajax/fetch_data.php',
            data: {
                keyvalue: '<?= $keyvalue ?>'
            },
            dataType: 'json',
            success: function(res) {
                $('#fetch_data').html(res.html);

                window.ORDER_SUMMARY = {
                    gross: parseFloat(res.summary.gross) || 0,
                    count: parseInt(res.summary.count, 10) || 0
                };

                if (res.summary.count > 0) {
                    document.getElementById('addCouponBtn').classList.remove('d-none');
                } else {
                    document.getElementById('addCouponBtn').classList.add('d-none');
                }
                recalculateTotals();
                CheckExpress();
                setFinalBtn();
                $('#mobile_no').focus();
            },
            error: function(xhr) {
                console.error('Fetch failed', xhr.responseText);
            }
        });
    }


    // Home Delivery toggle
    function homeDelivery() {
        const customer_id = Number(document.getElementById('customer_id').value) || 0;
        const home_delivery = document.getElementById('home_delivery');
        const addressBox = document.getElementById('addAddressBtn');
        const hidden = document.getElementById('is_home_delivery');
        const keyvalue = '<?= $keyvalue; ?>';
        if (home_delivery.checked) {
            hidden.value = 1;
            addressBox.classList.remove('d-none');
            document.getElementById('delivery_address_type').value = 'Home';
            if (customer_id > 0 && keyvalue == 0) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax_getaddress.php',
                    data: {
                        customer_id: customer_id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'found') {
                            $('#delivery_address_type').val(res.address_type);
                            $('#delivery_address').val(res.address);
                            $('#address_id').val(res.address_id);
                        } else {
                            $('#delivery_address').val('');
                            $('#address_id').val(0);
                        }
                    }
                });
            } else {
                $('#delivery_address').val('');
                $('#address_id').val(0);
            }

        } else {

            hidden.value = 0;
            addressBox.classList.add('d-none');

            // reset fields
            document.getElementById('delivery_address_type').value = '';
            document.getElementById('delivery_address').value = '';
            document.getElementById('address_id').value = 0;
        }
        setFinalBtn();
    }


    // Express Delivery toggle
    function CheckExpress() {

        const checkbox = document.getElementById('express_delivery');
        const sel = document.getElementById('express_charge');
        const expAmt = document.getElementById('expressAmountDisplay');

        document.getElementById('is_express_delivery').value =
            checkbox.checked ? 1 : 0;

        if (checkbox.checked) {

            expAmt.classList.remove('d-none');
            sel.classList.remove('d-none');

            if (sel.value === '0') {
                sel.value = '0';
            }

        } else {

            expAmt.classList.add('d-none');
            sel.classList.add('d-none');

            sel.value = '0';
            document.getElementById('express_percent').value = 0;
        }

        recalculateTotals();
    }


    document.getElementById('express_charge').addEventListener('change', function() {
        document.getElementById('express_percent').value = this.value;
        recalculateTotals();
        setFinalBtn();
    });


    function find_cust(mobile_no) {

        if (!mobile_no || mobile_no.length !== 10) {
            $('#cust_name').val('').prop('readonly', false);
            $('#customer_id').val(0);
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'ajax_getcust.php',
            data: {
                mobile_no: mobile_no
            },
            dataType: 'json',
            success: function(res) {

                if (res.status === 'not_found') {
                    $('#cust_name')
                        .val('')
                        .prop('readonly', false)
                        .removeClass('bg-body-secondary').focus();;

                    $('#customer_id').val(0);

                    Swal.fire({
                        toast: true,
                        position: 'top',
                        icon: 'info',
                        title: 'New customer',
                        timer: 1200,
                        showConfirmButton: false
                    });

                } else {
                    $('#cust_name')
                        .val(res.customer_name)
                        .prop('readonly', true)
                        .addClass('bg-body-secondary');

                    $('#customer_id').val(res.customer_id); // ✅ set id
                    setFinalBtn();
                }
            }
        });
    }

    // show product add modal
    function show_modal(item_id, btn) {
        const card = btn.closest('.item-card');
        const activeType = card.querySelector('.item-type.active');
        const item_type_master_id = activeType ?
            activeType.dataset.type :
            '';

        $('#productModal').modal('show');

        $.ajax({
            type: 'POST',
            url: 'ajax/get_product_modal.php',
            data: {
                item_id: item_id,
                item_type_master_id: item_type_master_id,
                mode: 'add'
            },
            dataType: 'html',
            success: function(data) {
                $('#modal-data').html(data);
                get_service(
                    item_type_master_id,
                    item_id,
                    null,
                    'modal'
                );

                const btn = document.getElementById('addItemBtn');
                if (btn) {
                    btn.classList.add('disabled');
                    btn.setAttribute('aria-disabled', 'true');
                }
                if (item_id == 2) {
                    fetchlaundry_items();
                }
                const qtyEl = document.querySelector('.qty-value');
                if (qtyEl) qtyEl.textContent = 1;
            }
        });
    }

    // show service on change of item 
    function get_service(item_type_master_id, item_id = '', el = null, target = 'card') {
        let container;
        if (target === 'modal') {
            container = document.getElementById('modal-services');
        } else if (el) {
            container = el
                .closest('.card-body')
                .querySelector('.item-service');
        }

        if (!container) return;

        $.ajax({
            type: 'POST',
            url: 'ajax/get_item_service.php',
            data: {
                item_type_master_id: item_type_master_id,
                item_id: item_id,
                context: target
            },
            success: function(data) {
                container.innerHTML = data;
                if (target === 'modal') {
                    setTimeout(() => {
                        if (item_id === 2) {
                            restoreLaundryModalFromState();
                        } else {
                            restoreSelectedServices(item_id);
                        }
                    }, 0);
                }
            }
        });
    }

    function restoreLaundrySelection() {
        const sel = window.__EDIT_SELECTION__;
        if (!sel) return;

        const serviceId =
            sel.service?.id ? String(sel.service.id) :
            (sel.services?.[0]?.id ? String(sel.services[0].id) : null);

        if (serviceId) {
            document.querySelectorAll('#modal-services .service-badge').forEach(el => {
                const match = selectedIds.includes(String(el.dataset.id));
                el.classList.toggle('selected', match);
                el.classList.toggle('bg-success', match);
                el.classList.toggle('text-white', match);

                el.classList.toggle('bg-dark-subtle', !match);
                el.classList.toggle('text-black', !match);
            });
        }

        const addonIds = Array.isArray(sel.addons) ? sel.addons.map(a => String(a.id)) : [];

        document.querySelectorAll('.addon-check').forEach(chk => {
            chk.checked = addonIds.includes(String(chk.value));
        });

        updateProcessAreas();
        updateAddButtonState();
    }


    // show selected services on edit
    function restoreSelectedServices(item_id) {
        const sel = window.__EDIT_SELECTION__;

        if (!sel) return;

        let servicesArr = sel.services ?? sel.service ?? [];

        if (!Array.isArray(servicesArr)) servicesArr = [servicesArr];

        const selectedIds = servicesArr
            .map(s => String(s.id))
            .filter(Boolean);

        document.querySelectorAll('#modal-services .service-badge').forEach(el => {
            const match = selectedIds.includes(String(el.dataset.id));

            el.classList.toggle('selected', match);
            el.classList.toggle('bg-success', match);
            el.classList.toggle('text-white', match);

            el.classList.toggle('bg-dark-subtle', !match);
            el.classList.toggle('text-black', !match);
        });

        updateProcessAreas();
        updateAddButtonState();
    }


    function applyServiceSelection(selectedIds, item_id) {
        item_id = parseInt(item_id, 10);

        const badges = document.querySelectorAll('#modal-services .service-badge');
        if (!badges.length) return;

        badges.forEach(b => {
            b.classList.remove('selected', 'bg-success', 'text-white');
            b.classList.add('bg-dark-subtle', 'text-black');
        });

        // item_id=2 → only one selection
        if (item_id === 2) {
            const first = selectedIds?.[0];
            if (!first) return;

            const el = document.querySelector(`#modal-services .service-badge[data-id="${first}"]`);
            if (!el) return;

            el.classList.add('selected', 'bg-success', 'text-white');
            el.classList.remove('bg-dark-subtle', 'text-black');

            return;
        }

        // normal multi-select
        badges.forEach(el => {
            if (selectedIds.includes(String(el.dataset.id))) {
                el.classList.add('selected', 'bg-success', 'text-white');
                el.classList.remove('bg-dark-subtle', 'text-black');
            }
        });
    }



    function syncQty(el) {
        let val = parseInt(el.value, 10);

        if (isNaN(val) || val < 0) {
            el.value = 0;
        }

        updateAddButtonState();
    }

    // set classes based on selection
    function setActiveType(el) {
        const wrap = el.closest('.card-body');

        wrap.querySelectorAll('.item-type').forEach(badge => {
            badge.classList.remove('bg-success', 'text-white', 'active');
            badge.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        get_service(el.dataset.type, el.dataset.item, el);

    }

    // fetch
    function fetchlaundry_items(order_item_id = 0) {
        $.ajax({
            type: 'POST',
            url: 'ajax/fetch_data_gament.php',
            data: {
                keyvalue: '<?= $keyvalue ?>',
                order_item_id: order_item_id
            },
            dataType: 'html',
            success: function(res) {
                $('#garment-items').html(res);
                updateAddButtonState();
            },
            error: function(xhr) {
                console.error('Fetch failed', xhr.responseText);
            }
        });
    }

    function setActiveTypeLaundry(el) {
        const wrap = el.closest('.card-body');

        wrap.querySelectorAll('.item-type').forEach(badge => {
            badge.classList.remove('bg-success-subtle', 'text-success', 'active');
            badge.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success-subtle', 'text-success', 'active');
    }

    // for searching items
    function itemSearch() {
        let search = document.getElementById('itemSearch').value.toLowerCase();
        document.querySelectorAll('.item-card').forEach(card => {
            card.style.display = card.dataset.name.includes(search) ?
                'block' :
                'none';
        });
    }

    // set the first one active
    function initDefaultServices() {
        document.querySelectorAll('.item-card').forEach(card => {

            const firstType = card.querySelector('.item-type');
            if (!firstType) return;

            const item_id = Number(firstType.dataset.item);

            if (item_id === 2) {
                get_service(
                    firstType.dataset.type,
                    item_id,
                    firstType
                );

                firstType.classList.add('d-none');
                return;
            }

            card.querySelectorAll('.item-type.active')
                .forEach(x => x.classList.remove('active'));

            firstType.classList.remove('bg-dark-subtle', 'text-black');
            firstType.classList.add('bg-success', 'text-white', 'active');

            get_service(
                firstType.dataset.type,
                item_id,
                firstType
            );
        });
    }

    function toggleService(el, item_id) {
        item_id = parseInt(item_id, 10);
        /* ================= ITEM ID = 2 (SINGLE SERVICE) ================= */
        if (item_id === 2) {
            if (el.classList.contains('selected')) return;

            document.querySelectorAll('.service-badge').forEach(badge => {
                badge.classList.remove('selected', 'bg-success', 'text-white');
                badge.classList.add('bg-dark-subtle', 'text-black');
            });

            el.classList.add('selected', 'bg-success', 'text-white');
            el.classList.remove('bg-dark-subtle', 'text-black');

            window.LAUDRY_STATE.service = {
                id: el.dataset.id,
                rate: el.dataset.rate,
                name: el.textContent.split('[')[0].trim()
            };

            document.querySelectorAll('.addon-check').forEach(chk => {
                chk.checked = false;
            });

            document.getElementById('addonlabel').innerText =
                window.LAUDRY_STATE.service.name + ' Addons';

            document.getElementById('addon-wrapper').classList.remove('d-none');

        } else {
            /* ================= NORMAL MULTI SELECT ================= */
            el.classList.toggle('bg-success');
            el.classList.toggle('text-white');
            el.classList.toggle('bg-dark-subtle');
            el.classList.toggle('text-black');
            el.classList.toggle('selected');
        }

        updateProcessAreas();
        updateAddButtonState();
    }

    function toggleServiceLaundry(el) {
        el.classList.toggle('bg-success');
        el.classList.toggle('text-white');
        el.classList.toggle('bg-dark-subtle');
        el.classList.toggle('text-black');
        el.classList.toggle('selected');
    }

    function updateProcessAreas() {

        let needsWashing = false;
        let needsPressing = false;

        document.querySelectorAll('.service-badge.selected').forEach(el => {

            if (el.dataset.is_washing === '1') {
                needsWashing = true;
            }
            if (el.dataset.is_pressing === '1') {
                needsPressing = true;
            }
        });

        const washChk = document.getElementById('area_washing');
        const pressChk = document.getElementById('area_pressing');

        // Washing
        washChk.checked = needsWashing;
        // Pressing
        pressChk.checked = needsPressing;
    }

    function updateAddButtonState() {

        const item = (document.getElementById('modal_item')?.textContent || '').trim();
        const btn = document.getElementById('addItemBtn');
        if (!btn) return;

        const hasService = document.querySelectorAll('.service-badge.selected').length > 0;

        const qtyEl = document.querySelector('.qty-value');
        const rawQty = qtyEl ? (qtyEl.value || qtyEl.textContent) : 1;
        const qty = parseFloat(rawQty) || 0;
        const qtyOk = qty > 0;

        // Laundry By Weight extra validation
        let garmentOk = true;

        if (item === "Laundry By Weight") {
            const garmentBox = document.getElementById("garment-items");

            garmentOk = parseInt(document.getElementById('garment_count')?.value || 0) > 0;
        }

        if (hasService && qtyOk && garmentOk) {
            btn.classList.remove('disabled');
            btn.removeAttribute('aria-disabled');
        } else {
            btn.classList.add('disabled');
            btn.setAttribute('aria-disabled', 'true');
        }
    }

    function collectModalData(item_id) {

        const modal = document.getElementById('productModal');

        const itemTypeEl = modal.querySelector('.modal-item-type.active');
        const item_type_master_id = itemTypeEl ? itemTypeEl.dataset.type : '';

        const qtyEl = modal.querySelector('.qty-value');
        const rawQty = qtyEl ? (qtyEl.value || qtyEl.textContent) : 1;
        const qty = parseFloat(rawQty) || 0;


        const areas = [];
        modal.querySelectorAll('.process-area:checked')
            .forEach(el => areas.push(el.value));

        /* ===================== LAUNDRY (ITEM ID = 2) ===================== */
        if (item_id === 2) {

            // single service
            const serviceEl = modal.querySelector(
                '#modal-services .service-badge.selected'
            );

            if (!serviceEl) {
                alert('Please select a service');
                return null;
            }

            const service = {
                id: serviceEl.dataset.id,
                rate: serviceEl.dataset.rate
            };

            // addons
            const addonContainer = document.getElementById('modal-addons');
            const addons = [];

            if (addonContainer) {
                addonContainer
                    .querySelectorAll('.addon-check:checked')
                    .forEach(chk => {
                        addons.push({
                            id: chk.value,
                            rate: chk.dataset.rate,
                            name: chk.dataset.name
                        });
                    });
            }


            return {
                item_type_master_id,
                qty, // KG
                services: [service],
                addons,
                areas
            };
        }

        /* ===================== NORMAL ITEMS ===================== */

        const services = [];
        modal.querySelectorAll('#modal-services .service-badge.selected')
            .forEach(el => {
                services.push({
                    id: el.dataset.id,
                    rate: el.dataset.rate
                });
            });

        const requirements = [];
        modal.querySelectorAll('#modal-requirements .modal-requirement.selected')
            .forEach(el => {
                requirements.push({
                    id: el.dataset.id,
                    rate: el.dataset.rate
                });
            });

        const comments = [];
        modal.querySelectorAll('#modal-comments .modal-comment.selected')
            .forEach(el => comments.push(el.dataset.id));

        return {
            item_type_master_id,
            qty,
            services,
            requirements,
            comments,
            areas
        };
    }

    function collectModalData1() {

        const modal = document.getElementById('laundryModal');
        const itemTypeEl = modal.querySelector('.modal-item-type.active');
        const item_type_master_id = itemTypeEl ? itemTypeEl.dataset.type : '';

        const qty = document.getElementById('lan_qty').value || 1;
        const comments = [];
        modal.querySelectorAll('#modal-comments .modal-comment.selected')
            .forEach(el => comments.push(el.dataset.id));

        return {
            item_type_master_id,
            qty,
            comments
        };
    }

    function setFinalBtn() {

        const btn = document.getElementById('submitBtn');
        const mobile = document.getElementById('mobile_no');
        const name = document.getElementById('cust_name');
        const delivery_date = document.getElementById('delivery_date');
        const delivery_slot = document.getElementById('delivery_slot');

        const homeDelivery = document.getElementById('home_delivery');
        const expressDelivery = document.getElementById('express_delivery');

        const delivery_address = document.getElementById('delivery_address');
        const express_charge = document.getElementById('express_percent');

        if (!btn || !mobile || !name) {
            console.warn("Missing required DOM elements");
            return;
        }

        const mobileOk = /^[6-9]\d{9}$/.test(mobile.value.trim());
        const nameOk = name.value.trim().length > 0;

        const dateOk = delivery_date && delivery_date.value.trim().length > 0;
        const slotOk = delivery_slot && delivery_slot.value.trim().length > 0;

        const countOk =
            window.ORDER_SUMMARY &&
            Number(window.ORDER_SUMMARY.count) >= 1;

        let deliveryOk = true;
        let expressOk = true;

        if (homeDelivery?.checked) {
            deliveryOk =
                delivery_address &&
                delivery_address.value.trim().length > 0;
        }

        if (expressDelivery?.checked) {
            expressOk = express_charge && express_charge.value > "0";
        }


        const finalState =
            mobileOk &&
            nameOk &&
            countOk &&
            dateOk &&
            slotOk &&
            deliveryOk &&
            expressOk;

        btn.disabled = !finalState;
    }




    function saveItemDetails(item_id) {

        if (document.querySelectorAll('.service-badge.selected').length === 0) {
            alert('Please select at least one service');
            return;
        }

        const payload = collectModalData(item_id);

        $.ajax({
            type: 'POST',
            url: 'ajax/save_order_item.php',
            data: {
                item_id: item_id,
                keyvalue: '<?= $keyvalue ?>',
                data: payload
            },
            success: function(response) {
                if (response !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response || 'Something went wrong'
                    });
                    return;
                }


                Swal.fire({
                    icon: 'success',
                    title: 'Item Added',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#productModal').modal('hide');
                    fetch_details();

                });
            }
        });
    }

    function saveItemDetailsLaundry(item_id) {

        const payload = collectModalData1();
        $.ajax({
            type: 'POST',
            url: 'ajax/save_order_item_laundry.php',
            data: {
                order_item_laundry_id: 0,
                item_id: item_id,
                keyvalue: '<?= $keyvalue ?>',
                data: payload
            },
            success: function(response) {
                if (response !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: response || 'Something went wrong'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Item Added',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#laundryModal').modal('hide');
                    fetchlaundry_items();
                });
            }
        });
    }

    function updateItemDetails(order_item_id) {

        if (document.querySelectorAll('.service-badge.selected').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Select at least one service'
            });
            return;
        }

        const payload = collectModalData();

        $.ajax({
            type: 'POST',
            url: 'ajax/update_order_item.php',
            data: {
                order_item_id: order_item_id,
                data: payload
            },
            success: function(res) {
                if (res !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update failed',
                        text: res
                    });
                    return;
                }

                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Item updated',
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    $('#productModal').modal('hide');
                    fetch_details();
                });
            }
        });
    }


    function allowDecimal(el) {
        // allow digits + one decimal point
        el.value = el.value
            .replace(/[^0-9.]/g, '')
            .replace(/(\..*)\./g, '$1');
    }

    function onlyDigits(el) {
        el.value = el.value.replace(/\D/g, '');
    }


    // delete saved order item
    function deleteOrderItem(order_item_id) {
        $.ajax({
            type: 'POST',
            url: 'ajax/delete_master_order.php',
            data: {
                id: order_item_id,
                tblname: 'order_item',
                tblpkey: 'order_item_id'
            },
            success: function(res) {
                if (res !== 'success') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Delete failed',
                        text: res || 'Unable to delete item'
                    });
                    return;
                }
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Item removed',
                    timer: 1200,
                    showConfirmButton: false
                });
                fetch_details(); // refresh order list
            }
        });
    }



    function setModalItemType(el) {

        document.querySelectorAll('.modal-item-type').forEach(b => {
            b.classList.remove('bg-success', 'text-white', 'active');
            b.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        const modal = document.getElementById('productModal');
        const itemId = modal.querySelector('#addItemBtn')
            ?.getAttribute('onclick')
            ?.match(/\d+/)?.[0];

        get_service(el.dataset.type, itemId, null, 'modal');

    }

    function setModalItemType1(el) {

        document.querySelectorAll('.modal-item-type').forEach(b => {
            b.classList.remove('bg-success', 'text-white', 'active');
            b.classList.add('bg-dark-subtle', 'text-black');
        });

        el.classList.remove('bg-dark-subtle', 'text-black');
        el.classList.add('bg-success', 'text-white', 'active');

        const modal = document.getElementById('productModal');
        const itemId = modal.querySelector('#addItemBtn1')
            ?.getAttribute('onclick')
            ?.match(/\d+/)?.[0];
    }

    function syncHiddenFields() {
        document.getElementById('gross_total').value = ORDER_SUMMARY.gross;
        document.getElementById('total_count').value = ORDER_SUMMARY.count;
        document.getElementById('final_total').value =
            document.getElementById('finalTotal').textContent;
    }

    // for modal change { from laundry by weight to garment modal}
    document.addEventListener('change', (e) => {
        if (!e.target.classList.contains('addon-check')) return;

        const chk = e.target;
        const selectedService = document.querySelector('.service-badge.selected');
        window.LAUDRY_STATE.service = {
            id: selectedService.dataset.id,
            rate: selectedService.dataset.rate,
            name: selectedService.textContent.split('[')[0].trim()
        };

        if (chk.checked) {
            if (!window.LAUDRY_STATE.addons.some(a => a.id === chk.value)) {
                window.LAUDRY_STATE.addons.push({
                    id: chk.value,
                    rate: chk.dataset.rate,
                    name: chk.dataset.name
                });
            }
        } else {
            window.LAUDRY_STATE.addons =
                window.LAUDRY_STATE.addons.filter(a => a.id !== chk.value);
        }
    });
</script>



</html>