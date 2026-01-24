<?php
include_once("../adminsession.php");

$order_id = intval($_REQUEST['order_id'] ?? 0);

$slno = 1;

$res = $obj->executequery("
    SELECT payment_id, pay_date, pay_type, pay_amount
    FROM payment
    WHERE order_id = '$order_id'
    ORDER BY payment_id DESC
");

if (empty($res)) {
?>
    <tr>
        <td colspan="5" class="text-center text-muted">No payments found</td>
    </tr>
<?php
    exit;
}

foreach ($res as $key) {
?>
    <tr>
        <td class="fw-semibold"><?= $slno++; ?>.</td>

        <td><?= $obj->dateformatindia($key['pay_date']); ?></td>

        <td>
            <span class="badge bg-primary">
                <?= htmlspecialchars($key['pay_type']); ?>
            </span>
        </td>

        <td class="fw-semibold">
            <?= number_format((float)$key['pay_amount'], 2); ?>
        </td>

        <td>
            <button type="button"
                class="btn btn-sm btn-outline-danger"
                onclick="deletePayment(<?= (int)$key['payment_id']; ?>)">
                Delete
            </button>
        </td>
    </tr>
<?php
}
?>