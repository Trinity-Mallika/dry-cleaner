<?php
include("../adminsession.php");

$customer_id = (int)($_POST['customer_id'] ?? 0);

if ($customer_id > 0) {

    $row = $obj->executequery("
        SELECT address_id, address_type, address
        FROM m_address
        WHERE customer_id='$customer_id'
        ORDER BY address_id DESC
        LIMIT 1
    ");

    if (!empty($row)) {
        echo json_encode([
            "status" => "found",
            "address_id" => $row[0]['address_id'],
            "address_type" => $row[0]['address_type'],
            "address" => $row[0]['address']
        ]);
    } else {
        echo json_encode(["status" => "not_found"]);
    }
} else {
    echo json_encode(["status" => "not_found"]);
}
