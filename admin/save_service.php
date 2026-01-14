<?php
include("../action.php");

$item_type_master_id = isset($_REQUEST['item_type_master_id']) ? trim($_REQUEST['item_type_master_id']) : 0;
$add_service_id  = isset($_REQUEST['add_service_id']) ? trim($_REQUEST['add_service_id']) : 0;
$item_service_id = isset($_REQUEST['item_service_id']) ? trim($_REQUEST['item_service_id']) : '';
$rate = isset($_REQUEST['rate']) ? trim($_REQUEST['rate']) : '';

$count = $obj->getvalfield("add_service", "count(*)", "item_service_id='$item_service_id' and item_type_master_id='$item_type_master_id' and add_service_id!='$add_service_id'");
if ($count > 0) {
    echo 4;
    exit;
}
if ($add_service_id > 0) {

    $form_data = array(
        'item_type_master_id'    => $item_type_master_id,
        'item_service_id'   => $item_service_id,
        'rate'   => $rate,
        'ipaddress'   => $ipaddress,
        'lastupdated' => date('Y-m-d H:i:s')
    );
    $where = array('add_service_id' => $add_service_id);
    $obj->update_record("add_service", $where, $form_data);
    echo 2;
}


else {

    $form_data = array(
        'item_type_master_id'   => $item_type_master_id,
        'item_service_id'  => $item_service_id,
        'rate'  => $rate,
        'createdby'  => $loginid,
        'ipaddress'  => $ipaddress,
        'createdate' => date('Y-m-d H:i:s')
    );
    $obj->insert_record("add_service", $form_data);
    echo 1;
}
?>
