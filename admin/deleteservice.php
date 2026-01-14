
<?php
include("../action.php");
$add_service_id = $_REQUEST['add_service_id'];
$obj->delete_record("add_service", array('add_service_id' => $add_service_id));
echo "Deleted successfully";
?>