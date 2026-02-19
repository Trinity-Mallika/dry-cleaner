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
$crit = "WHERE 1=1";

if (isset($_GET['order_no'])) {
    $order_no = $obj->test_input($_GET['order_no']);
    if ($order_no != '') {
        $crit .= " and o.order_no LIKE '%$order_no%'";
    }
} else {
    $order_no = "";
}
if (isset($_GET['status'])) {
    $status = $obj->test_input($_GET['status']);
    if ($status != '') {
        $crit .= " and o.pay_status='$status'";
    }
} else {
    $status = "1";
}

if ((isset($_GET['from_date'])) && (isset($_GET['to_date']))) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
} else {
    $from_date = date("Y-m-d");
    $to_date = date("Y-m-d");
}

if ($from_date != '' && $to_date != '') {
    $crit .= " AND o.createdate BETWEEN '$from_date' AND '$to_date'";
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
                                <label for="" class="fw-bold">From Date <span class="text-danger fw-bold">*</span></label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $from_date ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">To Date <span class="text-danger fw-bold">*</span></label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $to_date ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="" class="fw-bold">Order No.</label>
                                <input type="text" name="order_no" id="order_no" class="form-control" placeholder="Enter Order No." value="<?= $order_no; ?>">
                            </div>
                            <div class="col-lg-3">
                                <label for="status" class="fw-bold"> Status <span class="text-danger fw-bold"></span></label>
                                <select name="status" id="status" class="form-select chosen-select">
                                    <option value="">Select</option>
                                    <option value="0">Due</option>
                                    <option value="1">Paid</option>
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
                            <td class="fw-semibold">Actions</td>
                            <td class="fw-semibold">Status</td>
                            <td class="fw-semibold">Invoice No.</td>
                            <td class="fw-semibold">Order No.</td>
                            <td class="fw-semibold">Paid Amount</td>
                            <td class="fw-semibold">Due Amount</td>
                            <td class="fw-semibold">Total</td>
                            <td class="fw-semibold">Tax</td>
                            <td class="fw-semibold">Taxable Amt</td>
                            <td class="fw-semibold">Express Amt</td>
                            <td class="fw-semibold">Discount Amt</td>
                            <td class="fw-semibold">Gross Total</td>
                        </tr>
                    </thead>
                    <tbody class="text-nowrap">
                        <?php
                        $res = $obj->executequery("SELECT 
        o.*,
        IFNULL(p.paid_amount,0) AS paid_amount,
        IFNULL(t.taxable_amt,0) AS taxable_amt,
        IFNULL(t.tax_amt,0) AS tax_amt
    FROM orders o
    LEFT JOIN (
        SELECT order_id, SUM(pay_amount) AS paid_amount
        FROM payment
        GROUP BY order_id
    ) p ON p.order_id = o.order_id
    LEFT JOIN (
        SELECT 
            order_id,
            SUM(taxable_amount) AS taxable_amt,
            SUM(cgst_amount + sgst_amount) AS tax_amt
        FROM order_item
        GROUP BY order_id
    ) t ON t.order_id = o.order_id
    $crit
    ORDER BY o.order_id DESC
");


                        foreach ($res as $key) {
                        ?>
                            <tr>
                                <td class="nowrap">
                                    <?php if ($key['pay_status'] == 0) { ?>
                                        <a href="receipt_pdf.php?order_id=<?= $key['order_id'] ?>" class="badge rounded-1 bg-primary fs-10 p-1 text-decoration-none cursor" target="_blank">Receipt</a>
                                        <a href="javascript:void(0)" class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor" onclick="payModal('<?= $key['order_id'] ?>','<?= $key['order_no'] ?>','<?= round($key['final_total']) ?>','<?= $key['paid_amount']; ?>');">
                                            Settle Order
                                        </a>
                                    <?php } else { ?>
                                        <a href="bill_pdf.php?order_id=<?= $key['order_id'] ?>" class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor" target="_blank">Invoice</a>
                                    <?php } ?>
                                </td>
                                <td><?= ($key['pay_status'] == 1) ? "Paid" : "Draft" ?></td>
                                <td><?= ($key['invoice_no']) ? "INV" . $key['invoice_no'] : '-' ?></td>
                                <td><?= $key['order_no'] ?><?php if ($key['is_express_delivery'] == 1) { ?><img src="img/fast-delivery.png" width="30" alt=""><?php } ?></td>
                                <td><?= $key['paid_amount'] ?></td>
                                <td><?= round($key['final_total']) - $key['paid_amount'] ?></td>
                                <td><?= round($key['final_total']) ?></td>
                                <td><?= number_format($key['tax_amt'], 2) ?></td>
                                <td><?= number_format($key['taxable_amt'], 2) ?></td>
                                <td><?= $key['express_amount'] ?></td>
                                <td><?= $key['discount_amt']  ?></td>
                                <td><?= round($key['final_total']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>
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
</script>


</html>