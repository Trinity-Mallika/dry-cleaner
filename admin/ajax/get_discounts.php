<?php
include_once("../../adminsession.php");
$coupons = $obj->executequery("
    SELECT coupon_master_id, coupon_name, coupon_amount 
    FROM coupon_master 
    ORDER BY coupon_master_id DESC
");

foreach ($coupons as $c):
?>
    <div class="card p-2 border mb-2">
        <h6 class="text-secondary">
            <?= $c['coupon_name']; ?>
            <span class="cursor text-success float-end"
                onclick="applyCoupon(this)"
                data-id="<?= $c['coupon_master_id']; ?>"
                data-name="<?= $c['coupon_name']; ?>"
                data-amount="<?= $c['coupon_amount']; ?>">
                Apply
            </span>
        </h6>
        <h6 class="text-secondary">
            Flat <?= $c['coupon_amount']; ?>% Off
        </h6>
    </div>
<?php endforeach; ?>