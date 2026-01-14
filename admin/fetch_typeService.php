<?php
include("../action.php");

$item_type_master_id = $_POST['item_type_master_id'];

$rows = $obj->executequery("SELECT * FROM add_service WHERE item_type_master_id='$item_type_master_id' ORDER BY add_service_id desc");
?>

<table class='table table-sm table-bordered'>
  <thead>
    <tr>
      <th>Sno.</th>
      <th>Service Name</th>
      <th>Rate</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $slno = 1;
    foreach ($rows as $r) {
       $item_service_id = $obj->getvalfield(
        "add_service",
        "item_service_id",
        "add_service_id='$r[add_service_id]'"
    );

    $item_service_name = $obj->getvalfield(
        "item_service",
        "item_sname",
        "item_service_id='$item_service_id'"
    );

    $rate = $obj->getvalfield(
        "add_service",
        "rate",
        "add_service_id='$r[add_service_id]'"
    );
    ?>
      <tr>
        <td><?php echo  $slno++ ?></td>
       <td><?php echo $item_service_name; ?></td>
    <td><?php echo $rate; ?></td>

        <td>

         <button class='btn btn-sm btn-danger'
            onclick="deleteservice('<?php echo $r['add_service_id']; ?>', '<?php echo $item_type_master_id; ?>')">
            <i class='bi bi-trash'></i>
          </button>


        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>


</tbody>
</table>