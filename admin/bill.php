<?php include("../adminsession.php");
$pagename = "bill.php";
$title = "Invoices";
$module = "Invoices";
$submodule = "Invoices";
$btn_name = "Save";
$tblname = "orders";
$tblpkey = "order_id";
$keyvalue = (isset($_GET[$tblpkey])) ? $obj->test_input($_GET[$tblpkey]) : 0;
$action = (isset($_GET['action'])) ? $obj->test_input($_GET['action']) : '';
$crit = "WHERE o.pay_status=0";

if (isset($_GET['order_no'])) {
    $order_no = $obj->test_input($_GET['order_no']);
    if ($order_no != '') {
        $crit .= " and o.order_no LIKE '%$order_no%'";
    }
} else {
    $order_no = "";
}

if (isset($_GET['mob_no'])) {
    $mob_no = $obj->test_input($_GET['mob_no']);
    if ($mob_no != '') {
        $crit .= " and c.mobile LIKE '%$mob_no%'";
    }
} else {
    $mob_no = "";
}

if (isset($_GET['status'])) {
    $status = $obj->test_input($_GET['status']);
    if ($status != '') {
        $crit .= " and o.status='$status'";
    }
} else {
    $status = "0";
}
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

    .moreDropdown {
        position: fixed;
        display: none;
        background: #fff;
        border: 1px solid #ddd;
        min-width: 160px;
        z-index: 9999;
        border-radius: 6px;
    }

    .moreDropdown li a {
        display: block;
        padding: 6px 10px;
        font-size: 12px;
        color: #000;
        text-decoration: none;
    }

    .moreDropdown li a:hover {
        background: #f1f1f1;
    }

    .cursor {
        cursor: pointer;
    }

    .nav-link:focus,
    .nav-link:hover {
        color: #000000;
        background: #cde5da;
        border-radius: 5px;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link,
    .product_search_list li a:active {
        padding: 4px 18px;
    }

    .settel {
        background: #e8f7f1;
        color: #000000;
    }

    .nav-pills .nav-link {
        border-radius: var(--bs-nav-pills-border-radius);
        padding: 4px 17px;
    }
</style>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-5">
            <div class="card">
                <div class="card-header bg-success-subtle fw-bold">
                    <?= $module; ?>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Order No.</label>
                                <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Enter Order No." value="<?= $order_no; ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Mobile No. </label>
                                <input type="text" name="mob_no" id="mob_no" class="form-control" placeholder="Enter Mobile No." value="<?= $mob_no; ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="status" class="fw-bold"> Status <span class="text-danger fw-bold"></span></label>
                                <select name="status" id="status" class="form-select chosen-select">
                                    <option value="">Select</option>
                                    <option value="0">Tagged</option>
                                    <option value="1">Delivered</option>
                                    <option value="2">Processing At Store</option>
                                    <option value="3">Ready Order</option>
                                    <option value="4">Cancelled</option>
                                </select>
                                <script>
                                    document.getElementById('status').value = '<?= $status; ?>';
                                </script>
                            </div>

                            <div class="col-lg-3 mt-4">
                                <input type="submit" name="submit" class="btn btn-success btn-sm  fw-semibold" value="Search">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive mt-4">
                <table id="example" class="table table-hover align-middle mb-0">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <td class="fw-semibold fs-14">Actions</td>
                            <td class="fw-semibold fs-14">Order No.</td>
                            <td class="fw-semibold fs-14">Order Status</td>
                            <td class="fw-semibold fs-14">Due Amount</td>
                            <td class="fw-semibold fs-14">Invoice Amount</td>
                            <td class="fw-semibold fs-14">Delivery Date</td>
                            <td class="fw-semibold fs-14">Delivery Timeslot</td>
                            <td class="fw-semibold fs-14">Customer Name</td>
                            <td class="fw-semibold fs-14">Customer Mobile</td>
                            <td class="fw-semibold fs-14">Customer Address</td>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        <?php
                        $res = $obj->executequery("SELECT o.*,
           c.customer_name,c.mobile,
           a.address,
           IFNULL(p.paid_amount,0) as paid_amount
    FROM orders o
    LEFT JOIN m_customer c ON c.customer_id = o.customer_id
    LEFT JOIN m_address a ON a.address_id = o.address_id
    LEFT JOIN (
        SELECT order_id, SUM(pay_amount) as paid_amount
        FROM payment
        GROUP BY order_id
    ) p ON p.order_id = o.order_id
    $crit
    ORDER BY o.order_id DESC
");

                        foreach ($res as $key) {
                        ?>
                            <tr>
                                <td class="nowrap">
                                    <?php if ($key['status'] == 0) { ?>
                                        <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor"
                                            onclick="change_status('<?= $key['order_id'] ?>','2','<?= $key['order_no'] ?>');">
                                            <i class="bi bi-clipboard-check"></i> PROCESS AT STORE
                                        </a>
                                    <?php }
                                    if ($key['status'] == 2) { ?>
                                        <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor"
                                            onclick="change_status('<?= $key['order_id'] ?>','3','<?= $key['order_no'] ?>');">
                                            <i class="bi bi-patch-check"></i> MARK READY
                                        </a>
                                    <?php }
                                    if ($key['status'] == 0 || $key['status'] >= 2) { ?>
                                        <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor"
                                            onclick="change_status('<?= $key['order_id'] ?>','1','<?= $key['order_no'] ?>');">
                                            <i class="bi bi-heart-fill"></i> MARK DELIVERED
                                        </a>
                                    <?php }
                                    if ($key['status'] == 0 || $key['status'] == 2 || $key['status'] == 3) { ?>
                                        <a class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor"
                                            href="new-walk-in.php?order_id=<?= $key['order_id'] ?>">
                                            <i class="bi bi-tag-fill"></i> RE-TAG
                                        </a>
                                    <?php } ?>
                                    <span class="position-relative d-inline-block">
                                        <a href="javascript:void(0)"
                                            class="badge rounded-1 text-black cursor border fs-10 p-1 text-decoration-none more-btn">
                                            <i class="bi bi-three-dots-vertical"></i> More
                                        </a>
                                        <ul class="moreDropdown list-unstyled shadow">
                                            <li><a href="javascript:void(0)" onclick="rescheduleModal('<?= $key['order_id'] ?>','<?= $key['order_no'] ?>');" class="fs-6"><i class="bi bi-arrow-clockwise"></i>&nbsp;&nbsp;&nbsp;&nbsp;Reschedule</a></li>
                                            <li><a href="bill_pdf.php?order_id=<?= $key['order_id'] ?>" class="fs-6" target="_blank"><i class="bi bi-cash"></i>&nbsp;&nbsp;&nbsp;&nbsp;Bill Receipt</a></li>
                                            <?php if ($key['status'] == 0) { ?>
                                                <li><a href="tag_print.php?order_id=<?= $key['order_id']; ?>" class="fs-6" target="_blank"><i class="bi bi-printer-fill"></i>&nbsp;&nbsp;&nbsp;&nbsp;Print Tag</a></li>
                                            <?php }
                                            if ($key['pay_status'] == 0) {  ?>
                                                <li><a href="javascript:void(0)" class="fs-6" onclick="payModal('<?= $key['order_id'] ?>','<?= $key['order_no'] ?>','<?= round($key['final_total']) ?>','<?= $key['paid_amount']; ?>');">
                                                        <i class="bi bi-cash-stack"></i>&nbsp;&nbsp;&nbsp;&nbsp;Settle Order
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </span>

                                </td>

                                <td class="fs-12"><?= $key['order_no'] ?></td>
                                <td class="fs-12">
                                    <?php
                                    if ($key['status'] == 0) {
                                        echo '<span class="badge bg-warning text-dark">Tagged</span>';
                                    } elseif ($key['status'] == 1) {
                                        echo '<span class="badge bg-success">Delivered</span>';
                                    } elseif ($key['status'] == 2) {
                                        echo '<span class="badge bg-info text-dark">Process At Store</span>';
                                    } elseif ($key['status'] == 3) {
                                        echo '<span class="badge bg-primary">Ready</span>';
                                    }
                                    ?>
                                </td>
                                <td class="fs-12"><?= round($key['final_total']) - $key['paid_amount'] ?></td>
                                <td class="fs-12"><?= round($key['final_total']) ?></td>
                                <td class="fs-12"><?= $obj->dateformatindia($key['delivery_date']) ?></td>
                                <td class="fs-12"><?= $key['delivery_slot'] ?></td>
                                <td class="fs-12"><?= $key['customer_name'] ?? '' ?></td>
                                <td class="fs-12"><?= $key['mobile'] ?? '' ?></td>
                                <td class="fs-12"><?= $key['address'] ?? '' ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div class="modal fade" id="reschedule" data-bs-backdrop="static" tabindex="-1" aria-labelledby="rescheduleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow">

                <!-- HEADER -->
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-semibold mb-0" id="rescheduleLabel">
                            Reschedule Order
                        </h5>
                        <small class="text-muted">Order No: <span class="fw-semibold" id="rescheduleOrder">170288</span></small>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <!-- Form Section -->
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">

                                <div class="col-lg-4 col-md-6">
                                    <label for="delivery_date" class="form-label fw-semibold mb-1">Delivery Date</label>
                                    <select id="delivery_date" class="form-select">
                                        <option value="">Select Date</option>
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-6">
                                    <label for="delivery_slot" class="form-label fw-semibold mb-1">Time Slot</label>
                                    <select id="delivery_slot" class="form-select">
                                        <option value="">Select Time Slot</option>
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
                                </div>

                                <div class="col-lg-4 col-md-12">
                                    <label for="reason" class="form-label fw-semibold mb-1">Reason</label>
                                    <select id="reason" class="form-select">
                                        <option value="Call Not Answered">Call Not Answered</option>
                                        <option value="Call Not Connecting">Call Not Connecting</option>
                                        <option value="Requested By Customer" selected>Requested By Customer</option>
                                        <option value="Customer Not Avaliable At Location">Customer Not Available At Location</option>
                                        <option value="Rider Not Avaliable">Rider Not Available</option>
                                        <option value="Clothes Not Ready">Clothes Not Ready</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- History Section -->
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                        <h6 class="fw-semibold mb-0">Reschedule History</h6>
                        <span class="badge bg-secondary-subtle text-dark border">Latest updates shown below</span>
                    </div>

                    <div class="table-responsive border rounded">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>S.No.</th>
                                    <th>Date</th>
                                    <th>Time Slot</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody id="reschedule_data">

                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-white">
                    <input type="hidden" id="order_id_m">

                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-primary px-4" onclick="add_reschedule();">
                        Reschedule
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!-- Settel Modal -->
    <div class="modal fade" id="settle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="settleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="settleLabel">Settle Order [<span class="fw-semibold" id="orderSettle"> #1720865</span>]</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-around">
                        <span class="fw-semibold text-secondary">Invoice Amount : INR <span class="fw-semibold" id="InvTotal"> 430</span></span>
                        <span class="fw-semibold text-secondary">Paid Amount : INR <span class="fw-semibold" id="PaidTotal"> 430</span></span>
                        <span class="fw-semibold text-secondary">Due Amount : INR <span class="fw-semibold" id="DueTotal"> 430</span></span>
                    </div>
                    <div class="mt-3 border-top pt-3 pb-3">
                        <label for="" class="fw-bold">Select Payment Method</label>
                        <ul class="justify-content-around mb-3 mt-2 nav nav-pills" id="paymentTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="active btn-sm nav-link settel rounded-1" data-mode="Cash" data-bs-toggle="pill" data-bs-target="#cashTab" type="button">
                                    Cash @ Store
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="btn-sm nav-link settel rounded-1" data-mode="UPI" data-bs-toggle="pill" data-bs-target="#upiTab" type="button">
                                    UPI (GPay, BHIM, PhonePe, Paytm)
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="btn-sm nav-link settel rounded-1" data-mode="Other" data-bs-toggle="pill" data-bs-target="#otherTab" type="button">
                                    Other
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="btn-sm nav-link settel rounded-1" data-mode="Prepaid" data-bs-toggle="pill" data-bs-target="#prepaidTab" type="button">
                                    Prepaid
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="btn-sm nav-link settel rounded-1" data-mode="Account Transfer" data-bs-toggle="pill" data-bs-target="#accountTab" type="button">
                                    Account @ Transfer
                                </button>
                            </li>
                        </ul>

                    </div>
                    <hr>
                    <div class="justify-content-between pt-0">
                        <label for="">Enter Amount</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="pay_amount" oninput="updateDueAmount();">

                    </div>
                    <div class="text-center pb-3 mt-2">
                        <h6><b>New Due Amount :</b> INR <span id="new_due_amt">0</span></h6>
                        <input type="hidden" id="payment_mode" value="Cash">
                        <input type="hidden" id="orderid_m">
                        <input type="hidden" id="old_paid_amount" value="0">
                        <button type="button" class="btn btn-sm btn-success w-25" id="btnSavePayment">SETTLE</button>
                    </div>
                    <div>
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Amount (INR)</th>
                                    <th width="120">Action</th>
                                </tr>
                            </thead>
                            <tbody id="payHistoryBody" class="text-center">
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No payments found</td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
<?php include('inc/js-link.php') ?>
<script>
    $(document).ready(function() {
        $('#example').DataTable();
        $(".chosen-select").select2({
            width: '100%'
        });
    });
    let activeBtn = null;

    document.querySelectorAll('.more-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            const dropdown = this.nextElementSibling;
            const rect = this.getBoundingClientRect();

            // Close all other dropdowns
            document.querySelectorAll('.moreDropdown').forEach(d => {
                if (d !== dropdown) d.style.display = 'none';
            });

            dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
            dropdown.style.left = (rect.left + window.scrollX) + 'px';

            // Toggle show/hide
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';

            activeBtn = this;
        });
    });

    // Outside click close
    document.addEventListener('click', function() {
        document.querySelectorAll('.moreDropdown').forEach(d => d.style.display = 'none');
    });

    $(document).on('click', '#paymentTab button', function() {
        $('#payment_mode').val($(this).data('mode'));
    });


    function payModal(order_id, order_no, final_total, paid_amount) {
        $('#settle').modal("show");
        $('#orderid_m').val(order_id);
        $('#orderSettle').html("#" + order_no);
        show_data_pay(order_id);
        final_total = parseFloat(final_total) || 0;
        paid_amount = parseFloat(paid_amount) || 0;

        let balance = final_total - paid_amount;
        if (balance < 0) balance = 0;

        $('#InvTotal').html(final_total.toFixed(2));
        $('#old_paid_amount').val(paid_amount.toFixed(2));

        $('#pay_amount').val(balance);
        if (balance <= 0) {
            $('#pay_amount').val("0.00").prop('readonly', true);
        } else {
            $('#pay_amount').prop('readonly', false);
        }
        $('#PaidTotal').html(paid_amount.toFixed(2));
        $('#DueTotal').html(balance.toFixed(2));
        $('#new_due_amt').html(balance.toFixed(2));

        updateDueAmount();
    }


    function updateDueAmount() {
        let inv = parseFloat($('#InvTotal').text()) || 0;
        let oldPaid = parseFloat($('#old_paid_amount').val()) || 0;
        let currentPay = parseFloat($('#pay_amount').val()) || 0;

        let maxPay = inv - oldPaid;
        if (maxPay < 0) maxPay = 0;

        if (currentPay > maxPay) currentPay = maxPay;
        if (currentPay < 0) currentPay = 0;

        $('#pay_amount').val(currentPay);

        let newPaid = oldPaid + currentPay;
        let newDue = inv - newPaid;

        $('#PaidTotal').html(oldPaid.toFixed(2));
        $('#DueTotal').html(newDue.toFixed(2));
        $('#new_due_amt').html(newDue.toFixed(2));
    }


    $(document).on('click', '#btnSavePayment', function() {
        let order_id = $('#orderid_m').val();
        let invoice_amt = parseFloat($('#InvTotal').text()) || 0;
        let paid_amt = parseFloat($('#pay_amount').val()) || 0;
        let pay_mode = $('#payment_mode').val();

        if (!order_id) {
            alert("Order ID missing");
            return;
        }

        if (paid_amt <= 0) {
            alert("Enter valid amount");
            return;
        }

        if (paid_amt > invoice_amt) {
            alert("Paid amount cannot be greater than invoice amount");
            return;
        }

        $.ajax({
            type: "POST",
            url: "ajax/save_payment.php",
            data: {
                order_id: order_id,
                invoice_amt: invoice_amt,
                paid_amt: paid_amt,
                pay_mode: pay_mode
            },
            dataType: "json",
            success: function(res) {
                if (res.status == "success") {
                    show_data_pay(order_id);
                    $('#PaidTotal').html(res.paid_total);
                    $('#DueTotal').html(res.due_total);
                    $('#new_due_amt').html(res.due_total);
                    $('#old_paid_amount').val(res.paid_total);
                    let due = parseFloat(res.due_total) || 0;
                    if (due <= 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top',
                            icon: 'success',
                            title: 'Order Settled successfully',
                            timer: 1200,
                            showConfirmButton: false
                        });
                        $('#settle').modal('hide');
                        location.reload();
                    }
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            }
        });
    });

    function show_data_pay(order_id) {
        $.ajax({
            type: "POST",
            url: "ajax_fetch_payment.php",
            data: {
                order_id: order_id
            },
            success: function(data) {
                $('#payHistoryBody').html(data);
            }
        });

    }



    function rescheduleModal(order_id, order_no) {
        $('#reschedule').modal("show");
        $('#order_id_m').val(order_id);
        $('#rescheduleOrder').html(order_no);
        show_data(order_id);
    }

    function show_data(order_id) {
        $.ajax({
            type: "POST",
            url: "ajax_fetch_reschedule.php",
            data: {
                order_id: order_id
            },
            success: function(data) {
                $('#reschedule_data').html(data);
            }
        });

    }

    function add_reschedule() {
        let delivery_date = document.getElementById('delivery_date').value;
        let delivery_slot = document.getElementById('delivery_slot').value;
        let reason = document.getElementById('reason').value;
        let order_id = document.getElementById('order_id_m').value;

        if (delivery_date == '') {
            alert("Select a Date for Reschedule");
            return false;
        }

        if (delivery_slot == '') {
            alert("Select a Timeslot for Reschedule");
            return false;
        }
        if (reason == '') {
            alert("Select a reason for Reschedule");
            return false;
        }

        $.ajax({
            type: "POST",
            url: "ajax_add_reschedule.php",
            data: {
                order_id: order_id,
                delivery_date: delivery_date,
                delivery_slot: delivery_slot,
                reason: reason
            },
            success: function(res) {
                location.reload();
            }
        });
    }

    function change_status(order_id, status, order_no) {
        const statusdisp = (status == 2) ? "PROCESS AT STORE" : ((status == 3) ? "MARK AS READY" : "MARK AS DELIVERED");
        Swal.fire({
            title: statusdisp,
            text: "Change for Order " + order_no + " ?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {

            if (!result.isConfirmed) return;

            $.ajax({
                type: "POST",
                url: "ajax_change_status.php",
                data: {
                    order_id: order_id,
                    status: status
                },
                success: function(res) {
                    res = res.trim();

                    if (res === "success") {
                        Swal.fire("Updated!", "Order status updated.", "success");
                        location.reload();
                    } else {
                        Swal.fire("Error", res, "error");
                    }
                }
            });

        });
    }

    const select = document.getElementById("delivery_date");

    const formatDate = (date) => {
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, "0");
        const d = String(date.getDate()).padStart(2, "0");
        return `${y}-${m}-${d}`;
    };

    const today = new Date();

    for (let i = 0; i < 10; i++) {
        const dt = new Date(today);
        dt.setDate(today.getDate() + i);

        const value = formatDate(dt);

        const opt = document.createElement("option");
        opt.value = value;
        opt.textContent = value;

        // auto-select today
        if (i === 0) opt.selected = true;

        select.appendChild(opt);
    }
</script>


</html>