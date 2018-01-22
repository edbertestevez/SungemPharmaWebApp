<?php
include '../library/config.php';
include '../classes/class.products.php';
include '../classes/class.suppliers.php';
include '../classes/class.payments.php';
include '../classes/class.orders.php';
include '../classes/class.delivery.php';
include '../classes/class.clients.php';
include '../classes/class.users.php';
include '../classes/class.reports.php';

$users = new Users();
$suppliers = new Suppliers();
$payments = new Payments();
$orders = new Orders();
$delivery = new Delivery();
$products = new Products();
$clients = new Clients();
$reports = new Reports();

require('../api/fpdf/fpdf.php');


class PDF extends FPDF
{

var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function HeadRow($data)
{

    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=(5*$nb)+3; //+10 sa height nya nadi pre
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {

        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->SetLineWidth(0);

        $this->Rect($x,$y,$w,$h); 
        //Print the text
        $this->MultiCell($w,6,$data[$i]);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row($data)
{

    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=(5*$nb)+10; //+10 sa height nya nadi pre
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->SetLineWidth(0);

        $this->Rect($x,$y,$w,$h); 
        //Print the text
        $this->MultiCell($w,6,$data[$i]);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
// Page header
function Header()
{
    $delivery = new Delivery();
    $orders = new Orders();
    $delivery_id = $_GET['delivery_id'];
    $record = $delivery->get_selected_delivery($delivery_id);
    // Logo
    $this->Image('../img/logo_orig.png',10,12,18);
    // Arial bold 15
    $this->SetFont('Times','B',20);
    // Move to the right
    $this->Cell(30);
    // Title
    $this->Cell(45,20,'SUNGEM PHARMA',0,0,'C');
    // Line break
    $this->Ln(5);
    // Move to the right
    $this->Cell(30);
    // Line break
    $this->SetFont('Arial','B',20);
    $this->Cell(240,10,'DELIVERY RECEIPT',0,0,'C');

    // ADDRESS
    $this->Ln(5);
    $this->SetFont('Arial','',10);
    $this->Cell(100,15,'Door #3 & 4 FourM Bldg. Lot 1, Blk.24',0,0,'C');
    $this->Ln(4);
    $this->Cell(98,15,'Fiesta Homes, Sum-ag, Bacolod City',0,0,'C');
    $this->Ln(4);
    $this->Cell(105,15,'Negros Occidental, Telefax No. 704-3603',0,0,'C');
    $this->Ln(4);
    $this->Cell(82,15,'TIN 126-426-336-000 VAT',0,0,'C');

    $this->SetFont('Times','',12);
    //DATE
    // Move to the right
    $this->Ln(-6);
    $this->Cell(117);
    $this->Cell(25,7,'Date',1,0,'C');
    $this->Cell(45,7,'Order No.',1,0,'C');
    // Line break
    // Move to the right
    $this->Ln(7);
    $this->Cell(117);
    $date_today = date('m/d/y');
    $this->Cell(25,7,$date_today,1,0,'C');
    $this->Cell(45,7,$record['order_id'],1,0,'C');

    $this->SetFont('Times','',11.5);
    $this->Ln(20);
    $this->Cell(2);
    $this->Cell(105,6,'Bill To:',1,0,'L');
    $this->Cell(10);
    $this->Cell(35,6,'Terms:',1,0,'L');
    $this->Cell(35,6,'Med Rep:',1,0,'L');
    $this->Ln(6);
    $this->Cell(2);
    $this->Cell(105,10,$record['client_name'],1,1,'L');
    $this->Ln(-10);
    $this->Cell(117);
    $term = $orders->get_order_termname($record['order_id']);
    $this->Cell(35,10,$term,1,0,'L');
    $this->Cell(35,10,$record['mr_lastname'],1,0,'L');

    //ALL VALUES RETRIEVED FROM URL CONVERT
    //$start_date = date('F d, Y', strtotime($_GET['start']));
    
    // Move to the right
    /*$this->Cell(30);
    //SUBTITLE
    $this->SetFont('Arial','B',12);
    $this->Cell(220,10, $start_date." - ".$end_date ,0,0,'C');
    // Line break
    $this->Ln(15);
    */
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->ln(-70);
    $this->Cell(2);
    $this->SetFont('Times','I',14);
    $this->Cell(185,10,'***NOTE: NO RETURN OF EXPIRY***',0,0,'C');
    $this->ln(10);
    $this->Cell(2);
    $this->SetFont('Times','B',9);
    $this->Cell(120,5,'TERMS AND CONDITION',1,0,'L');
    $this->Cell(65,5,'TOTAL AMOUNT',1,0,'C');
    $this->ln(5);
    $this->Cell(2);
    $this->SetFont('Times','B',8);
    $this->Cell(120,5,'1) Cheque payment should be crossed and payable to SUNGEM PHARMA',0,0,'LT');
    $this->SetFont('Times','B',18);
    $delivery = new Delivery();
    $orders = new Orders();
    $delivery_id=$_GET['delivery_id'];
    $record = $delivery->get_selected_delivery($delivery_id);
    $total_amount = $delivery->get_delivery_total($delivery_id);
    $this->Cell(65,18,"Php".$total_amount,1,0,'C');
    $this->SetFont('Times','B',8);
    $this->ln(4);
    $this->Cell(2);
    $this->Cell(120,5,'2) Goods herein remain the property of SUNGEM PHARMA until fully paid.',0,0,'LT');
    $this->ln(4);
    $this->Cell(2);
    $this->Cell(120,5,"3) Good's travel at purchaser's risk. No claim of whatsoever nature will be considered after five",0,0,'LT');
    $this->ln(3);
    $this->Cell(4);
    $this->Cell(120,5,"(5) days from date delivery",0,0,'LT');

    $this->ln(3);
    $this->Cell(2);
    $this->Rect(12,227,120,18);

    $this->SetFont('Times','B',11);
    $this->ln(8);
    $this->Cell(2);
    $this->Cell(90,6,"Prepared by: ".$_SESSION['userdata'],1,0,'LT');
    $this->ln(6);
    $this->Cell(2);
    $this->Cell(90,6,"Checked by: ",1,0,'LT');
    $this->ln(6);
    $this->Cell(2);
    $this->Cell(90,6,"Noted by: ",1,0,'LT');
    $this->ln(12);
    $this->Cell(2);
    $this->Cell(90,6,"Delivered by:",1,0,'LT');
    $this->ln(6);
    $this->Cell(2);
    $this->Cell(90,6,"Date Received:",1,0,'LT');

    $this->ln(-20);
    $this->Cell(122);
    $this->Cell(65,8,"By:",1,0,'LT');
    $this->ln(8);
    $this->Cell(122);
    $this->Cell(65,8,"Signature Over Printed Name",1,0,'C');

    $this->SetFont('Times','B',9);
    $this->ln(-18);
    $this->Cell(115);
    $this->Cell(65,8,"Received the above merchandise in good order and condition",0,0,'C');
}
}

//Instanciation of inherited class above na di pre
$pdf = new PDF();

$pdf->SetLineWidth(0);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',11);
$pdf->SetLineWidth(0);
$pdf->SetWidths(array(50,23,23,23, 23, 23, 23));
//ALIGNMENT SANG COLUMNS
$pdf->SetAligns(array('L','C','C','C','C','C','C'));
//HEADER
// Line break
$pdf->Ln(15);
$pdf->Cell(2);
$pdf->SetFillColor(230,230,0);
$pdf->HeadRow(array('Description','Lot No.', 'Expiry Date', 'Quantity', 'Unit Price', 'Discount','Amount'));

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Times','',11);
$delivery = new Delivery();
$orders = new Orders();
$delivery_id=$_GET['delivery_id'];
$record = $delivery->get_selected_delivery($delivery_id);
$list = $delivery->get_delivered_orditems($delivery_id);
$pdf->SetAligns(array('L','C','C','C','C','C', 'C'));
foreach($list as $row){
    $pdf->Cell(2);
    $pdf->SetDrawColor(255,255,255);
    $lot = $products->get_lot_details($row['lot_id']);
    $pdf->Row(array($row['pro_brand']."(".$row['pro_generic'].")-".$row['pro_formulation'],"Lot #".$lot['lot_number'], $lot['expiry_date'], $row['qty_delivery'], $row['pro_unit_price'], $row['discount'], $row['qty_delivered']));
}
//BORDER KA CONTENT
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(12,82,50,130);
$pdf->Rect(62,82,23,130);
$pdf->Rect(85,82,23,130);
$pdf->Rect(108,82,23,130);
$pdf->Rect(131,82,23,130);
$pdf->Rect(154,82,23,130);
$pdf->Rect(177,82,23,130);

$pdf->Output();
?>