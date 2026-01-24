<?php
include("../adminsession.php");
require("fpdf183/fpdf.php");

$order_id = (int)($_GET['order_id'] ?? 0);
if ($order_id <= 0) exit("Invalid order id");

$companyTop = "FAB-CHHOTAPARA-492001";   // top line
$companyname = "Laundrixo";

// Fetch order + items + customer
$qry = $obj->executequery("
    SELECT 
        o.order_no,
        o.delivery_date,
        o.customer_id,
        c.customer_name,
        oi.qty,
        oi.item_id,
        im.item_name
    FROM orders o
    LEFT JOIN m_customer c ON c.customer_id=o.customer_id
    LEFT JOIN order_item oi ON oi.order_id=o.order_id
    LEFT JOIN item_master im ON im.item_id=oi.item_id
    WHERE o.order_id='$order_id'
");

if (empty($qry)) exit("Order not found");

class PDF extends FPDF
{
    function TagBorder()
    {
        // outer border same like sample
        $this->Rect(1, 1, 42, 42); // 44mm page => keep 1mm margin
    }

    function DCBox($x, $y)
    {
        // small DC box like sample
        $this->SetXY($x, $y);
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(6, 5, "DC", 1, 0, 'C');
    }

    function PrintTag($companyTop, $customerName, $orderNo, $deliveryDate, $itemName, $serviceText = "T 5")
    {
        $this->AddPage();
        $this->SetAutoPageBreak(false);

        $this->TagBorder();

        // ====== Top shop code line ======
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(1, 6);
        $this->Cell(42, 4, $companyTop, 0, 1, 'C');

        // ====== Customer name ======
        $this->SetFont('Arial', '', 8);
        $this->SetX(1);
        $this->Cell(42, 4, strtolower($customerName), 0, 1, 'C');

        // ====== Order No (big) ======
        $this->SetFont('Arial', 'B', 12);
        $this->SetX(1);
        $this->Cell(42, 7, "#" . $orderNo, 0, 1, 'C');

        // ====== Delivery Date (big) ======
        $this->SetFont('Arial', 'B', 10);
        $this->SetX(1);
        $this->Cell(42, 6, $deliveryDate, 0, 1, 'C');

        // ====== DC box ======
        $this->DCBox(18, 27);

        // ====== Item name (bold) ======
        $this->SetFont('Arial', 'B', 8);
        $this->SetXY(1, 33);
        $this->Cell(42, 5, strtoupper($itemName), 0, 1, 'C');

        // ====== Service line ======
        $this->SetFont('Arial', 'B', 8);
        $this->SetX(1);
        $this->Cell(42, 5, $serviceText, 0, 1, 'C');
    }
}

$pdf = new PDF('P', 'mm', [44, 44]);
$pdf->SetMargins(0, 0);

// print each order item as 2 copies (same like your photo)
foreach ($qry as $row) {

    $orderNo = $row['order_no'];
    $customerName = $row['customer_name'] ?? '';
    $itemName = $row['item_name'] ?? '';

    // Format like: Sat 24 Jan 2026
    $deliveryDate = date('D d M Y', strtotime($row['delivery_date']));

    $serviceText = "T 5";

    $pdf->PrintTag($companyTop, $customerName, $orderNo, $deliveryDate, $itemName, $serviceText);
}

$pdf->Output();
