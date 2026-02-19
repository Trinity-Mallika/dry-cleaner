<?php include_once("../../adminsession.php");
$keyvalue = $obj->test_input($_REQUEST['keyvalue']);
$grossTotal = 0;
$totalCount = 0;
ob_start();
?>
<div style="overflow:scroll;height: 280px;">
    <?php
    $res = $obj->executequery("
    SELECT 
        oi.*,
        im.item_name,
        im.item_in
    FROM order_item oi
    JOIN item_master im ON im.item_id = oi.item_id
    WHERE oi.order_id='$keyvalue'
    ORDER BY oi.order_item_id DESC
");
    if (count($res) > 0) {
        foreach ($res as $key) {
            $item_type_master_name = $obj->getvalfield("item_type_master", "item_type_master_name", "item_type_master_id='{$key['item_type_master_id']}'");
            $grossTotal += $key['total_amount'];

            $selection = json_decode($key['selection_json'], true);

            $serviceNames = [];
            $addonNames = [];
            $reqNames = [];
            $commentNames = [];
            $LaundryItems = [];

            if ((int)$key['item_id'] === 2) {
                /* ===== LAUNDRY ===== */
                $services = [];
                $Laundrycomment = [];
                if (!empty($selection['service']['id'])) {
                    $services[] = $selection['service'];
                } elseif (!empty($selection['service'][0]['id'])) {
                    $services = $selection['service'];
                }

                foreach ($services as $srv) {

                    if (empty($srv['id'])) continue;

                    $srvId = (int)$srv['id'];

                    $item_service_id = $obj->getvalfield("add_service", "item_service_id", "add_service_id='$srvId'");
                    if (!$item_service_id) continue;

                    $name = $obj->getvalfield("item_service", "item_sname", "item_service_id='$item_service_id'");
                    if ($name) $serviceNames[] = $obj->shortCode($name);
                }


                foreach ($selection['addons'] ?? [] as $ad) {
                    if (!empty($ad['name'])) {
                        $addonNames[] = $obj->shortCode($ad['name']);
                    }
                }

                $serviceWithAddons = array_merge($serviceNames, $addonNames);
                $serviceText = implode(' + ', $serviceWithAddons);

                $LaundryItems = [];

                $lanitms = $obj->executequery("SELECT 
        im.item_name,
        itm.item_type_master_name,
        oil.qty,
        oil.comments
    FROM order_item_laundry oil
    JOIN item_master im ON im.item_id = oil.item_id
    JOIN item_type_master itm ON itm.item_type_master_id = oil.item_type_master_id
    WHERE oil.order_id = '$keyvalue' and oil.order_item_id='{$key['order_item_id']}'
");

                foreach ($lanitms as $row) {
                    $totalCount += $row['qty'];

                    $itemText = $row['item_name'] . " [" . $row['item_type_master_name'] . "] X " . $row['qty'];

                    $Laundrycomment = [];
                    $commentIds = json_decode($row['comments'], true);
                    if (!is_array($commentIds)) $commentIds = [];

                    foreach ($commentIds as $cid) {
                        $cid = (int)$cid;
                        $comment = $obj->getvalfield("comment_master", "comment", "comment_id='$cid'");
                        if ($comment) $Laundrycomment[] = $obj->shortCode($comment);
                    }

                    $commentText = "";
                    if (!empty($Laundrycomment)) {
                        $commentText = " [Comments: " . implode(', ', $Laundrycomment) . "]";
                    }

                    $LaundryItems[] = $itemText . $commentText;
                }
            } else {
                /* ===== NORMAL ITEMS ===== */
                $totalCount += $key['qty'];

                foreach ($selection['services'] ?? [] as $srv) {

                    if (empty($srv['id'])) continue;

                    $srvId = (int)$srv['id'];

                    $item_service_id = $obj->getvalfield("add_service", "item_service_id", "add_service_id='$srvId'");

                    if (!$item_service_id) continue;

                    $name = $obj->getvalfield("item_service", "item_sname", "item_service_id='$item_service_id'");

                    if ($name) $serviceNames[] = $obj->shortCode($name);
                }

                $serviceText = implode(' , ', $serviceNames);


                foreach ($selection['requirements'] ?? [] as $req) {
                    $reqName = $obj->getvalfield(
                        "requirement_master",
                        "requirement",
                        "requirement_id='{$req['id']}'"
                    );
                    if ($reqName) {
                        $unit = ($key['item_in'] == 1) ? 'PR' : 'PC';
                        $reqNames[] = $obj->shortCode($reqName) . "[$unit]";
                    }
                }

                foreach ($selection['comments'] ?? [] as $cid) {
                    $comment = $obj->getvalfield(
                        "comment_master",
                        "comment",
                        "comment_id='$cid'"
                    );
                    if ($comment) {
                        $commentNames[] = $obj->shortCode($comment);
                    }
                }
            }

            $qty = $key['qty'];
            $unitPrice = number_format($key['total_amount'] / $qty, 2);
            $total = number_format($key['total_amount'], 2);
    ?>
            <!-- ITEM CARD -->
            <div class="bg-body-tertiary mb-2 p-2 rounded">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="d-flex align-items-center gap-2"> <i class="bi bi-pencil text-success cursor-pointer" onclick="editOrderItem('<?= $key['order_item_id']; ?>','<?= $key['item_id']; ?>')"></i>
                            <i class="bi bi-x text-danger cursor-pointer" onclick="deleteOrderItem('<?= $key['order_item_id']; ?>')"></i> <strong class="fs-14"> <?= $key['item_name']; ?> <?php if ($key['item_id'] != 2) { ?> [<?= $item_type_master_name; ?>] <?php } ?> </strong>
                        </div>
                        <div class="small text-black mt-1">
                            Services: <span class="fs-13"><?= $serviceText; ?></span>
                        </div>
                        <?php if (!empty($LaundryItems)) { ?>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <?php foreach ($LaundryItems as $txt) { ?>
                                    <span class="badge bg-secondary-subtle mt-2 text-light-emphasis">
                                        <?= $txt; ?>
                                    </span>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if (!empty($commentNames)): ?>
                            <div class="small text-black">
                                Comments: <span class="fs-13"><?= implode(', ', $commentNames); ?></span>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="text-end"> <strong> <?php if ((int) $key['item_id'] === 2): ?> <?= $qty; ?> Kg × <?= $unitPrice; ?> = <?= $total; ?> <?php else: ?> <?= $qty; ?> × <?= $unitPrice; ?> = <?= $total; ?> <?php endif; ?> </strong> <?php if (!empty($reqNames)): ?> <div class="small text-black mt-1"> Req: <span class="fs-13"><?= implode(', ', $reqNames); ?></span> </div> <?php endif; ?> </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3"> <span class="badge p-2 border text-secondary"> <i class="bi fs-6 <?= $key['is_washing'] ? 'bi-check2 text-success' : 'bi-x text-danger' ?>"></i> Washing Area </span> <span class="badge p-2 border text-secondary"> <i class="bi fs-6 <?= $key['is_pressing'] ? 'bi-check2 text-success' : 'bi-x text-danger' ?>"></i> Pressing Area </span> </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="bg-body-tertiary mb-2 p-3 rounded text-center">
            <i class="bi bi-bag-x fs-3 text-secondary"></i>
            <div class="fw-semibold mt-2">No Products Added</div>
            <div class="small text-muted">Add laundry or garments to create an order</div>
        </div>
    <?php
    }
    ?>
</div>
<?php
$html = ob_get_clean();

echo json_encode([
    "html" => $html,
    "summary" => [
        "gross" => round($grossTotal, 2),
        "count" => (int)$totalCount
    ]
]);
