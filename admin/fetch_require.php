<?php
include("../action.php");

$item_id = isset($_POST['item_id']) ? trim($_POST['item_id']) : 0;

$rows = $obj->executequery(
    "SELECT * FROM add_requirement WHERE item_id='$item_id' ORDER BY add_requirement_id DESC"
);
?>

<table class="table table-sm table-bordered">
  <thead>
    <tr>
      <th>Sno.</th>
      <th>Requirement Name</th>
      <th>Rate</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
<?php
if(empty($rows)){
    echo "<tr><td colspan='5' class='text-center'>No requirement added</td></tr>";
} else {
    $slno = 1;
    foreach($rows as $r){
        $req_row = $obj->select_record("requirement_master", ["requirement_id"=>$r['requirement_id']]);
        $requirement = $req_row['requirement'];
        $rate = $r['rate'];
?>
    <tr>
      <td><?= $slno++ ?></td>
      <td><?= $requirement ?></td>
      <td><?= $rate ?></td>
      <td>
        <button class="btn btn-sm btn-danger" onclick="deleterequire('<?= $r['add_requirement_id'] ?>','<?= $item_id ?>')">
            <i class="bi bi-trash"></i>
        </button>
      </td>
    </tr>
<?php
    }
}
?>
  </tbody>
</table>
