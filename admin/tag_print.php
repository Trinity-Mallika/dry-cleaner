<?php
include("../adminsession.php");
require("fpdf183/fpdf.php");

$order_id = (int)($_GET['order_id'] ?? 0);
if ($order_id <= 0) exit("Invalid order id");

$companyTop = "LAUNDRIXO";

$qry = [];

$items = $obj->executequery("
    SELECT 
        o.order_no,
        o.delivery_date,
        c.customer_name,
        oi.qty,
        im.item_name
    FROM orders o
    JOIN m_customer c ON c.customer_id = o.customer_id
    JOIN order_item oi ON oi.order_id = o.order_id
    JOIN item_master im ON im.item_id = oi.item_id
    WHERE o.order_id = '$order_id'
      AND oi.item_id <> 2
");

foreach ($items as $row) {
  for ($i = 0; $i < (int)$row['qty']; $i++) {
    $qry[] = $row;
  }
}
$laundryItems = $obj->executequery("
    SELECT 
        o.order_no,
        o.delivery_date,
        c.customer_name,
        im.item_name,
        oil.qty
    FROM orders o
    JOIN m_customer c ON c.customer_id = o.customer_id
    JOIN order_item oi ON oi.order_id = o.order_id
    JOIN order_item_laundry oil ON oil.order_item_id = oi.order_item_id
    JOIN item_master im ON im.item_id = oil.item_id
    WHERE o.order_id = '$order_id'
      AND oi.item_id = 2
");

foreach ($laundryItems as $row) {
  for ($i = 0; $i < (int)$row['qty']; $i++) {
    $qry[] = $row;
  }
}

class PDF extends FPDF
{
  protected $javascript;
  protected $n_js;

  function IncludeJS($script)
  {
    $this->javascript = $script;
  }

  function _putjavascript()
  {
    $this->_newobj();
    $this->n_js = $this->n;
    $this->_out('<<');
    $this->_out('/Names [(EmbeddedJS) ' . ($this->n + 1) . ' 0 R]');
    $this->_out('>>');
    $this->_out('endobj');

    $this->_newobj();
    $this->_out('<<');
    $this->_out('/S /JavaScript');
    $this->_out('/JS ' . $this->_textstring($this->javascript));
    $this->_out('>>');
    $this->_out('endobj');
  }

  function _putresources()
  {
    parent::_putresources();
    if (!empty($this->javascript))
      $this->_putjavascript();
  }

  function _putcatalog()
  {
    parent::_putcatalog();
    if (!empty($this->javascript))
      $this->_out('/Names <</JavaScript ' . ($this->n_js) . ' 0 R>>');
  }

  // 🔥 AUTO PRINT FUNCTION
  function AutoPrint($dialog = true)
  {
    $param = ($dialog ? 'true' : 'false');
    $script = "print($param);";
    $this->IncludeJS($script);
  }


  function DCBox($x, $y, $service_name)
  {
    $this->SetXY($x, $y);
    $this->SetFont('Arial', 'B', 11);
    $this->Cell(7, 5, $service_name, 1, 0, 'C');
  }

  function PrintTagBlock($companyTop, $customerName, $orderNo, $deliveryDate, $itemName, $serviceText)
  {
    $usableWidth = 36;

    $this->SetFont('Arial', 'B', 6);
    $this->SetXY(1, 2);
    $this->Cell($usableWidth, 3, $companyTop, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 7);
    $this->SetFontSize(8);

    $name = strtoupper($customerName);
    $this->SetX(1);
    $this->Cell($usableWidth, 5, $name, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 11);
    $this->SetX(1);
    $this->Cell($usableWidth, 6, "#" . $orderNo, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 9);
    $this->SetX(1);
    $this->Cell($usableWidth, 4, $deliveryDate, 0, 1, 'C');

    $this->SetFont('Arial', 'B', 9);
    $this->SetXY(15, 22);
    $this->Cell(8, 5, 'DC', 1, 0, 'C');

    $this->SetFont('Arial', 'B', 7);
    $this->SetXY(1, 30);
    $this->MultiCell($usableWidth, 4, strtoupper($itemName), 0, 'C');

    $this->SetFont('Arial', 'B', 9);
    $this->SetXY(1, 38);
    $this->Cell($usableWidth, 4, $serviceText, 0, 1, 'C');
  }

  function SetDash($black = null, $white = null)
  {
    if ($black !== null)
      $s = sprintf('[%.3F %.3F] 0 d', $black, $white);
    else
      $s = '[] 0 d';
    $this->_out($s);
  }
}

$pdf = new PDF('P', 'mm', [38, 57]);
$pdf->SetMargins(0, 0);
$pdf->SetAutoPageBreak(false);
$pdf->SetTitle("Tag Print");

$totalCount = count($qry);

foreach ($qry as $row) {

  $pdf->AddPage();

  $orderNo = $row['order_no'];
  $customerName = $row['customer_name'] ?? '';
  $itemName = $row['item_name'] ?? '';
  $deliveryDate = date('D d M Y', strtotime($row['delivery_date']));
  $serviceText = "T " . $totalCount;

  $pdf->PrintTagBlock(
    $companyTop,
    $customerName,
    $orderNo,
    $deliveryDate,
    $itemName,
    $serviceText
  );

  $pdf->SetDash(1, 1);
  $pdf->Line(1, 45, 37, 45);
  $pdf->SetDash();
}

$pdf->AutoPrint(true);
$pdf->Output('I', 'tag-print.pdf');
