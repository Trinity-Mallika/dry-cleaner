<?php include("../adminsession.php");
$pagename = "dashboard.php";
$rangeFrom = $_GET['range_from'] ?? date('Y-m-d');
$rangeTo   = $_GET['range_to'] ?? date('Y-m-d');
$rangeLabel =
    date('d M Y', strtotime($rangeFrom)) .
    " → " .
    date('d M Y', strtotime($rangeTo));


$statusMap = [
    0 => 'Tag',
    1 => 'Delivered',
    2 => 'Store',
    3 => 'Ready'
];

$latestDeliveryDate = $obj->getvalfield(
    "orders",
    "MAX(delivery_date)",
    "1=1"
);

$baseDate = $latestDeliveryDate
    ? date('Y-m-d', strtotime($latestDeliveryDate))
    : date('Y-m-d');

$fromDate = date('Y-m-d', strtotime("$baseDate -10 day"));

$sql = "SELECT 
    o.status,
    DATE(o.delivery_date) AS ddate,
    COUNT(DISTINCT o.order_id) AS order_count,
    SUM(od.qty) AS total_qty
FROM orders o
JOIN order_item od ON od.order_id = o.order_id
WHERE o.delivery_date BETWEEN '$fromDate' AND '$baseDate'
AND o.status!='1'
GROUP BY o.status, DATE(o.delivery_date);
";
$res = $obj->executequery($sql);

$data = [];

foreach ($res as $row) {

    $statusCode = (int)$row['status'];
    $statusName = $statusMap[$statusCode] ?? 'Unknown';
    $date       = $row['ddate'];

    $data[$statusName][$date] = [
        'orders' => (int)$row['order_count'],
        'qty'    => (int)$row['total_qty']
    ];
}

$totalData = [];

foreach ($data as $status => $dates) {
    if ($status === 'Delivered') continue;

    foreach ($dates as $date => $vals) {

        $totalData[$date]['orders'] =
            ($totalData[$date]['orders'] ?? 0) + $vals['orders'];

        $totalData[$date]['qty'] =
            ($totalData[$date]['qty'] ?? 0) + $vals['qty'];
    }
}

$data['Total'] = $totalData;

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dry Cleaner</title>
</head>
<?php include('inc/css-link.php') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .btn.active {
        background-color: #c1f1c1;
        border-color: #bde5bd;
    }

    .pu-ready {
        background: #d1ff89 !important;
    }
</style>

