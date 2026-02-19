<?php include("../adminsession.php");
$pagename = 'payment.php';
$title = "Payment Summary";
$module = "Payment Summary";
$submodule = "Payment Summary";
$tblname = "orders";
$tblpkey = "order_id";

$crit = "WHERE 1=1";

if ((isset($_GET['from_date'])) && (isset($_GET['to_date']))) {
    $from_date = $_GET['from_date'];
    $to_date = $_GET['to_date'];
} else {
    $from_date = date("Y-m-d", strtotime("-15 days"));
    $to_date   = date("Y-m-d");
}

if ($from_date != '' && $to_date != '') {
    $crit .= " AND pay_date BETWEEN '$from_date' AND '$to_date'";
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
                    <?= $module ?>
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
            <div class="card mt-4">
                <div class="card-header bg-success-subtle fw-bold">
                    Payment List
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php $dateSummary = [];
                        $orderBreakdown = [];

                        $paymentModes = [
                            'Cash' => [
                                'label' => 'Cash @ Store',
                                'db'    => ['cash_store'],
                                'class' => 'text-success'
                            ],
                            'UPI' => [
                                'label' => 'UPI (GPay, BHIM, PhonePe, Paytm)',
                                'db'    => ['upi'],
                                'class' => 'text-primary'
                            ],
                            'Other' => [
                                'label' => 'Other',
                                'db'    => ['other'],
                                'class' => 'text-secondary'
                            ],
                            'Prepaid' => [
                                'label' => 'Prepaid',
                                'db'    => ['prepaid'],
                                'class' => 'text-warning'
                            ],
                            'Account Transfer' => [
                                'label' => 'Account @ Transfer',
                                'db'    => ['account_transfer'],
                                'class' => 'text-info'
                            ],
                        ];

                        $res = $obj->executequery("SELECT pay_date, SUM(pay_amount) AS total_amount,
        SUM(CASE WHEN pay_type = 'Cash' THEN pay_amount ELSE 0 END) AS cash_store,
        SUM(CASE WHEN pay_type = 'UPI' THEN pay_amount ELSE 0 END) AS upi,
        SUM(CASE WHEN pay_type = 'Other' THEN pay_amount ELSE 0 END) AS other,
        SUM(CASE WHEN pay_type = 'Prepaid' THEN pay_amount ELSE 0 END) AS prepaid,
        SUM(CASE WHEN pay_type = 'Account Transfer' THEN pay_amount ELSE 0 END) AS account_transfer

    FROM payment  $crit GROUP BY pay_date ORDER BY pay_date DESC
");

                        $dateSummary = [];
                        foreach ($res as $row) {
                            $dateSummary[$row['pay_date']] = $row;
                        }

                        $orderRes = $obj->executequery("
    SELECT 
        DATE(p.pay_date) AS pay_date,
        o.order_no,
        SUM(p.pay_amount) AS total,
        SUM(CASE WHEN p.pay_type = 'Cash' THEN p.pay_amount ELSE 0 END) AS cash_store,
        SUM(CASE WHEN p.pay_type = 'UPI' THEN p.pay_amount ELSE 0 END) AS upi,
        SUM(CASE WHEN p.pay_type = 'Other' THEN p.pay_amount ELSE 0 END) AS other,
        SUM(CASE WHEN p.pay_type = 'Prepaid' THEN p.pay_amount ELSE 0 END) AS prepaid,
        SUM(CASE WHEN p.pay_type = 'Account Transfer' THEN p.pay_amount ELSE 0 END) AS account_transfer
    FROM payment p
    JOIN orders o ON o.order_id = p.order_id
    $crit
    GROUP BY DATE(p.pay_date), o.order_id
    ORDER BY p.pay_date DESC
");


                        $orderBreakdown = [];

                        foreach ($orderRes as $r) {
                            $date = $r['pay_date'];

                            $orderBreakdown[$date][] = [
                                'order_no'          => $r['order_no'],
                                'total'             => $r['total'],
                                'cash_store'        => $r['cash_store'],
                                'upi'               => $r['upi'],
                                'other'             => $r['other'],
                                'prepaid'           => $r['prepaid'],
                                'account_transfer'  => $r['account_transfer'],
                            ];
                        }


                        ?>
                        <table class="table align-middle table-bordered table-hover">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="fw-semibold">Date</th>
                                    <th class="fw-semibold">Total</th>
                                    <?php foreach ($paymentModes as $mode) { ?>
                                        <th class="fw-semibold"><?= $mode['label'] ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($dateSummary as $date => $row) {

                                    $collapseId = "collapseDate_" . $i;
                                ?>
                                    <!-- DATE SUMMARY ROW -->
                                    <tr class="align-middle"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?= $collapseId ?>"
                                        aria-expanded="false"
                                        style="cursor:pointer;">
                                        <td class="p-2 fs-14">
                                            <i class="bg-body-secondary bi bi-chevron-down fs-14 me-2 rounded-circle" style="padding: 5px 7px;"></i>
                                            <?= date('d-m-Y', strtotime($date)) ?>
                                        </td>

                                        <td class="p-2 fs-14"><?= number_format($row['total_amount'], 0) ?></td>


                                        <td class="p-2 fs-14"><?= number_format($row['cash_store'] ?? 0, 0) ?></td>

                                        <td class="p-2 fs-14"><?= number_format($row['upi'] ?? 0, 0) ?></td>
                                        <td class="p-2 fs-14"><?= number_format($row['other'] ?? 0, 0) ?></td>
                                        <td class="p-2 fs-14"><?= number_format($row['prepaid'] ?? 0, 0) ?></td>
                                        <td class="p-2 fs-14"><?= number_format($row['account_transfer'] ?? 0, 0) ?></td>

                                    </tr>
                                    <tr class="collapse bg-light" id="<?= $collapseId ?>">
                                        <td colspan="7" class="p-3 bg-body-secondary">
                                            <div class="rounded bg-white p-3">
                                                <table class="table table-sm mb-0">
                                                    <thead class="p-2 fs-14">
                                                        <tr>
                                                            <th>Order No.</th>
                                                            <th>Total</th>
                                                            <th>Cash @ Store</th>
                                                            <th>UPI</th>
                                                            <th>Other</th>
                                                            <th>Prepaid</th>
                                                            <th>Account Transfer</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php
                                                        if (!empty($orderBreakdown[$date])) {
                                                            foreach ($orderBreakdown[$date] as $ord) {
                                                        ?>
                                                                <tr>
                                                                    <td class="p-2 fs-14">#<?= $ord['order_no'] ?></td>
                                                                    <td class="p-2 fs-14"><?= $ord['total'] ?></td>
                                                                    <td class="p-2 fs-14"><?= number_format($ord['cash_store'], 0) ?></td>
                                                                    <td class="p-2 fs-14"><?= number_format($ord['upi'], 0) ?></td>
                                                                    <td class="p-2 fs-14"><?= number_format($ord['other'], 0) ?></td>
                                                                    <td class="p-2 fs-14"><?= number_format($ord['prepaid'], 0) ?></td>
                                                                    <td class="p-2 fs-14"><?= number_format($ord['account_transfer'], 0) ?></td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='5' class='text-center text-muted'>No orders</td></tr>";
                                                        }
                                                        ?>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
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
    });
</script>

</html>