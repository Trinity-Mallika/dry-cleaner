<?php
include_once("../../adminsession.php");

$order_id = (int)($_REQUEST['order_id'] ?? 0);
$storage_label = (int)($_REQUEST['storage_label'] ?? 0);

$obj->update_record("orders", ["order_id" => $order_id], ["storage_label" => $storage_label, "status" => 3]);
echo "success";
