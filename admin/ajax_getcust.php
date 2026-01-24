<?php
include("../adminsession.php");

$mobile_no = $obj->test_input($_POST['mobile_no'] ?? '');

if ($mobile_no != '') {

    $row = $obj->executequery("
        SELECT customer_id, customer_name
        FROM m_customer
        WHERE mobile='$mobile_no'
        LIMIT 1
    ");

    if (!empty($row)) {
        echo json_encode([
            "status" => "found",
            "customer_id" => $row[0]['customer_id'],
            "customer_name" => $row[0]['customer_name']
        ]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }
} else {
    echo json_encode(["status" => "not_found"]);
}
