<?php include_once("../../adminsession.php");
$keyvalue = $obj->test_input($_REQUEST['keyvalue']);
$order_item_id = $obj->test_input($_REQUEST['order_item_id']);
?>
<div>
    <?php $total_count = 0;
    $res = $obj->executequery("SELECT 
        oi.*,
        im.item_name,
        im.item_in,
        itm.item_type_master_name
    FROM order_item_laundry oi
    JOIN item_master im ON im.item_id = oi.item_id
    JOIN item_type_master itm ON itm.item_type_master_id = oi.item_type_master_id
    WHERE oi.order_id='$keyvalue' and order_item_id='$order_item_id'
    ORDER BY oi.order_item_laundry_id DESC");
    foreach ($res as $key) {
        $comments = $key['comments'];
        $comments = json_decode($key['comments'], true);

        if (is_array($comments) && !empty($comments)) {

            $ids = implode(',', array_map('intval', $comments));

            $rows = $obj->executequery(
                "SELECT comment 
         FROM comment_master 
         WHERE comment_id IN ($ids)"
            );

            foreach ($rows as $row) {
                $commentNames[] = $obj->shortCode($row['comment']);
            }
        }

        $qty = $key['qty'];
    ?>
        <!-- ITEM CARD -->
        <div class="bg-body-tertiary mb-2 p-2 rounded">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="d-flex align-items-center gap-2"> <i class="bi bi-pencil text-success cursor-pointer" onclick="handleLaundryEdit('<?= $key['order_item_laundry_id']; ?>')"></i> <i class="bi bi-x text-danger cursor-pointer" onclick="handleLaundryDel('<?= $key['order_item_laundry_id']; ?>')"></i> <strong class="fs-14"> <?= $key['item_name']; ?> <?php if ($key['item_id'] != 2) { ?> [<?= $key['item_type_master_name']; ?>] <?php } ?> </strong> </div>
                    <?php if (!empty($commentNames)): ?> <div class="small text-black"> Comments: <span class="fs-13"><?= implode(', ', $commentNames); ?></span> </div> <?php endif; ?>
                </div>
                <div class="text-end"> <strong> <?= $qty; ?></strong> </div>
            </div>
        </div>
    <?php
        $total_count += $qty;
    }

    ?>
    <input type="hidden" id="garment_count" value="<?= $total_count; ?>">
</div>