<body>
    <!-- Sidebar -->
    <?php include('inc/sidebar.php'); ?>
    <!-- Header -->
    <?php include('inc/header.php'); ?>
    <div id="mainWrapper" class="main-content">
        <!-- Sidebar Close-->
        <div class="container pt-3">
            <?php
            $days = 11;
            $statuses = [
                'Total' => [
                    'class' => 'purple',
                    'source' => 'Total'
                ],
                'Processing' => [
                    'class' => 'black',
                    'source' => 'Store'
                ],
                'Ready' => [
                    'class' => 'green',
                    'source' => 'Ready'
                ],
            ];


            ?>

            <div class="table-responsive" style="height: 500px;">
                <table class="table table-bordered">
                    <thead class="position-sticky top-0">
                        <!-- DATE HEADER -->
                        <tr>
                            <th class="nowrap text-center blue headcol">Delivery Date</th>
                            <?php $today = date('Y-m-d');
                            for ($i = 0; $i < $days; $i++) {
                                $colDate = date('Y-m-d', strtotime("$baseDate -$i day"));
                                $class = ($colDate === $today)
                                    ? 'red'
                                    : 'blue';
                            ?>
                                <th class="nowrap text-center <?= $class ?>">
                                    <?= date('D j M', strtotime("$baseDate -$i day")) ?>
                                </th>
                            <?php } ?>
                        </tr>

                        <!-- STATUS ROWS -->
                        <?php foreach ($statuses as $label => $cfg) {

                            $source = $cfg['source'];
                            $headerTotal = 0;

                            if (isset($data[$source])) {
                                foreach ($data[$source] as $d) {
                                    $headerTotal += $d['orders'];
                                }
                            }
                        ?>
                            <tr>
                                <th class="nowrap text-center <?= $cfg['class'] ?> headcol">
                                    <?= $label ?> [<?= $headerTotal ?>]
                                </th>

                                <?php
                                for ($i = 0; $i < $days; $i++) {
                                    $date = date('Y-m-d', strtotime("$baseDate -$i day"));

                                    $orders = $data[$source][$date]['orders'] ?? 0;
                                    $qty    = $data[$source][$date]['qty'] ?? 0;

                                    echo "<th class='nowrap text-center {$cfg['class']}'>
                        {$orders} - {$qty} pc
                      </th>";
                                }
                                ?>
                            </tr>
                        <?php } ?>

                    </thead>

                    <?php $bodyData = [];

                    $res = $obj->executequery("SELECT 
    o.order_id,
    o.order_no,
    o.delivery_date,
    c.customer_name,
    c.mobile,
    o.status,
    SUM(oi.qty) AS qty,
    o.createtime AS order_time,
    o.storage_label AS storage,
    o.is_home_delivery AS home_delivery,
    o.is_express_delivery AS express_delivery,
    o.gross_total AS amount
FROM orders o
JOIN order_item oi ON oi.order_id = o.order_id
JOIN m_customer c ON c.customer_id = o.customer_id
WHERE o.delivery_date BETWEEN '$fromDate' AND '$baseDate'
AND o.status!='1'
GROUP BY o.order_id, DATE(o.delivery_date)
ORDER BY o.delivery_date DESC;
");

                    foreach ($res as $row) {
                        $date = date('Y-m-d', strtotime($row['delivery_date']));

                        $bodyData[$date][] = [
                            'order_id' => $row['order_id'],
                            'order_no' => $row['order_no'],
                            'name'     => $row['customer_name'],
                            'mobile'     => $row['mobile'],
                            'qty'      => (int)$row['qty'],
                            'amount'   => $row['amount'],
                            'storage'   => $row['storage'],
                            'home_delivery'   => $row['home_delivery'],
                            'express_delivery'   => $row['express_delivery'],
                            'time'     => date('h:i A', strtotime($row['order_time'])),
                            'status'   => (int)$row['status']
                        ];
                    }
                    $maxRows = 0;
                    foreach ($bodyData as $orders) {
                        $maxRows = max($maxRows, count($orders));
                    }
                    ?>
                    <tbody>
                        <?php for ($r = 0; $r < $maxRows; $r++) { ?>
                            <tr>
                                <td class="nowrap text-center"></td>
                                <?php for ($c = 0; $c < $days; $c++) {
                                    $date = date('Y-m-d', strtotime("$baseDate -$c day"));
                                    $order = $bodyData[$date][$r] ?? null;
                                    if (!$order) {
                                        if ($r === 4) {
                                            echo "<td class='text-center text-success fw-semibold'>
                            LOAD DETAIL
                          </td>";
                                        } else {
                                            echo "<td></td>";
                                        }

                                        continue;
                                    }
                                ?>
                                    <td class="nowrap text-start <?= ($order['status'] == 3) ? "pu-ready" : "" ?>">
                                        <a href="b2c-order.php?order_no=<?= $order['order_no'] ?>" class="text-decoration-none text-black" target="_blank"> <?= ($r + 1) ?>.
                                            <?= $order['order_no'] ?>-
                                            <?= $order['qty'] ?> pc-
                                            ₹<?= $order['amount'] ?>-
                                            <?= $order['time'] ?>-
                                        </a>
                                        <span class="btn btn-sm cursor pointer-event fw-semibold" onclick="call_customer(
'<?= $order['order_id'] ?>',
'<?= $order['order_no'] ?>',
'<?= strtoupper($order['name']) ?>',
'<?= $order['mobile'] ?>',
'<?= $order['qty'] ?>',
'<?= $order['amount'] ?>',
'<?= $obj->dateformatindia($date) ?>',
'<?= $order['time'] ?>',
'<?= $order['status'] ?>'
);"><i class="bi bi-telephone-fill"></i>&nbsp;&nbsp;<?= strtoupper($order['name']) ?></span>
                                        <?php if ($order['express_delivery'] == 1) { ?><img src="img/fast-delivery.png" width="30" alt=""><?php } ?>
                                        <span class="bg-body border-bottom btn btn-sm cursor fw-bold" onclick="set_storage('<?= $order['order_id'] ?>','<?= $order['order_no'] ?>','<?= $order['storage'] ?>');">
                                            <?php if ($order['storage'] == 0) { ?>
                                                <i class="bi bi-check2 fs-4 text-success"></i>
                                            <?php } else { ?>
                                                <span class=" text-success"><?= $order['storage'] ?></span>
                                            <?php } ?>
                                        </span>
                                    </td>
                                <?php } ?>
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
                    <a class="btn btn-success rounded-1 btn-sm" id="dateBtn">
                        <i class="bi bi-calendar4-event"></i>
                        <span id="dateLabel"><?= $rangeLabel ?></span>
                        <input type="text" id="dateRange" style="position:absolute; visibility:hidden;">
                    </a>

                    <span class="float-end pt-2 fs-14">Refreshed At <?= date("H:i:s A") ?></span>
                </div>
            </div>
            <!-- SUMMARY BAR -->
            <div class="d-flex flex-wrap gap-2 mb-3 small">
                <?php $timeRanges = [
                    '7 AM - 8 AM',
                    '8 AM - 9 AM',
                    '9 AM - 10 AM',
                    '10 AM - 11 AM',
                    '11 AM - 12 PM',
                    '12 PM - 1 PM',
                    '1 PM - 2 PM',
                    '2 PM - 3 PM',
                    '3 PM - 4 PM',
                    '4 PM - 5 PM',
                    '5 PM - 6 PM',
                    '6 PM - 7 PM',
                    '7 PM - 8 PM',
                    '8 PM - 9 PM',
                    '9 PM - 10 PM'
                ];

                $grossSale = $obj->getvalfield(
                    "orders",
                    "IFNULL(SUM(gross_total),0)",
                    "delivery_date BETWEEN '$rangeFrom' AND '$rangeTo'"
                );

                $discount = $obj->getvalfield(
                    "orders",
                    "IFNULL(SUM(discount_amt),0)",
                    "delivery_date BETWEEN '$rangeFrom' AND '$rangeTo'"
                );

                $express = $obj->getvalfield(
                    "orders",
                    "IFNULL(SUM(express_amount),0)",
                    "delivery_date BETWEEN '$rangeFrom' AND '$rangeTo'"
                );

                $totalSale = $obj->getvalfield(
                    "orders",
                    "IFNULL(SUM(final_total),0)",
                    "delivery_date BETWEEN '$rangeFrom' AND '$rangeTo'"
                );

                $totalPayment = $obj->getvalfield(
                    "payment",
                    "IFNULL(SUM(pay_amount),0)",
                    "pay_date BETWEEN '$rangeFrom' AND '$rangeTo'"
                );

                $slotSql = "
SELECT
    CASE
        WHEN HOUR(o.createtime)=7  THEN '7 AM - 8 AM'
        WHEN HOUR(o.createtime)=8  THEN '8 AM - 9 AM'
        WHEN HOUR(o.createtime)=9  THEN '9 AM - 10 AM'
        WHEN HOUR(o.createtime)=10 THEN '10 AM - 11 AM'
        WHEN HOUR(o.createtime)=11 THEN '11 AM - 12 PM'
        WHEN HOUR(o.createtime)=12 THEN '12 PM - 1 PM'
        WHEN HOUR(o.createtime)=13 THEN '1 PM - 2 PM'
        WHEN HOUR(o.createtime)=14 THEN '2 PM - 3 PM'
        WHEN HOUR(o.createtime)=15 THEN '3 PM - 4 PM'
        WHEN HOUR(o.createtime)=16 THEN '4 PM - 5 PM'
        WHEN HOUR(o.createtime)=17 THEN '5 PM - 6 PM'
        WHEN HOUR(o.createtime)=18 THEN '6 PM - 7 PM'
        WHEN HOUR(o.createtime)=19 THEN '7 PM - 8 PM'
        WHEN HOUR(o.createtime)=20 THEN '8 PM - 9 PM'
        WHEN HOUR(o.createtime)=21 THEN '9 PM - 10 PM'
        ELSE 'Other'
    END AS delivery_slot,

    /* WO */
    COUNT(CASE WHEN o.status=0 THEN 1 END) AS wo_orders,
    SUM(CASE WHEN o.status=0 THEN o.total_count ELSE 0 END) AS wo_qty,

    /* HOME DELIVERY */
    COUNT(CASE WHEN o.is_home_delivery=1 AND o.status=0 THEN 1 END) AS p_nrhd,
    SUM(CASE WHEN o.is_home_delivery=1 AND o.status=0 THEN o.total_count ELSE 0 END) AS p_nrhd_qty,

    COUNT(CASE WHEN o.is_home_delivery=1 AND o.status=3 THEN 1 END) AS p_rhd,
    SUM(CASE WHEN o.is_home_delivery=1 AND o.status=3 THEN o.total_count ELSE 0 END) AS p_rhd_qty,

    COUNT(CASE WHEN o.is_home_delivery=1 AND o.status=1 THEN 1 END) AS c_hd,
    SUM(CASE WHEN o.is_home_delivery=1 AND o.status=1 THEN o.total_count ELSE 0 END) AS c_hd_qty,

    /* WALKIN DELIVERY */
    COUNT(CASE WHEN o.is_home_delivery=0 AND o.status=2 THEN 1 END) AS p_nrwd,
    SUM(CASE WHEN o.is_home_delivery=0 AND o.status=2 THEN o.total_count ELSE 0 END) AS p_nrwd_qty,

    COUNT(CASE WHEN o.is_home_delivery=0 AND o.status=3 THEN 1 END) AS p_rwd,
    SUM(CASE WHEN o.is_home_delivery=0 AND o.status=3 THEN o.total_count ELSE 0 END) AS p_rwd_qty,

    COUNT(CASE WHEN o.is_home_delivery=0 AND o.status=1 THEN 1 END) AS c_wd,
    SUM(CASE WHEN o.is_home_delivery=0 AND o.status=1 THEN o.total_count ELSE 0 END) AS c_wd_qty

FROM orders o

WHERE TIMESTAMP(o.createdate,o.createtime)
      BETWEEN '$rangeFrom 00:00:00'
          AND '$rangeTo 23:59:59'

GROUP BY delivery_slot
ORDER BY MIN(TIMESTAMP(o.createdate,o.createtime));

";

                $resSlot = $obj->executequery($slotSql);

                $defaultRow = [
                    'wo_orders' => 0,
                    'wo_qty' => 0,
                    'p_nrhd' => 0,
                    'p_nrhd_qty' => 0,
                    'p_rhd' => 0,
                    'p_rhd_qty' => 0,
                    'c_hd' => 0,
                    'c_hd_qty' => 0,
                    'p_nrwd' => 0,
                    'p_nrwd_qty' => 0,
                    'p_rwd' => 0,
                    'p_rwd_qty' => 0,
                    'c_wd' => 0,
                    'c_wd_qty' => 0,
                    'p_nrhd_overdue' => 0,
                    'p_rhd_overdue' => 0,
                    'p_nrwd_overdue' => 0,
                    'p_rwd_overdue' => 0
                ];

                /* initialize ALL slots first */
                $slotData = [];
                foreach ($timeRanges as $slot) {
                    $slotData[$slot] = $defaultRow;
                }

                foreach ($resSlot as $r) {

                    $slot = trim($r['delivery_slot']);

                    if (!isset($slotData[$slot])) {
                        continue;
                    }

                    foreach ($defaultRow as $k => $v) {
                        $slotData[$slot][$k] += (int)($r[$k] ?? 0);
                    }
                }

                $totals = $defaultRow;

                foreach ($slotData as $row) {
                    foreach ($totals as $k => $v) {
                        $totals[$k] += $row[$k];
                    }
                }


                function overdueClass($row, $key)
                {
                    return (!empty($row[$key]) && $row[$key] > 0)
                        ? 'text-danger fw-bold'
                        : '';
                }
                ?>
                <span class="btn bg-body-secondary btn-sm">
                    Gross Sale ₹<?= number_format($grossSale) ?>
                </span>
                <span class="btn bg-body-secondary btn-sm">
                    Discount ₹<?= number_format($discount) ?>
                </span>

                <span class="btn bg-body-secondary btn-sm">
                    Express ₹<?= number_format($express) ?>
                </span>
                <span class="btn bg-body-secondary btn-sm">
                    Total Sale ₹<?= number_format($totalSale) ?>
                </span>
                <span class="btn bg-body-secondary btn-sm">Total Payment ₹<?= number_format($totalPayment) ?></span>

            </div>

            <!-- TABLE -->
            <div class="table-responsive">

                <table class="table table-strip align-middle small report-table">

                    <thead class="table-light text-center">
                        <tr>
                            <th>T</th>
                            <th>WO [<?= $totals['wo_orders'] ?> - <?= $totals['wo_qty'] ?>]</th>
                            <th>P-P [0]</th>
                            <th>C-P [0]</th>
                            <th>P-NRHD [<?= $totals['p_nrhd'] ?>]</th>
                            <th>P-RHD [<?= $totals['p_rhd'] ?>]</th>
                            <th>C-HD [<?= $totals['c_hd'] ?>]</th>
                            <th>P-NRWD [<?= $totals['p_nrwd'] ?>]</th>
                            <th>P-RWD [<?= $totals['p_rwd'] ?>]</th>
                            <th>C-WD [<?= $totals['c_wd'] ?>]</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($timeRanges as $slot):

                            $row = $slotData[$slot] ?? [];

                        ?>
                            <tr>
                                <td class="p-3 fs-6"><?= $slot ?></td>

                                <!-- WO -->
                                <td class="text-center <?= ($row['wo_orders'] > 0) ? 'cursor openModal' : 'text-secondary' ?> "
                                    data-slot="<?= $slot ?>"
                                    data-type="WO"
                                    data-from="<?= $rangeFrom ?>"
                                    data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['wo_orders'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['wo_orders'] > 0) ? $row['wo_orders'] : 0 ?></span>
                                    <sub class=" <?= ($row['wo_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['wo_qty'] > 0) ? $row['wo_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center text-secondary"
                                    data-slot="<?= $slot ?>" data-type="PP"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <?= 0 ?>
                                </td>

                                <td class="text-center text-secondary"
                                    data-slot="<?= $slot ?>" data-type="CP"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <?= 0 ?>
                                </td>

                                <td class="text-center <?= ($row['p_nrhd'] > 0) ? 'cursor openModal' : 'text-secondary' ?> <?= overdueClass($row, 'p_nrhd_overdue') ?>"
                                    data-slot="<?= $slot ?>" data-type="P_NRHD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['p_nrhd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['p_nrhd'] > 0) ? $row['p_nrhd'] : 0 ?></span>
                                    <sub class=" <?= ($row['p_nrhd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['p_nrhd_qty'] > 0) ? $row['p_nrhd_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center <?= ($row['p_rhd'] > 0) ? 'cursor openModal' : 'text-secondary' ?> <?= overdueClass($row, 'p_rhd_overdue') ?>"
                                    data-slot="<?= $slot ?>" data-type="P_RHD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['p_rhd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['p_rhd'] > 0) ? $row['p_rhd'] : 0 ?></span>
                                    <sub class=" <?= ($row['p_rhd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['p_rhd_qty'] > 0) ? $row['p_rhd_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center <?= ($row['c_hd'] > 0) ? 'cursor openModal' : 'text-secondary' ?>"
                                    data-slot="<?= $slot ?>" data-type="C_HD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['c_hd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['c_hd'] > 0) ? $row['c_hd'] : 0 ?></span>
                                    <sub class=" <?= ($row['c_hd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['c_hd_qty'] > 0) ? $row['c_hd_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center <?= ($row['p_nrwd'] > 0) ? 'cursor openModal' : 'text-secondary' ?> <?= overdueClass($row, 'p_nrwd_overdue') ?>"
                                    data-slot="<?= $slot ?>" data-type="P_NRWD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['p_nrwd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['p_nrwd'] > 0) ? $row['p_nrwd'] : 0 ?></span>
                                    <sub class=" <?= ($row['p_nrwd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['p_nrwd_qty'] > 0) ? $row['p_nrwd_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center <?= ($row['p_rwd'] > 0) ? 'cursor openModal' : 'text-secondary' ?> <?= overdueClass($row, 'p_rwd_overdue') ?>"
                                    data-slot="<?= $slot ?>" data-type="P_RWD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['p_rwd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['p_rwd'] > 0) ? $row['p_rwd'] : 0 ?></span>
                                    <sub class=" <?= ($row['p_rwd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['p_rwd_qty'] > 0) ? $row['p_rwd_qty'] : '' ?></sub>
                                </td>

                                <td class="text-center <?= ($row['c_wd'] > 0) ? 'cursor openModal' : 'text-secondary' ?>"
                                    data-slot="<?= $slot ?>" data-type="C_WD"
                                    data-from="<?= $rangeFrom ?>" data-to="<?= $rangeTo ?>">
                                    <span class=" <?= ($row['c_wd'] > 0) ? 'fw-bold fs-4' : '' ?>"> <?= ($row['c_wd'] > 0) ? $row['c_wd'] : 0 ?></span>
                                    <sub class=" <?= ($row['c_wd_qty'] > 0) ? 'fs-13' : '' ?>"><?= ($row['c_wd_qty'] > 0) ? $row['c_wd_qty'] : '' ?></sub>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>


                    <tfoot class="text-center">
                        <tr>
                            <th>TOTAL</th>

                            <th>WO [<?= $totals['wo_orders'] ?> - <?= $totals['wo_qty'] ?>]</th>
                            <th>P-P [0]</th>
                            <th>C-P [0]</th>
                            <th>P-NRHD [<?= $totals['p_nrhd'] ?>]</th>
                            <th>P-RHD [<?= $totals['p_rhd'] ?>]</th>
                            <th>C-HD [<?= $totals['c_hd'] ?>]</th>
                            <th>P-NRWD [<?= $totals['p_nrwd'] ?>]</th>
                            <th>P-RWD [<?= $totals['p_rwd'] ?>]</th>
                            <th>C-WD [<?= $totals['c_wd'] ?>]</th>
                        </tr>
                    </tfoot>

                </table>
                <p class="fs-14 fw-semibold text-center text-secondary pt-3 pb-3">
                    WO : Walkin Order
                    &nbsp;&nbsp;&nbsp; P-P : Pending Pickup
                    &nbsp;&nbsp;&nbsp;C-P : Completed Pickup
                    &nbsp;&nbsp;&nbsp;P-NRHD : Pending Not-Ready-Home Delivery
                    &nbsp;&nbsp;&nbsp;P-RHD : Pending Ready-Home-Delivery
                    &nbsp;&nbsp;&nbsp;C-HD : Completed Home-Delivery
                    &nbsp;&nbsp;&nbsp;P-NRWD : Pending Not-Ready-Walkin-Delivery
                    &nbsp;&nbsp;&nbsp;P-RWD : Pending Ready-Walkin-Delivery
                    &nbsp;&nbsp;&nbsp;C-WD : Completed Walkin-Delivery</p>
            </div>

        </div>
    </div>

    <!-- Orders Modal -->
    <div class="modal fade" id="ordersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="fs-14">Store</th>
                                    <th class="fs-14">Booking Id.</th>
                                    <th class="fs-14">Order Status</th>
                                    <th class="fs-14">Customer Name</th>
                                    <th class="fs-14">Created At</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>


    <!--Customet Detail Modal Start-->
    <div class="modal fade custom-modal" id="customer-detail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="modal_head">Call</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row g-2 small">
                        <div class="col-6">
                            <strong>Customer :</strong><br>
                            <span id="info_customer"></span>
                        </div>
                        <div class="col-6">
                            <strong>Mobile No. :</strong><br>
                            <a href="#" id="info_mobile" class="text-primary text-decoration-none"></a>
                        </div>
                        <div class="col-6">
                            <strong>Order No :</strong><br>
                            <span id="info_order_no"></span>
                        </div>

                        <div class="col-6">
                            <strong>Status :</strong><br>
                            <span id="info_status" class="badge bg-secondary"></span>
                        </div>

                        <div class="col-6">
                            <strong>Quantity :</strong><br>
                            <span id="info_qty"></span>
                        </div>

                        <div class="col-6">
                            <strong>Amount :</strong><br>
                            ₹<span id="info_amount"></span>
                        </div>

                        <div class="col-6">
                            <strong>Delivery Date :</strong><br>
                            <span id="info_date"></span>
                        </div>

                        <div class="col-6">
                            <strong>Order Time :</strong><br>
                            <span id="info_time"></span>
                        </div>

                    </div>

                </div>

                <div class="modal-footer border-top-0">
                    <a data-bs-dismiss="modal" class="btn btn-sm btn-danger cursor">Close</a>
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
                    <small id="order_head"> Orders: 1690279</small>
                </div>
                <div class="modal-body">
                    <h6 class="fw-normal text-secondary">Select Storage Label</h6>

                    <div class="container py-3">

                        <!-- Tabs -->
                        <ul class="nav flex-wrap gap-2 border-0" id="numberTabs" role="tablist">
                            <?php for ($i = 1; $i < 47; $i++) { ?>
                                <li class="nav-item" role="presentation">
                                    <button
                                        type="button"
                                        class="btn rounded-circle number-pill storage-btn <?php echo ($i == 1 ? 'active' : ''); ?>"
                                        data-storage="<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </button>
                                </li>
                            <?php } ?>
                        </ul>
                        <input type="hidden" id="order_id_storage">
                        <input type="hidden" id="storage_label">

                    </div>
                    <div class="modal-footer border-top-0 p-0">
                        <a class="btn btn-success btn-sm cursor ms-2" onclick="save_storage();">Mark As Ready</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- check-detail Modal ENd-->

</body>
<?php include('inc/js-link.php') ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let temp = [],
        confirmed = [];

    const fp = flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "d M Y",
        clickOpens: false,
        closeOnSelect: false,

        onChange: d => {
            temp = d;
            setTimeout(() => fp.open(), 0);
        },

        onReady: (_, __, fp) => {

            const btn = (text, action) => {
                let b = document.createElement("button");
                b.innerHTML = text;
                b.style = "border:none;background:none;color:#00a86b;font-weight:bold";
                b.onclick = action;
                return b;
            };

            let footer = document.createElement("div");
            footer.style = "display:flex;justify-content:space-between;padding:10px";

            footer.append(
                btn("CANCEL", () => {
                    fp.setDate(confirmed, false);
                    fp.close();
                }),

                btn("OK", () => {
                    if (temp.length == 2) {

                        confirmed = temp;

                        let from = fp.formatDate(temp[0], "Y-m-d");
                        let to = fp.formatDate(temp[1], "Y-m-d");
                        window.location =
                            "dashboard.php?range_from=" + from +
                            "&range_to=" + to;

                    }
                })

            );

            fp.calendarContainer.appendChild(footer);
        }
    });

    dateBtn.onclick = () => fp.open();


    $(document).on("click", ".openModal", function() {

        let slot = $(this).data("slot");
        let type = $(this).data("type");
        let from = $(this).data("from");
        let to = $(this).data("to");

        $("#ordersModal tbody").html(
            "<tr><td colspan='5' class='text-center'>Loading...</td></tr>"
        );

        $("#ordersModal").modal("show");

        $.post("ajax/load_slot_orders.php", {
            slot: slot,
            type: type,
            from: from,
            to: to
        }, function(res) {
            $("#ordersModal tbody").html(res);
        });
    });
