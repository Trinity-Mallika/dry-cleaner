<?php include_once("../adminsession.php");

$order_id = $obj->test_input($_REQUEST['order_id']);
$slno = 1;
$res = $obj->executequery("Select * from order_reschedule where order_id='$order_id' order by reschedule_id desc");
foreach ($res as $key) {
?>
    <tr>
        <td class="fw-semibold"><?= $slno++; ?>.</td>
        <td><?= $obj->dateformatindia($key['delivery_date']); ?></td>
        <td><span class="badge text-bg-primary"><?= $key['delivery_slot']; ?></span></td>
        <td><?= $key['reason']; ?></td>
    </tr>
<?php
} ?>