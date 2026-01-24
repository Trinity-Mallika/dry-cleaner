<?php
include_once("../../adminsession.php");

$id = $_REQUEST['id'] ?? '1';
$activeLetter = $_REQUEST['alpha'] ?? 'ALL';

$where = "WHERE 1=1";
if ($activeLetter !== 'ALL') {
    $activeLetter = $obj->test_input($activeLetter);
    $where .= " AND item_name LIKE '$activeLetter%'";
}

// laundry by weight
if ($id == '2') {
    $where .= " AND item_id !=2";
}

$res = $obj->executequery("
    SELECT * 
    FROM item_master 
    $where 
    ORDER BY item_id ASC
");

$lettersRaw = $obj->executequery("
    SELECT DISTINCT UPPER(LEFT(item_name,1)) AS letter
    FROM item_master
");

$availableLetters = array_column($lettersRaw, 'letter');


$autoCalled = false;
if ($id == 1) {
?>
    <div class="d-flex flex-wrap gap-2 mb-3" id="alphaBar">
        <?php foreach (range('A', 'Z') as $char):
            $isAvailable = in_array($char, $availableLetters);
            $isActive = ($char == $activeLetter);
        ?>
            <a href="javascript:void(0)" onclick="loadProductsByAlpha('<?= $char ?>')"
                class="d-flex align-items-center justify-content-center fw-semibold text-decoration-none rounded
           <?= $isActive ? 'bg-success text-white' : ($isAvailable ? 'text-success border' : 'text-secondary border') ?>"
                style="width:28px;height:28px;font-size:13px;
           <?= !$isAvailable ? 'pointer-events:none;opacity:.4;' : '' ?>"
                <?= $isAvailable ? "onclick=\"loadProductsByAlpha('$char')\"" : '' ?>>
                <?= $char ?>
            </a>
        <?php endforeach; ?>
        <?php if ($activeLetter != 'ALL') { ?>
            <a href="javascript:void(0)" onclick="loadProductsByAlpha('ALL')"
                class="d-flex align-items-center justify-content-center fw-semibold text-decoration-none rounded text-success border"
                style="width:28px;height:28px;font-size:13px;"
                onclick="loadProductsByAlpha('ALL')">
                ALL
            </a>
        <?php } ?>
    </div>


    <div class="input-group mb-3">
        <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
        </span>
        <input type="text" id="itemSearch" class="form-control border-start-0" onkeyup="itemSearch()" placeholder="Search products">
    </div>

    <div id="itemList" style="overflow:auto;height:620px;" class="bg-body-secondary p-1">
        <?php foreach ($res as $key): ?>
            <div class="card mb-3 shadow-sm rounded-1 border-0 item-card" data-name="<?= strtolower($key['item_name']); ?>">
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-lg-9 col-12">
                            <h6 class="mb-0"><?= $key['item_name']; ?></h6>
                            <?php if ($key['item_id'] != 2) { ?>
                                <div class="mt-2">
                                    <?php
                                    $item_types = $obj->executequery("
                                    SELECT item_type_master_id,item_type_master_name
                                    FROM item_type_master
                                    WHERE item_id='{$key['item_id']}'
                                ");

                                    $i = 0;
                                    foreach ($item_types as $type):
                                    ?>
                                        <a href="javascript:void(0)" class="badge item-type rounded-1 fs-10 p-1 text-decoration-none bg-dark-subtle text-black"
                                            data-item="<?= $key['item_id']; ?>"
                                            data-type="<?= $type['item_type_master_id']; ?>"
                                            onclick="setActiveType(this)">
                                            <?= $type['item_type_master_name']; ?>
                                        </a>
                                    <?php $i++;
                                    endforeach; ?>
                                </div>
                            <?php } ?>

                            <div class="d-flex flex-wrap gap-2 mt-2 item-service">

                            </div>
                        </div>
                        <div class="col-lg-3 col-3 d-flex justify-content-center align-items-center text-center">
                            <button onclick="show_modal('<?= $key['item_id']; ?>', this)"
                                class="btn btn-success btn-sm w-100">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
<?php } else { ?>
    <div class="modal-header">
        <div class="container-fluid bg-white">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <button class="btn btn-light text-danger fs-4" onclick="close_modal_garment();">×</button>
                <input type="text" class="form-control w-25" placeholder="Search garments" onkeyup="itemSearch()">
                <!-- A–Z FILTER -->
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach (range('A', 'Z') as $char):
                        $isAvailable = in_array($char, $availableLetters);
                        $isActive = ($char === $activeLetter);
                    ?>
                        <span
                            class="<?= $isActive
                                        ? 'bg-success text-white'
                                        : ($isAvailable ? 'text-success' : 'text-secondary') ?>
      fw-semibold small cursor-pointer alpha-filter"
                            data-alpha="<?= $char ?>">
                            <?= $char ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- CATEGORY TABS -->
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Men</span>
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Women</span>
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Woolen</span>
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Kids</span>
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Accessories</span>
                <span class="badge rounded-pill bg-light text-dark px-3 cursor-pointer">Home & Living</span>
            </div>

        </div>
    </div>

    <div class="modal-body">
        <div class="container-fluid mt-4">
            <div class="row g-3">
                <?php foreach ($res as $key): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 laundry-card"
                        data-name="<?= strtolower($key['item_name']); ?>"
                        data-alpha="<?= strtoupper(substr($key['item_name'], 0, 1)); ?>">

                        <div class="card h-100 shadow-sm border">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    <h6 class="fw-bold fs-13"><?= $key['item_name']; ?></h6>
                                    <div class="d-flex flex-wrap gap-1 mt-2">
                                        <?php
                                        $item_types = $obj->executequery("
            SELECT item_type_master_id,item_type_master_name
            FROM item_type_master
            WHERE item_id='{$key['item_id']}'");
                                        $i = 0;
                                        foreach ($item_types as $type):
                                            $isFirst = ($i === 0);
                                        ?>
                                            <span class="badge item-type <?= $isFirst ? 'bg-success-subtle text-success active' : 'bg-dark-subtle text-black' ?>" data-item="<?= $key['item_id']; ?>"
                                                data-type="<?= $type['item_type_master_id']; ?>" onclick="setActiveTypeLaundry(this)"><?= $type['item_type_master_name']; ?></span>
                                        <?php $i++;
                                        endforeach; ?>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button
                                        onclick="handleLaundryAdd('<?= $key['item_id']; ?>',this)"
                                        class="btn btn-success btn-sm px-3">
                                        Add
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php } ?>