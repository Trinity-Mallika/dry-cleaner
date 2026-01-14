<?php
include("../action.php");

$item_id = isset($_REQUEST['item_id']) ? trim($_REQUEST['item_id']) : 0;
$add_requirement_id  = isset($_REQUEST['add_requirement_id']) ? trim($_REQUEST['add_requirement_id']) : 0;
$requirement_id = isset($_REQUEST['requirement_id']) ? trim($_REQUEST['requirement_id']) : '';
$rate = isset($_REQUEST['rate']) ? trim($_REQUEST['rate']) : '';

$count = $obj->getvalfield("add_requirement", "count(*)", "requirement_id='$requirement_id' and item_id='$item_id' and add_requirement_id!='$add_requirement_id'");
if ($count > 0) {
    echo 4;
    exit;
}
if ($add_requirement_id > 0) {

    $form_data = array(
        'item_id'    => $item_id,
        'requirement_id'   => $requirement_id,
        'rate'   => $rate,
        'ipaddress'   => $ipaddress,
        'lastupdated' => date('Y-m-d H:i:s')
    );
    $where = array('add_requirement' => $add_requirement_id);
    $obj->update_record("add_requirement", $where, $form_data);
    echo 2;
}


else {

    $form_data = array(
        'item_id'   => $item_id,
        'requirement_id'  => $requirement_id,
        'rate'  => $rate,
        'createdby'  => $loginid,
        'ipaddress'  => $ipaddress,
        'createdate' => date('Y-m-d H:i:s')
    );
    $obj->insert_record("add_requirement", $form_data);
    echo 1;
}
?>
