
<?php
include("../action.php");
$add_requirement_id = $_REQUEST['add_requirement_id'];
$obj->delete_record("add_requirement", array('add_requirement_id' => $add_requirement_id));
echo "Deleted successfully";
?>