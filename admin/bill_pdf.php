<?php include("../adminsession.php");
$order_id = (isset($_GET['order_id'])) ? $obj->test_input($_GET['order_id']) : 0;
$where = array("order_id" => $order_id);
$sqledit = $obj->select_record("orders", $where);

$companyname = $obj->getvalfield("company_setting", "comp_name", "1=1");
$companygstno = $obj->getvalfield("company_setting", "gstno", "1=1");
$companymobile = $obj->getvalfield("company_setting", "mobile", "1=1");
$companymobile2 = $obj->getvalfield("company_setting", "mobile2", "1=1");
$companyaddress = $obj->getvalfield("company_setting", "address", "1=1");
$companyemail = $obj->getvalfield("company_setting", "email", "1=1");
$companyterm_cond = $obj->getvalfield("company_setting", "term_cond", "1=1");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laundrixo</title>
</head>
<style>
    @page {
        margin: 4px;
    }

    hr {
        border: none;
        border-top: 1px dotted black;
        color: #fff;
        background-color: #fff;
        height: 1px;
    }

    * {
        color: black;
        font-size: 12px;
        font-family: 'Times New Roman';
    }

    .centered {
        text-align: center;
        align-content: center;
    }

    table {
        width: 100%;
    }
</style>

<body>
    <div class="bill">
        <p class="centered">
            TAX INVOICE
            <br><?= $companyname; ?>
            <br><?= $companyaddress; ?>
            <br>Phone No: <?= $companymobile; ?> / <?= $companymobile2; ?>
            <br>GST No: <?= $companygstno; ?>
        </p>
        <hr />
        <p>
            Booking Id: <?= $sqledit['order_no'] ?>
            <?= ($sqledit['invoice_no'] != '') ? "<br>Invoice No: INV" . $sqledit['invoice_no'] : "" ?>
            <br>Date & Time: <?= $obj->dateformatindia($sqledit['createdate']) ?> <?= $sqledit['createtime'] ?>
        </p>
        <hr />
        <p>
        <div>Bill To:</div>
        <?= $obj->getvalfield("m_customer", "customer_name", "customer_id='{$sqledit['customer_id']}'");
        ?>
        </p>
        <hr />
        <table>
            <thead>
                <tr>
                    <td>Code</td>
                    <td>Pc</td>
                    <td>Qnt.</td>
                    <td>Description</td>
                    <td>Rate</td>
                    <td align="right">Price</td>
                </tr>
            </thead>
            <tbody>
                <?php $slno = 1;
                $totalQty = 0;
                $res = $obj->executequery("
    SELECT 
        oi.*,
        im.item_name,
        im.item_in,
        im.hsncode
    FROM order_item oi
    JOIN item_master im ON im.item_id = oi.item_id
    WHERE oi.order_id='$order_id'
    ORDER BY oi.order_item_id DESC
");
                $taxSummary = [];
                foreach ($res as $key) {
                    $total_qty = 0;
                    $serviceNames = [];
                    $LaundryItems = [];
                    $item_type_master_name = $obj->getvalfield("item_type_master", "item_type_master_name", "item_type_master_id='{$key['item_type_master_id']}'");
                    $selection = json_decode($key['selection_json'], true);
                    $gst_percent = (float)$key['gst_percent'];
                    $hsn_sac = $key['hsncode'] ?: '99971';

                    if ($gst_percent > 0) {

                        $groupKey = $hsn_sac . '_' . $gst_percent;

                        if (!isset($taxSummary[$groupKey])) {
                            $taxSummary[$groupKey] = [
                                'hsn'      => $hsn_sac,
                                'gst'      => $gst_percent,
                                'taxable' => 0,
                                'cgst'    => 0,
                                'sgst'    => 0
                            ];
                        }

                        $taxSummary[$groupKey]['taxable'] += (float)$key['taxable_amount'];
                        $taxSummary[$groupKey]['cgst']    += (float)$key['cgst_amount'];
                        $taxSummary[$groupKey]['sgst']    += (float)$key['sgst_amount'];
                    }

                    if ((int)$key['item_id'] === 2) {
                        /* ===== LAUNDRY ===== */
                        $services = [];
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

                        $serviceText = implode(' + ', $serviceNames);

                        $LaundryItems = [];
                        $lanitms = $obj->executequery("SELECT 
        im.item_name,
        itm.item_type_master_name,
        oil.qty,
        oil.comments
    FROM order_item_laundry oil
    JOIN item_master im ON im.item_id = oil.item_id
    JOIN item_type_master itm ON itm.item_type_master_id = oil.item_type_master_id
    WHERE oil.order_id = '$order_id' and oil.order_item_id='{$key['order_item_id']}'
");

                        foreach ($lanitms as $row) {

                            $itemText = $row['item_name'] . " [" . $row['item_type_master_name'] . "] X " . $row['qty'];
                            $LaundryItems[] = $itemText;
                            $total_qty += $row['qty'];
                        }
                    } else {
                        foreach ($selection['services'] ?? [] as $srv) {

                            if (empty($srv['id']))
                                continue;

                            $srvId = (int) $srv['id'];
                            $item_service_id = $obj->getvalfield("add_service", "item_service_id", "add_service_id='$srvId'");
                            if (!$item_service_id)
                                continue;

                            $name = $obj->getvalfield("item_service", "item_sname", "item_service_id='$item_service_id'");
                            if ($name)
                                $serviceNames[] = $obj->shortCode($name);
                        }
                        $total_qty = $key['qty'];
                        $serviceText = implode(' , ', $serviceNames);
                    }
                ?>
                    <tr>
                        <td><?= $slno++; ?>N</td>
                        <td><?= $total_qty; ?></td>
                        <td><?= $key['qty']; ?></td>
                        <td><?= $key['item_name']; ?><?php ($item_type_master_name != '0') ? "[" . $item_type_master_name . "]" : ''; ?><br />Services: <?= $serviceText; ?><br>
                            <?php if (!empty($LaundryItems)) { ?>
                                <?php foreach ($LaundryItems as $txt) { ?>
                                    <?= $txt; ?><br>
                                <?php } ?>
                            <?php } ?>
                        </td>
                        <td><?= $key['service_total']; ?></td>
                        <td align="right"><?= $key['total_amount']; ?></td>
                    </tr>
                <?php $totalQty += $total_qty;
                }
                ?>
                <tr>
                    <td>Total Pcs</td>
                    <td><?= $totalQty; ?></td>
                    <td colspan="3"></td>
                    <td align="right">
                        Sub Total : <?= $sqledit['gross_total'] ?>
                    </td>
                </tr>
                <?php if ($sqledit['is_express_delivery'] == 1) { ?>
                    <tr>
                        <td>Express Delivery</td>
                        <td colspan="3"></td>
                        <td><?= $sqledit['express_percent'] ?>%</td>
                        <td align="right">
                            Total : <?= $sqledit['express_amount'] ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td align="right">
                        Total : INR <?= $sqledit['gross_total'] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
        <p>
            Tax Summary
        <table>
            <thead>
                <tr>
                    <td>Code</td>
                    <td>HSN/SAC</td>
                    <td>Type</td>
                    <td>Rate</td>
                    <td>Taxable Amt</td>
                    <td align="right">Tax Amt</td>
                </tr>
            </thead>
            <?php
            $taxableGrand = 0;
            $taxGrand     = 0;
            $code = 1;

            foreach ($taxSummary as $t) {

                $halfRate = $t['gst'] / 2;
            ?>
                <tr>
                    <td><?= $code ?>N</td>
                    <td><?= $t['hsn'] ?></td>
                    <td>CGST</td>
                    <td><?= $halfRate ?></td>
                    <td><?= $t['taxable'] ?></td>
                    <td align="right"><?= $t['cgst'] ?></td>
                </tr>

                <tr>
                    <td><?= $code ?>N</td>
                    <td><?= $t['hsn'] ?></td>
                    <td>SGST</td>
                    <td><?= $halfRate ?></td>
                    <td><?= $t['taxable'] ?></td>
                    <td align="right"><?= $t['sgst'] ?></td>
                </tr>
            <?php
                $taxableGrand += $t['taxable'];
                $taxGrand     += ($t['cgst'] + $t['sgst']);
                $code++;
            }
            ?>
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td><strong>INR <?= $taxableGrand ?></strong></td>
                <td align="right"><strong>INR <?= $taxGrand ?></strong></td>
            </tr>

        </table>
        <div>PRICES INCLUSIVE OF ALL TAXES</div>
        </p>
        <hr />
        <p class="centered">Thank you for using our service.
            <br>Smarter. Cleaner. Greener.
            <br>www.laundrixo.in
            <br>Email: <?= $companyemail; ?>
        </p>
        <hr />
        <p>
        <div class="centered">Terms and Conditions</div>
        <?= nl2br($companyterm_cond); ?>
        </p>
        <hr />
    </div>

</body>

</html>