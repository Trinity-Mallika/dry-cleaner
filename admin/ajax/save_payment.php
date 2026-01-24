<?php
include("../../adminsession.php");
header('Content-Type: application/json');

$order_id = intval($_POST['order_id'] ?? 0);
$paid_amt = floatval($_POST['paid_amt'] ?? 0);
$pay_mode = trim($_POST['pay_mode'] ?? 'Cash');

$final_total = $obj->getvalfield("orders", "final_total", "order_id='$order_id'");
$finalTotal = round($final_total);
$customer_id = $obj->getvalfield("orders", "customer_id", "order_id='$order_id'");
$old_paid = $obj->getvalfield("payment", "sum(pay_amount)", "order_id='$order_id'");

$new_paid = $old_paid + $paid_amt;

if ($new_paid > $finalTotal) {
    echo json_encode(["status" => "error", "message" => "Payment exceeds invoice total"]);
    exit;
}

$new_due = $finalTotal - $new_paid;
$voucher_no = $obj->getcode("payment", "voucher_no", "1=1");

$obj->insert_record("payment", [
    "order_id"     => $order_id,
    "customer_id"     => $customer_id,
    "pay_amount"     => $paid_amt,
    "pay_type" => $pay_mode,
    "voucher_no" => $voucher_no,
    "pay_date"     => $createdate,
    "ipaddress"   => $ipaddress,
    "createdby"   => $loginid,
    "createdate"   => $createdate,
]);
if ($new_due == 0) {
    $obj->update_record("orders", ["order_id" => $order_id], ["pay_status" => 1]);
}

echo json_encode([
    "status"     => "success",
    "message"    => "Payment saved",
    "paid_total" => number_format($new_paid, 2, '.', ''),
    "due_total"  => number_format($new_due, 2, '.', '')
]);
