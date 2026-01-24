<?php include_once("../adminsession.php");

$order_id = $obj->test_input($_REQUEST['order_id']);
$delivery_date = $obj->test_input($_REQUEST['delivery_date']);
$delivery_slot = $obj->test_input($_REQUEST['delivery_slot']);
$reason = $obj->test_input($_REQUEST['reason']);

if ($order_id > 0) {
    $obj->insert_record("order_reschedule", [
        "order_id"  => $order_id,
        "delivery_date"     => $delivery_date,
        "delivery_slot"     => $delivery_slot,
        "reason"            => $reason,
        "ipaddress"         => $ipaddress,
        "createdby"         => $loginid,
        "createdate"        => $createdate,
    ]);

    $obj->update_record("orders", [
        "order_id"  => $order_id
    ], ["delivery_date" => $delivery_date, "delivery_slot" => $delivery_slot]);
}
