<?php
include_once("../../adminsession.php");

$item_id = (int)($_REQUEST['item_id'] ?? 0);
$data    = $_REQUEST['data'] ?? [];
$item_type_master_id = (int)($data['item_type_master_id'] ?? 0);
$qty = max(1, (int)($data['qty'] ?? 1)); // pcs OR kg
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

    // total = (service + addons) × KG
    $service_total = ($service_rate + $addon_total) * $qty;

    $selection = [
        "service" => [
            "id" => (int)$service['id'],
            "rate" => $service_rate
        ],
        "addons" => $addons
    ];

    $obj->insert_record("order_item", [
        "item_id"             => 2,
        "item_type_master_id" => 1,
        "qty"                 => $qty, // KG
        "selection_json"      => json_encode($selection, JSON_UNESCAPED_UNICODE),
        "service_total"       => $service_total,
        "requirement_total"   => 0,
        "total_amount"        => $service_total,
        "is_washing"          => $is_washing,
        "is_pressing"         => $is_pressing,
        "createdby"           => $loginid,
        "ipaddress"           => $ipaddress,
        "createdate"          => $createdate,
        "sessionid"           => $sessionid
    ]);

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

$obj->insert_record("order_item", [
    "item_id"             => $item_id,
    "item_type_master_id" => $item_type_master_id,
    "qty"                 => $qty,
    "selection_json"      => json_encode($selection, JSON_UNESCAPED_UNICODE),
    "service_total"       => $service_total,
    "requirement_total"   => $requirement_total,
    "total_amount"        => $total_amount,
    "is_washing"          => $is_washing,
    "is_pressing"         => $is_pressing,
    "createdby"           => $loginid,
    "ipaddress"           => $ipaddress,
    "createdate"          => $createdate,
    "sessionid"           => $sessionid
]);

echo "success";
