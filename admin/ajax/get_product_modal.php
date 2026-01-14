<?php
include_once("../../adminsession.php");

$mode = $_POST['mode'] ?? 'add';
$order_item_id = (int)($_POST['order_item_id'] ?? 0);
$from_landry = (int)($_POST['from_landry'] ?? 0);

$item_id = 0;
$item_type_master_id = 0;
$qty = 1;
$is_washing = 0;
$is_pressing = 0;
$selection = [];

/* ================= EDIT MODE ================= */
if ($mode === 'edit' && $order_item_id > 0) {

    $order = $obj->select_record("order_item", [
        "order_item_id" => $order_item_id
    ]);

    $item_id = $order['item_id'];
    $item_type_master_id = $order['item_type_master_id'];
    $qty = $order['qty'];
    $is_washing = $order['is_washing'];
    $is_pressing = $order['is_pressing'];

    $selection = json_decode($order['selection_json'], true);
}
/* ================= ADD MODE ================= */ else {
    $item_id = $obj->test_input($_POST['item_id'] ?? '');
    $item_type_master_id = $obj->test_input($_POST['item_type_master_id'] ?? '');
}

$item = $obj->select_record("item_master", ["item_id" => $item_id]);
$item_name = $item['item_name'];
$item_in = $item['item_in'];

$selectedReqIds = array_column($selection['requirements'] ?? [], 'id');
$selectedCommentIds = $selection['comments'] ?? [];
?>

<div class="modal-header">
    <h1 class="modal-title fs-5"><?= $item_name; ?></h1>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <?php if ($item_id != 2) { ?>
        <!-- ITEM TYPE -->
        <div class="mb-3">
            <label class="fw-semibold mb-2 d-block">Select Item Type</label>
            <div id="modal-item-types">
                <?php
                $types = $obj->executequery("
                SELECT item_type_master_id, item_type_master_name
                FROM item_type_master
                WHERE item_id = '$item_id'
            ");
                foreach ($types as $type):
                    $active = ($type['item_type_master_id'] == $item_type_master_id);
                ?>
                    <a href="javascript:void(0)"
                        class="badge p-2 mb-1 modal-item-type text-decoration-none <?= $active ? 'bg-success text-white active' : 'bg-dark-subtle text-black' ?>"
                        data-type="<?= $type['item_type_master_id']; ?>"
                        onclick="setModalItemType(this)">
                        <?= $type['item_type_master_name']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php } ?>
    <!-- SERVICES -->
    <div class="mb-3">
        <label class="fw-semibold mb-2 d-block">Services <span class="text-danger">*</span></label>
        <div id="modal-services"></div>
    </div>
    <?php if ($item_id != 2) { ?>
        <!-- REQUIREMENTS -->
        <div class="mb-3">
            <label class="fw-semibold mb-2 d-block">Requirements</label>
            <div id="modal-requirements">
                <?php
                $reqs = $obj->executequery("
                SELECT A.requirement_id, A.rate, B.requirement
                FROM add_requirement A
                JOIN requirement_master B ON A.requirement_id=B.requirement_id
                WHERE A.item_id='$item_id' 
            ");
                foreach ($reqs as $r):
                    $sel = in_array($r['requirement_id'], $selectedReqIds);
                ?>
                    <a href="javascript:void(0)"
                        class="badge p-2 mb-1 modal-requirement text-decoration-none <?= $sel ? 'bg-success text-white selected' : 'bg-dark-subtle text-black' ?>"
                        data-id="<?= $r['requirement_id']; ?>"
                        data-rate="<?= $r['rate']; ?>"
                        onclick="toggleService(this)">
                        <?= $r['requirement']; ?>
                        [₹ <?= $r['rate']; ?> / <?= $item_in == 1 ? 'PR' : 'PC'; ?>]
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- COMMENTS -->
        <div class="mb-3">
            <label class="fw-semibold d-block">Comments</label>
            <small class="fw-11 text-secondary">Customer will be notified for these comments and it is advised to wait for atleast 30 min to start processing this order after tagging</small>
            <div id="modal-comments" class="mt-2">
                <?php
                $comments = $obj->executequery("
                SELECT A.comment_id, B.comment
                FROM item_comment_map A
                JOIN comment_master B ON A.comment_id=B.comment_id
                WHERE A.item_id='$item_id'
            ");
                foreach ($comments as $c):
                    $sel = in_array($c['comment_id'], $selectedCommentIds);
                ?>
                    <a href="javascript:void(0)"
                        class="badge p-2 mb-1 modal-comment text-decoration-none <?= $sel ? 'bg-success text-white selected' : 'bg-dark-subtle text-black' ?>"
                        data-id="<?= $c['comment_id']; ?>"
                        onclick="toggleService(this)">
                        <?= $c['comment']; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="mb-3 d-none" id="addon-wrapper">
            <label class="fw-semibold mb-2 d-block" id="addonlabel">
                Wash & Fold Addons
            </label>

            <div id="modal-addons">

                <div class="form-check mb-2">
                    <input
                        type="checkbox"
                        class="form-check-input addon-check"
                        id="addon_1"
                        value="1"
                        data-rate="5"
                        data-name="Antiviral Cleaning">
                    <label for="addon_1" class="form-check-label">
                        Antiviral Cleaning | ₹5/kg
                    </label>
                </div>

                <div class="form-check mb-2">
                    <input
                        type="checkbox"
                        class="form-check-input addon-check"
                        id="addon_2"
                        value="2"
                        data-rate="5"
                        data-name="Fabric Softener">
                    <label for="addon_2" class="form-check-label">
                        Fabric Softener | ₹5/kg
                    </label>
                </div>

            </div>


        </div>
        <a href="javascript:void(0)" onclick="show_garment_modal();" class="btn bg-body-secondary w-100">Add Garment</a>

    <?php } ?>
</div>

<!-- FOOTER -->
<div class="text-center mb-3 mt-2">

    <div class="count-box">
        <button class="count-btn" data-action="minus">-</button>
        <input type="text"
            class="qty-value form-control d-inline-block text-center"
            value="<?= $qty ?>"
            style="width:70px"
            inputmode="numeric"
            pattern="[0-9]*"
            oninput="onlyDigits(this),syncQty(this)">
        <span> <?php if ($item_id != 2) { ?>
                <?= $item_in == 1 ? 'PR' : 'PC'; ?>
            <?php } else { ?>
                Kg
            <?php } ?>
        </span>
        <input type="hidden" id="from_landry" value="<?= $from_landry; ?>">
        <button class="count-btn" data-action="plus">+</button>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input process-area" type="checkbox" id="area_washing" value="washing" <?= $is_washing ? 'checked' : '' ?>>
        <label class="form-check-label">Washing</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input process-area" type="checkbox" id="area_pressing" value="pressing" <?= $is_pressing ? 'checked' : '' ?>>
        <label class="form-check-label">Pressing</label>
    </div>

    <a href="javascript:void(0)"
        id="addItemBtn"
        class="btn btn-success w-75"
        onclick="<?= $mode === 'edit'
                        ? "updateItemDetails($order_item_id)"
                        : "saveItemDetails($item_id)" ?>">
        <?= $mode === 'edit' ? 'UPDATE' : 'ADD' ?>
    </a>

</div>

<script>
    get_service(
        '<?= $item_type_master_id ?>',
        '<?= $item_id ?>',
        null,
        'modal'
    );
</script>
<script>
    window.__EDIT_SELECTION__ = <?= json_encode($selection['services'] ?? []); ?>;
</script>