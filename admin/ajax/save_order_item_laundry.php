<?php
include_once("../../adminsession.php");

$order_item_laundry_id = (int)($_POST['order_item_laundry_id'] ?? 0);
$keyvalue = (int)($_POST['keyvalue'] ?? 0);
$item_id = (int)($_REQUEST['item_id'] ?? 0);
$data    = $_REQUEST['data'] ?? [];
$item_type_master_id = (int)($data['item_type_master_id'] ?? 0);
$from_laundry        = (int)($data['from_landry'] ?? 0);
$qty                 = (int)($data['qty'] ?? 0);
$comments            = $data['comments'] ?? [];

$comments_json = json_encode(array_map('intval', $comments));
if ($order_item_laundry_id == 0) {
    $obj->insert_record("order_item_laundry", [
        "item_id" => $item_id,
        "order_id" => $keyvalue,
        "item_type_master_id" => $item_type_master_id,
        "qty" => $qty,
        "comments" => $comments_json,
        "createdby" => $loginid,
        "ipaddress" => $ipaddress,
        "createdate" => $createdate,
        "sessionid" => $sessionid
    ]);
    echo "success";
} else {
    $obj->update_record(
        "order_item_laundry",
        ["order_item_laundry_id" => $order_item_laundry_id],
        [
            "item_type_master_id" => $item_type_master_id,
            "qty" => $qty,
            "order_id" => $keyvalue,
            "comments" => $comments_json,
            "createdby" => $loginid,
            "ipaddress" => $ipaddress,
            "lastupdated" => date('Y-m-d H:i:s')
        ]
    );
    echo "success";
}
