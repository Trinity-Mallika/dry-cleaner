<?php
include_once("../../adminsession.php");

$order_item_id = (int)($_POST['order_item_id'] ?? 0);
$data = $_POST['data'] ?? [];
$item_type_master_id = (int)$data['item_type_master_id'];
$qty = isset($data['qty']) ? (float)$data['qty'] : 0;
$areas = $data['areas'] ?? [];
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
$is_washing  = in_array('washing', $areas) ? 1 : 0;
$is_pressing = in_array('pressing', $areas) ? 1 : 0;

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

$obj->update_record(
    "order_item",
    ["order_item_id" => $order_item_id],
    [
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
        "lastupdated"         => date('Y-m-d H:i:s')
    ]
);

echo "success";
