<?php include_once("../../adminsession.php");
$keyvalue = $obj->test_input($_REQUEST['keyvalue']);
?>
<form method="post">
    <div class="d-flex justify-content-between">
        <div class="mb-2">
            <label for="mobile_no" class="fw-bold">Mobile No.</label>
            <input
                type="tel"
                name="mobile_no"
                id="mobile_no"
                inputmode="numeric"
                maxlength="10"
                class="form-control"
                onchange="find_cust(this.value); setFinalBtn();">
        </div>
        <div class="mb-2">
            <label for="cust_name" class="fw-bold">Name</label>
            <input
                type="text"
                name="cust_name"
                id="cust_name"
                class="form-control"
                onchange="setFinalBtn();">
        </div>
    </div>
    <hr>
    <div style="overflow:scroll;height: 280px;">
        <?php
        $grossTotal = 0;
        $totalCount = 0;
        $res = $obj->executequery("
    SELECT 
        oi.*,
        im.item_name,
        im.item_in,
        itm.item_type_master_name
    FROM order_item oi
    JOIN item_master im ON im.item_id = oi.item_id
    JOIN item_type_master itm ON itm.item_type_master_id = oi.item_type_master_id where oi.order_id='$keyvalue'
    ORDER BY oi.order_item_id DESC
");
        if (count($res) > 0) {
            foreach ($res as $key) {

                $grossTotal += $key['total_amount'];
                $totalCount += $key['qty'];

                $selection = json_decode($key['selection_json'], true);

                $serviceNames = [];
                $addonNames = [];
                $reqNames = [];
                $commentNames = [];

                /* ===================== LAUNDRY (ITEM ID = 2) ===================== */
                if ((int) $key['item_id'] === 2) {

                    // main service
                    if (!empty($selection['service'])) {
                        $srv = $selection['service'];

                        $name = $obj->getvalfield(
                            "item_service",
                            "item_sname",
                            "item_service_id = (
                    SELECT item_service_id 
                    FROM add_service 
                    WHERE add_service_id = '{$srv['id']}'
                )"
                        );

                        $serviceNames[] = $obj->shortCode($name);
                    }

                    // addons
                    foreach ($selection['addons'] ?? [] as $ad) {
                        $addonNames[] = $obj->shortCode($ad['name']);
                    }
                }
                /* ===================== NORMAL ITEMS ===================== */ else {

                    foreach ($selection['services'] ?? [] as $srv) {

                        $name = $obj->getvalfield(
                            "item_service",
                            "item_sname",
                            "item_service_id = (
                    SELECT item_service_id 
                    FROM add_service 
                    WHERE add_service_id = '{$srv['id']}'
                )"
                        );

                        $serviceNames[] = $obj->shortCode($name);
                    }

                    foreach ($selection['requirements'] ?? [] as $req) {

                        $reqName = $obj->getvalfield(
                            "requirement_master",
                            "requirement",
                            "requirement_id='{$req['id']}'"
                        );

                        $unit = ($key['item_in'] == 1) ? 'PR' : 'PC';
                        $reqNames[] = $obj->shortCode($reqName) . "[$unit]";
                    }

                    foreach ($selection['comments'] ?? [] as $cid) {

                        $comment = $obj->getvalfield(
                            "comment_master",
                            "comment",
                            "comment_id='$cid'"
                        );

                        $commentNames[] = $obj->shortCode($comment);
                    }
                }

                $qty = $key['qty'];
                $unitPrice = number_format($key['total_amount'] / max(1, $qty), 2);
                $total = number_format($key['total_amount'], 2);
        ?>

                <div class="bg-body-tertiary mb-2 p-2 rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-pencil text-success cursor-pointer"
                                    onclick="editOrderItem('<?= $key['order_item_id']; ?>')"></i>

                                <i class="bi bi-x text-danger cursor-pointer"
                                    onclick="deleteOrderItem('<?= $key['order_item_id']; ?>')"></i>

                                <strong class="fs-14">
                                    <?= $key['item_name']; ?>
                                    <?php if ($key['item_id'] != 2) { ?>
                                        [<?= $key['item_type_master_name']; ?>]
                                    <?php } ?>
                                </strong>
                            </div>

                            <div class="small text-black mt-1">
                                Services:
                                <span class="fs-13"><?= implode(', ', $serviceNames); ?></span>
                            </div>

                            <?php if (!empty($addonNames)): ?>
                                <div class="small text-black">
                                    Addons:
                                    <span class="fs-13"><?= implode(', ', $addonNames); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($commentNames)): ?>
                                <div class="small text-black">
                                    Comments:
                                    <span class="fs-13"><?= implode(', ', $commentNames); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="text-end">
                            <strong>
                                <?php if ((int) $key['item_id'] === 2): ?>
                                    <?= $qty; ?> Kg × <?= $unitPrice; ?> = <?= $total; ?>
                                <?php else: ?>
                                    <?= $qty; ?> × <?= $unitPrice; ?> = <?= $total; ?>
                                <?php endif; ?>
                            </strong>

                            <?php if (!empty($reqNames)): ?>
                                <div class="small text-black mt-1">
                                    Req:
                                    <span class="fs-13"><?= implode(', ', $reqNames); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <span class="badge p-2 border text-secondary">
                            <i class="bi fs-6 <?= $key['is_washing'] ? 'bi-check2 text-success' : 'bi-x text-danger' ?>"></i>
                            Washing Area
                        </span>
                        <span class="badge p-2 border text-secondary">
                            <i class="bi fs-6 <?= $key['is_pressing'] ? 'bi-check2 text-success' : 'bi-x text-danger' ?>"></i>
                            Pressing Area
                        </span>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="bg-body-tertiary mb-2 p-3 rounded text-center">
                <i class="bi bi-bag-x fs-3 text-secondary"></i>
                <div class="fw-semibold mt-2">No Products Added</div>
                <div class="small text-muted">
                    Add laundry or garments to create an order
                </div>
            </div>

        <?php } ?>
        <script>
            window.ORDER_SUMMARY = {
                gross: <?= round($grossTotal, 2) ?>,
                count: <?= (int)$totalCount ?>
            };
        </script>
    </div>
    <hr>
    <div id="couponSection" class="text-center">
        <a href="javascript:void(0)"
            id="addCouponBtn"
            class="btn btn-sm bg-body-secondary"
            data-bs-toggle="modal"
            data-bs-target="#coupon">
            <i class="bi bi-ticket-perforated-fill"></i>&nbsp;Add Coupon
        </a>

        <div id="appliedCouponBox"
            class="d-none align-items-center gap-2 bg-success-subtle text-success p-2 rounded mt-2">

            <i class="bi bi-ticket-perforated-fill"></i>
            <strong id="appliedCouponName"></strong>

            <span class="badge bg-success">
                <span id="appliedCouponPercent"></span>% OFF
            </span>

            <i class="bi bi-x-circle-fill cursor-pointer text-danger"
                onclick="removeCoupon()"></i>
        </div>

    </div>

    <h6 class="text-secondary fw-bold mt-3">
        Gross Total <span class="float-end" id="grossTotal">0</span>
    </h6>

    <h6 class="text-secondary fw-bold">
        Discount Amount <span class="float-end" id="discountAmount">0</span>
    </h6>
    <h6 class="text-secondary fw-bold">Express Amount: <span class="float-end">0</span></h6>
    <h6 class="text-secondary fw-bold">Total Count: <span class="float-end" data-total-count>0pc</span></h6>
    <h6 class="fw-bold mt-3">
        Total Amount <span class="float-end" id="finalTotal">0</span>
    </h6>

    <hr>
    <!-- Delivery Date & Slot -->
    <div class="d-flex justify-content-between gap-3 mb-3">
        <div class="flex-fill">
            <label class="fw-bold">Delivery Date</label>
            <input type="date" class="form-control" name="delivery_date" id="delivery_date">
        </div>

        <div class="flex-fill">
            <label class="fw-bold">Delivery Timeslot</label>
            <select class="form-control" name="delivery_slot" id="delivery_slot">
                <option value="">Select Slot</option>
                <option>7 AM - 8 AM</option>
                <option>8 AM - 9 AM</option>
                <option>9 AM - 10 AM</option>
                <option>10 AM - 11 AM</option>
                <option>11 AM - 12 PM</option>
                <option>12 PM - 1 PM</option>
                <option>1 PM - 2 PM</option>
                <option>2 PM - 3 PM</option>
                <option>3 PM - 4 PM</option>
                <option>4 PM - 5 PM</option>
                <option>5 PM - 6 PM</option>
                <option>6 PM - 7 PM</option>
                <option>7 PM - 8 PM</option>
                <option>8 PM - 9 PM</option>
                <option>9 PM - 10 PM</option>
            </select>
        </div>
    </div>

    <!-- Home Delivery -->
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="d-flex align-items-center gap-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="home_delivery">
            </div>
            <label class="fw-semibold mb-0" for="home_delivery">
                Home Delivery
            </label>
        </div>

        <button
            type="button"
            id="addAddressBtn"
            class="btn btn-sm btn-light border d-none"
            onclick="openAddressModal()">
            ADD NEW ADDRESS
        </button>
    </div>

    <!-- Express Delivery -->
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="express_delivery">
            </div>
            <label class="fw-semibold mb-0" for="express_delivery">
                Express Delivery
            </label>
        </div>

        <select
            class="form-control form-control-sm w-50 d-none"
            id="express_charge">
            <option value="0">Select</option>
            <option value="25">25% Extra</option>
            <option value="50">50% Extra</option>
            <option value="100">100% Extra</option>
        </select>
    </div>

    <!-- Hidden fields -->
    <input type="hidden" name="is_home_delivery" id="is_home_delivery" value="0">
    <input type="hidden" name="is_express_delivery" id="is_express_delivery" value="0">
    <input type="hidden" name="express_percent" id="express_percent" value="0">

    <input type="hidden" name="gross_total" id="gross_total">
    <input type="hidden" name="discount_amt" id="discount_amt">
    <input type="hidden" name="final_total" id="final_total">
    <input type="hidden" name="coupon_master_id" id="coupon_master_id">
    <input type="hidden" name="coupon_percent" id="coupon_percent">
    <input type="hidden" name="total_count" id="total_count">

    <button
        type="submit" name="submit"
        class="btn btn-success btn-sm mt-3 fw-semibold w-100"
        id="submitBtn"
        disabled>
        Create Order
    </button>
</form>