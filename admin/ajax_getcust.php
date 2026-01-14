<?php include("../adminsession.php");

$mobile_no = $obj->test_input($_REQUEST['mobile_no']);

if ($mobile_no != '') {
    $customer_name = $obj->getvalfield("m_customer", "customer_name", "mobile='$mobile_no'");
    echo $customer_name;
} else {
    echo "not_found";
}
