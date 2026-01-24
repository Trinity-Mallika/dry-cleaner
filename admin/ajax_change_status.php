<?php
include("../adminsession.php");

$order_id = (int)($_POST['order_id'] ?? 0);
$status   = (int)($_POST['status'] ?? -1);

if ($order_id <= 0) {
    echo "Invalid order";
    exit;
}

$valid = [0, 1, 2, 3];
if (!in_array($status, $valid)) {
    echo "Invalid status";
    exit;
}

$obj->update_record(
    "orders",
    ["order_id" => $order_id],
    ["status" => $status]
);

echo "success";