</script>




<script>
    function call_customer(
        order_id,
        order_no,
        name,
        mobile,
        qty,
        amount,
        date,
        time,
        status
    ) {

        $("#modal_head").html("Order Info");

        $("#info_customer").text(name);
        $("#info_mobile")
            .text(mobile)
            .attr("href", "tel:" + mobile);

        $("#info_order_no").text(order_no);
        $("#info_qty").text(qty + " pc");
        $("#info_amount").text(amount);
        $("#info_date").text(date);
        $("#info_time").text(time);

        let statusText = "Pending";
        let badge = "bg-warning";

        if (status == 2) {
            statusText = "Process At Store";
            badge = "bg-primary";
        }
        if (status == 3) {
            statusText = "Ready";
            badge = "bg-success";
        }

        $("#info_status")
            .text(statusText)
            .removeClass()
            .addClass("badge " + badge);

        $("#customer-detail").modal("show");
    }

    $(document).on("click", ".storage-btn", function() {

        $(".storage-btn").removeClass("active")
            .addClass("btn-light");

        $(this).addClass("active")
            .removeClass("btn-light");

        $("#storage_label").val($(this).data("storage"));
    });


    function set_storage(order_id, order_no, storage) {

        $("#order_id_storage").val(order_id);
        $("#order_head").text("Orders: " + order_no);

        $(".storage-btn")
            .removeClass("active")
            .addClass("btn-light");

        let selectedStorage;

        if (storage && storage != 0) {
            selectedStorage = storage;
        } else {
            selectedStorage = 1;
        }

        let btn = $('.storage-btn[data-storage="' + selectedStorage + '"]');

        btn.removeClass("btn-light")
            .addClass("active");

        $("#storage_label").val(selectedStorage);

        $("#check-detail").modal("show");
    }



    function save_storage() {

        let order_id = $("#order_id_storage").val();
        let storage_label = $("#storage_label").val();

        if (!storage_label) {
            alert("Please select storage label");
            return;
        }

        $.ajax({
            url: "ajax/save_storage.php",
            type: "POST",
            data: {
                order_id: order_id,
                storage_label: storage_label
            },
            success: function(res) {
                $("#check-detail").modal("hide");
                location.reload();
            }
        });
    }


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