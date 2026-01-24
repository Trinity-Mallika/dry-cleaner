<?php include("../adminsession.php");
$order_id = (isset($_GET['order_id'])) ? $obj->test_input($_GET['order_id']) : 0;
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
            RECEIPT
            <br>LAUNDRY CARE
            <br>Opposite Surana bhavan, Beside H.P.gas agency, Rajeev gandhi Chowk, Main road , Chhotapara 492001
            <br>Phone No: 0120-6824455 / 07406344009
            <br>GST No: 22BCDPK2279P1ZB
        </p>
        <hr />
        <p>
            Booking Id: 1738984
            <br>
            Receipt Id: a0e78cfd-bbcf-400f-9fc8-0cae0d82cbb7
            <br>Date & Time: 2026-01-23 15:50:33
        </p>
        <hr />
        <p>
        <div>Bill To:</div>
        FARSEEN
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
                <tr>
                    <td>1N</td>
                    <td>1</td>
                    <td>1</td>
                    <td>Dupatta [Wedding]<br />Services: DC</td>
                    <td>154</td>
                    <td align="right">154</td>
                </tr>
                <tr>
                    <td>Total Pcs</td>
                    <td>1</td>
                    <td colspan="3"></td>
                    <td align="right">
                        Sub Total : 154
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td align="right">
                        Total : INR 154
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
            <tbody>
                <tr>
                    <td>1N</td>
                    <td>99971</td>
                    <td>CGST</td>
                    <td>9</td>
                    <td>130.5</td>
                    <td align="right">11.74</td>
                </tr>
                <tr>
                    <td>1N</td>
                    <td>99971</td>
                    <td>SGST</td>
                    <td>9</td>
                    <td>130.5</td>
                    <td align="right">11.74</td>
                </tr>
                <tr>
                    <td colspan="4">Total</td>
                    <td>INR 130.5</td>
                    <td align="right">INR 23.48</td>
                </tr>
            </tbody>
        </table>
        <div>PRICES INCLUSIVE OF ALL TAXES</div>
        </p>
        <hr />
        <p class="centered">Thank you for using our service.
            <br>Smarter. Cleaner. Greener.
            <br>www.fabrico.in
            <br>Email: contact@fabrico.in

        </p>

        <hr />
        <p>
        <div class="centered">Terms and Conditions</div>
        <br>
        1. Customer shall examine articles for damage at the time of delivery, and notify the same with in 24 hours from the date of delivery and company shall not be responsible for any claims afterwards.
        <br>2. Company assures the warranty of 2 days from the date of delivery for the articles, for any quality related issues with washing or dry-cleaning of articles (only if the article has not been used by the customer after service). Any quality related claim after the stipulated time shall not
        be entertained.
        <br>3. Company is not responsible for any article which is left beyond 15Days from the date of delivery. After the completion of 15 days company will charge 25% of total bill for next 15 days. After 30 days from the date of delivery store will not be liable for loss or damages.
        <br>4. Removal of stain is a part of the process but, complete removal of stains can not be guaranteed and will be processed at customer's risk.
        <br>5. We handle all garments, linen, and fabrics with utmost care, but please be aware that due to the condition of the items or unseen material defects, there's a risk of discoloration or shrinkage.
        These items are cleaned at the owner's risk, and we accept no liability for such occurrences.
        <br>6. Our company strives to maintain a high quality in our washing, drying, and folding services, employing reasonable efforts to achieve this standard.
        <br>7. We bear no responsibility for special care or delicate items needing particular cleaning attention.
        <br>8. We reserve the right to decline cleaning any garment.
        <br>9. We are not responsible for loss or damage to personal items left in garments, such as money or jewellery or anything else.
        <br>10. In case of any loss or damage of an article Company can reimburse up to a maximum of six (6) times of its processing (laundry/dryclean) cost or the Rs. 3,000 which ever is lower (decision remains with Company if any reimbursement has to be done).
        customer must show Fabrico Laundry / Dryclean bill.
        <br>11. Please count your articles upon delivery and report any discrepancies immediately, as we cannot be held responsible for claims after the delivery is accepted and signed for.
        <br>12. Company shall not be held responsible for any ornaments/ jewellery fittings on the garment.
        <br>13. Any loss/damage/delay due to FORCE MAJEURE conditions,Company is not liable for any compensation or reduction in charges.
        <br>14. We accept no liability for any loss or damage of the articles arising due to fire, burglary etc. beyond conduct or any other similar unforseen causes.
        <br>15.Some process/items may require additional period to process. No deduction on billed amount or claim can be initiated against in respect of delays.

        </p>
        <hr />
    </div>

</body>

</html>