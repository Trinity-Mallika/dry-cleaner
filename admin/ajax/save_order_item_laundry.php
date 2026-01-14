<?php
include_once("../../adminsession.php");

$item_id = (int)($_REQUEST['item_id'] ?? 0);
$data    = $_REQUEST['data'] ?? [];

$item_type_master_id = (int)($data['item_type_master_id'] ?? 0);
$from_laundry        = (int)($data['from_landry'] ?? 0);
$qty                 = (int)($data['qty'] ?? 0);
$comments            = $data['comments'] ?? [];

$comments_json = json_encode(array_map('intval', $comments));

$obj->insert_record("order_item_laundry", [
    "item_id"             => $item_id,
    "item_type_master_id" => $item_type_master_id,
    "qty"                 => $qty,
    "comments"            => $comments_json,
    "createdby"           => $loginid,
    "ipaddress"           => $ipaddress,
    "createdate"          => $createdate,
    "sessionid"           => $sessionid
]);

echo "success";
