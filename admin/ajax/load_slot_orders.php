<?php
include("../../adminsession.php");

/* RECEIVE DATA */
$slot = trim($_POST['slot'] ?? '');
$type = trim($_POST['type'] ?? '');
$from = trim($_POST['from'] ?? '');
$to   = trim($_POST['to'] ?? '');



function getSlotRange($slot)
{
    $map = [
        '7 AM - 8 AM'   => ['07:00:00', '07:59:59'],
        '8 AM - 9 AM'   => ['08:00:00', '08:59:59'],
        '9 AM - 10 AM'  => ['09:00:00', '09:59:59'],
        '10 AM - 11 AM' => ['10:00:00', '10:59:59'],
        '11 AM - 12 PM' => ['11:00:00', '11:59:59'],
        '12 PM - 1 PM'  => ['12:00:00', '12:59:59'],
        '1 PM - 2 PM'   => ['13:00:00', '13:59:59'],
        '2 PM - 3 PM'   => ['14:00:00', '14:59:59'],
        '3 PM - 4 PM'   => ['15:00:00', '15:59:59'],
        '4 PM - 5 PM'   => ['16:00:00', '16:59:59'],
        '5 PM - 6 PM'   => ['17:00:00', '17:59:59'],
        '6 PM - 7 PM'   => ['18:00:00', '18:59:59'],
        '7 PM - 8 PM'   => ['19:00:00', '19:59:59'],
        '8 PM - 9 PM'   => ['20:00:00', '20:59:59'],
        '9 PM - 10 PM'  => ['21:00:00', '21:59:59'],
    ];

    return $map[$slot] ?? null;
}

$where = "1=1";

switch ($type) {

    case 'WO': // Walkin Order (Tagged)
        $where .= " AND o.status = 0";
        break;

    case 'P_NRHD': // Pending Not Ready Home Delivery
        $where .= " AND o.status = 0 AND o.is_home_delivery = 1";
        break;

    case 'P_RHD': // Pending Ready Home Delivery
        $where .= " AND o.status = 3 AND o.is_home_delivery = 1";
        break;

    case 'C_HD': // Completed Home Delivery
        $where .= " AND o.status = 1 AND o.is_home_delivery = 1";
        break;

    case 'P_NRWD': // Pending Not Ready Walkin
        $where .= " AND o.status = 2 AND o.is_home_delivery = 0";
        break;

    case 'P_RWD': // Pending Ready Walkin
        $where .= " AND o.status = 3 AND o.is_home_delivery = 0";
        break;

    case 'C_WD': // Completed Walkin Delivery
        $where .= " AND o.status = 1 AND o.is_home_delivery = 0";
        break;
}

$timeRange = getSlotRange($slot);

if (!$timeRange) {
    exit("<tr><td colspan='5'>Invalid slot</td></tr>");
}

list($timeFrom, $timeTo) = $timeRange;


$sql = "
SELECT
    o.order_no,
    o.createdate,
    o.status,
    o.createtime,
    c.customer_name
FROM orders o
JOIN m_customer c ON c.customer_id=o.customer_id

WHERE
o.createtime BETWEEN '$timeFrom' AND '$timeTo'
AND o.createdate BETWEEN '$from'
                       AND '$to'

AND $where

ORDER BY o.createtime ASC
";

$res = $obj->executequery($sql);

/* STATUS LABEL */
$statusMap = [
    0 => 'Tagged',
    1 => 'Delivered',
    2 => 'Store',
    3 => 'Ready'
];

/* OUTPUT HTML ROWS */
if (empty($res)) {
    echo "<tr><td colspan='5' class='text-center'>No orders found</td></tr>";
    exit;
}

foreach ($res as $r) {

    $statusText = $statusMap[$r['status']] ?? 'Unknown';

    echo "
    <tr>
        <td class='p-2'>LAUNDRIXO</td>
        <td>
            <a href='b2c-order.php?order_no={$r['order_no']}'
               target='_blank'
               class='text-success text-decoration-none'>
               {$r['order_no']}
            </a>
        </td>
        <td>{$statusText}</td>
        <td>{$r['customer_name']}</td>
        <td>" . date('d M Y h:i A', strtotime($r['createdate'] . ' ' . $r['createtime'])) . "</td>
    </tr>";
}
