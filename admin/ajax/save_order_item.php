<?php
include_once("../../adminsession.php");

$item_id = (int)($_REQUEST['item_id'] ?? 0);
$order_id = (int)($_REQUEST['keyvalue'] ?? 0);
$data    = $_REQUEST['data'] ?? [];
$item_type_master_id = (int)($data['item_type_master_id'] ?? 0);
$qty = isset($data['qty']) ? (float)$data['qty'] : 0;
$areas = $data['areas'] ?? [];

$is_washing  = in_array('washing', $areas) ? 1 : 0;
$is_pressing = in_array('pressing', $areas) ? 1 : 0;

/* ========================= LAUNDRY BY ITEM ========================= */
if ($item_id === 2) {

    if (empty($data['services'])) {
        echo "Service required";
        exit;
    }

    // only ONE main service
    $service = $data['services'][0];
    $service_rate = (float)$service['rate'];

    // addons (if sent separately)
    $addon_total = 0;
    $addons = [];

    foreach ($data['addons'] ?? [] as $ad) {
        $rate = (float)$ad['rate'];
        $addon_total += $rate;

        $addons[] = [
            "id" => (int)$ad['id'],
            "name" => $ad['name'],
            "rate" => $rate
        ];
    }

    $selection = [
        "service" => [
            "id" => (int)$service['id'],
            "rate" => $service_rate
        ],
        "addons" => $addons
    ];


    // total = (service + addons) × KG
    $service_total = $service_rate + $addon_total;
    $total_amount = ($service_rate + $addon_total) * $qty;
    $gst_percent     = 18;
    $taxable_amount  = round(($total_amount * 100) / (100 + $gst_percent), 2);
    $gst_amount      = round($total_amount - $taxable_amount, 2);
    $cgst_amount = round($gst_amount / 2, 2);
    $sgst_amount = round($gst_amount / 2, 2);

    $lastid =  $obj->insert_record_lastid("order_item", [
        "item_id"             => 2,
        "item_type_master_id" => 1,
        "qty"                 => $qty, // KG
        "selection_json"      => json_encode($selection, JSON_UNESCAPED_UNICODE),
        "order_id"       => $order_id,
        "service_total"       => $service_total,
        "requirement_total"   => 0,
        "taxable_amount"      => $taxable_amount,
        "cgst_amount"     => $cgst_amount,
        "sgst_amount"     => $sgst_amount,
        "gst_amount"          => $gst_amount,
        "gst_percent"         => $gst_percent,
        "total_amount"        => $total_amount,
        "is_washing"          => $is_washing,
        "is_pressing"         => $is_pressing,
        "createdby"           => $loginid,
        "ipaddress"           => $ipaddress,
        "createdate"          => $createdate,
        "sessionid"           => $sessionid
    ]);

    $obj->update_record("order_item_laundry", ["order_id" => $order_id, "createdby" => $loginid, 'order_item_id' => 0], ["order_item_id" => $lastid]);

    echo "success";
    exit;
}

/* ========================= NORMAL ITEMS ========================= */

$services = [];
$service_total = 0;

foreach ($data['services'] as $srv) {
    $rate = (float)$srv['rate'];
    $service_total += $rate;

    $services[] = [
        "id" => (int)$srv['id'],
        "rate" => $rate
    ];
}

$service_total *= $qty;

$requirements = [];
$requirement_total = 0;

foreach ($data['requirements'] ?? [] as $req) {
    $rate = (float)($req['rate'] ?? 0);
    $requirement_total += $rate;

    $requirements[] = [
        "id" => (int)$req['id'],
        "rate" => $rate
    ];
}

$requirement_total *= $qty;

$selection = [
    "services"     => $services,
    "requirements" => $requirements,
    "comments"     => array_map('intval', $data['comments'] ?? [])
];

$total_amount = $service_total + $requirement_total;
$gst_percent     = 18;
$taxable_amount  = round(($total_amount * 100) / (100 + $gst_percent), 2);
$gst_amount      = round($total_amount - $taxable_amount, 2);
$cgst_amount = round($gst_amount / 2, 2);
$sgst_amount = round($gst_amount / 2, 2);

$obj->insert_record("order_item", [
    "item_id"             => $item_id,
    "order_id" => $order_id,
    "item_type_master_id" => $item_type_master_id,
    "qty"                 => $qty,
    "selection_json"      => json_encode($selection, JSON_UNESCAPED_UNICODE),
    "service_total"       => $service_total,
    "requirement_total"   => $requirement_total,
    "taxable_amount"      => $taxable_amount,
    "cgst_amount"     => $cgst_amount,
    "sgst_amount"     => $sgst_amount,
    "gst_amount"          => $gst_amount,
    "gst_percent"         => $gst_percent,
    "total_amount"        => $total_amount,
    "is_washing"          => $is_washing,
    "is_pressing"         => $is_pressing,
    "createdby"           => $loginid,
    "ipaddress"           => $ipaddress,
    "createdate"          => $createdate,
    "sessionid"           => $sessionid
]);

echo "success";
