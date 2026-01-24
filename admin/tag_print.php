<?php
include("../adminsession.php");
require("fpdf183/fpdf.php");

$order_id = (int)($_GET['order_id'] ?? 0);
if ($order_id <= 0) exit("Invalid order id");

$companyTop = "FAB-CHHOTAPARA-492001";

$qry = $obj->executequery("
    SELECT 
        o.order_no,
        o.delivery_date,
        c.customer_name,
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
  function DCBox($x, $y, $service_name)
  {
    $this->SetXY($x, $y);
    $this->SetFont('Arial', 'B', 11);
    $this->Cell(7, 5, $service_name, 1, 0, 'C');
  }

  function PrintTagBlock($yStart, $companyTop, $customerName, $orderNo, $deliveryDate, $itemName, $serviceText)
  {
    $this->SetFont('Arial', 'B', 6);
    $this->SetXY(1, $yStart + 3);
    $this->Cell(42, 4, $companyTop, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 7);
    $this->SetX(1);
    $this->Cell(42, 4, strtoupper($customerName), 0, 1, 'C');

    $this->SetFont('Arial', 'B', 12);
    $this->SetX(1);
    $this->Cell(42, 7, "#" . $orderNo, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 10);
    $this->SetX(1);
    $this->Cell(42, 6, $deliveryDate, 0, 1, 'C');

    $this->DCBox(18, $yStart + 25, "DC");

    $this->SetFont('Arial', 'B', 8);
    $this->SetXY(1, $yStart + 40);
    $this->Cell(42, 5, strtoupper($itemName), 0, 1, 'C');

    $this->SetFont('Arial', 'B', 10);
    $this->SetX(1);
    $this->Cell(42, 5, $serviceText, 0, 1, 'C');
  }

  function DottedLine($y)
  {
    for ($x = 2; $x < 42; $x += 2) {
      $this->Line($x, $y, $x + 0.8, $y);
    }
  }
}

$pdf = new PDF('P', 'mm', [44, 200]);
$pdf->SetMargins(0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->SetTitle("Tag Print");
$pdf->AddPage();

$y = 5;
$blockHeight = 50;
$dividerGap = 5;
$totalCount = count($qry);
foreach ($qry as $row) {

  $orderNo = $row['order_no'];
  $customerName = $row['customer_name'] ?? '';
  $itemName = $row['item_name'] ?? '';
  $deliveryDate = date('D d M Y', strtotime($row['delivery_date']));
  $serviceText = "T " . $totalCount;

  if ($y + $blockHeight + 10 > 200) {
    $pdf->AddPage();
    $y = 5;
  }

  $pdf->PrintTagBlock($y, $companyTop, $customerName, $orderNo, $deliveryDate, $itemName, $serviceText);
  $pdf->DottedLine($y + $blockHeight);
  $y = $y + $blockHeight + $dividerGap;
}

$pdf->Output();
