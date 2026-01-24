<?php
include_once("../../adminsession.php");

$item_id = $obj->test_input($_REQUEST['item_id'] ?? '');
$item_type_master_id = $obj->test_input($_REQUEST['item_type_master_id'] ?? '');
$context = $_REQUEST['context'] ?? 'card';
$item_type_master_id = ($item_id == 2) ? 1 : $item_type_master_id;
$item_in = $obj->getvalfield(
    "item_master",
    "item_in",
    "item_id='$item_id'"
);

$item_services = $obj->executequery("SELECT 
        A.add_service_id,
        A.rate,
        B.item_sname,
        B.is_pressing,
        B.is_washing
    FROM add_service A
    LEFT JOIN item_service B 
        ON A.item_service_id = B.item_service_id
    WHERE A.item_type_master_id='$item_type_master_id'
");
?>

<?php foreach ($item_services as $srv): ?>

    <?php if ($context === 'modal'): ?>
        <!-- MODAL : interactive -->
        <a href="javascript:void(0)"
            class="badge p-2 cursor bg-dark-subtle text-black text-decoration-none rounded-1 mb-1 service-badge"
            data-id="<?= $srv['add_service_id']; ?>"
            data-rate="<?= $srv['rate']; ?>"
            data-is_pressing="<?= $srv['is_pressing']; ?>"
            data-is_washing="<?= $srv['is_washing']; ?>"
            onclick="toggleService(this,<?= $item_id; ?>)">
            <?= $srv['item_sname']; ?>[
            ₹ <?= $srv['rate']; ?> / <?= ($item_in == 1) ? 'PR' : 'PC'; ?>]
        </a>


    <?php else: ?>
        <!-- CARD : display only -->
        <div class="service-item">
            <h6 class="fs-13 mb-0">
                <?= $srv['item_sname']; ?><br>
                <small class="fs-12 fw-bold text-black">
                    ₹ <?= $srv['rate']; ?> / <?= ($item_in == 1) ? 'PR' : 'PC'; ?>
                </small>
            </h6>
        </div>
    <?php endif; ?>

<?php endforeach; ?>