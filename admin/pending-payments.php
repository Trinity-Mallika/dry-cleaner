<?php include("../adminsession.php");
$pagename = 'pending-payments.php';
$title = "Pending Payments";
$module = "Pending Payments";
$submodule = "Pending Payments";
$tblname = "orders";
$tblpkey = "order_id";

$crit = "WHERE 1=1 and o.status=1 and o.pay_status=0";

if ((isset($_GET['from_date'])) && (isset($_GET['to_date']))) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
} else {
    $from_date = date("Y-m-d", strtotime("-15 days"));
    $to_date   = date("Y-m-d");
}

if ($from_date != '' && $to_date != '') {
    $crit .= " AND o.delivery_date BETWEEN '$from_date' AND '$to_date'";
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
</head>
<?php include('inc/css-link.php') ?>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container-fluid mt-5">
            <?php //include('inc/alert.php'); 
            ?>
            <div class="card">
                <div class="card-header bg-success-subtle fw-bold">
                    Pending Payments
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
                            <div class="col-lg-3 mt-4">
                                <input type="submit" name="submit" class="btn btn-success btn-sm  fw-semibold" value="Search">
                                <a href="<?php echo $pagename; ?>" class="btn btn-danger btn-sm  fw-semibold"> Reset </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive mt-4">

                        <table id="example" class="table table-hover align-middle mb-2 mt-2">
                            <thead class="table-light text-nowrap">
                                <tr>
                                    <th class="fw-semibold">Order No.</th>
                                    <th class="fw-semibold">Actions</th>
                                    <th class="fw-semibold">Order Status</th>
                                    <th class="fw-semibold">Amount</th>
                                    <th class="fw-semibold">Paid Amount</th>
                                    <th class="fw-semibold">Due Amount</th>
                                    <th class="fw-semibold">Order Date</th>
                                    <th class="fw-semibold">Delivery Date</th>
                                    <th class="fw-semibold">Delivery Slot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = $obj->executequery("SELECT o.*,COALESCE(paid.paid_amount, 0) AS paid_amount FROM orders o LEFT JOIN (
        SELECT order_id, SUM(pay_amount) AS paid_amount FROM payment GROUP BY order_id) paid ON paid.order_id = o.order_id $crit ORDER BY o.order_id DESC");
                                foreach ($res as $key) {
                                ?>
                                    <tr>
                                        <td><?= $key['order_no'] ?></td>
                                        <td><a href="receipt_pdf.php?order_id=<?= $key['order_id'] ?>" target="_blank" class="badge rounded-1 bg-success fs-10 p-1 text-decoration-none cursor">
                                                <i class="bi bi-clipboard-check"></i> INVOICE
                                            </a></td>
                                        <td> Delivered</td>
                                        <td><?= round($key['final_total']) ?></td>
                                        <td><?= round($key['paid_amount']) ?></td>
                                        <td><?= round($key['final_total']) - $key['paid_amount'] ?></td>
                                        <td><?= $obj->dateformatindia($key['createdate']) . " " . $key['createtime'] ?></td>
                                        <td><?= $obj->dateformatindia($key['delivery_date']) ?></td>
                                        <td><?= $key['delivery_slot'] ?></td>
                                    </tr>
                                <?php } ?>
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
</script>

</html>