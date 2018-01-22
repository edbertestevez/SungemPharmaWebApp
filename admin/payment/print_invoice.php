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
    $payments = new Payments();
    $invoice = $payments->get_specific_invoice($_GET['invoice_id']);
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
    $this->SetFont('Arial','B',16);
    $this->Cell(240,10,'CHARGE SALES INVOICE',0,0,'C');

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
    $this->Cell(45,7,'Invoice No.',1,0,'C');
    // Line break
    // Move to the right
    $this->Ln(7);
    $this->Cell(117);
    $date_today = date('m/d/y');
    $this->Cell(25,7,$date_today,1,0,'C');
    $this->Cell(45,7,$_GET['invoice_id'],1,0,'C');

    $this->SetFont('Times','',10.5);
    $this->Ln(20);
    $this->Cell(2);
    $this->Rect(12,53,150,8);
    $client = substr($invoice['client_name'], 0, 48).". . .";
    $this->Cell(110,8,'Account Name:  '.$client,0,0,'L');
    $this->Cell(40,8,'TIN:',0,0,'L');
    $this->Cell(40,8,'P.O. No.:',1,0,'L');
    $this->Ln(8);
    $this->Cell(2);
    $this->Cell(110,8,'Business Style:',1,0,'L');
    $this->SetFont('Times','',8);
    $this->Cell(40,8,'PWD ID No.:',1,0,'L');
    $this->Cell(40,8,'PW Signature:',1,0,'L');
    $this->SetFont('Times','',10.5);
    $this->Ln(8);
    $this->Cell(2);
    $address = substr($invoice['address'], 0, 54).". . .";
    $this->Cell(110,8,'Address: '.$address,1,0,'L');
    $this->SetFont('Times','',9);
    $this->Cell(40,8,'Salesman:',1,0,'L');
    $this->Cell(40,8,'TERMS: '.$invoice['term_name'],1,0,'L');

}

// Page footer
function Footer()
{   
     $payments = new Payments();
    $invoice = $payments->get_specific_invoice($_GET['invoice_id']);
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number


    $this->ln(-104);
    $this->Cell(122);

    $this->SetFont('Times','',8.5);
    $this->ln(8);
    $this->Cell(2);
    $this->Cell(46,6,"",1,0,'C');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Total Sales (VAT Inclusive)",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"",1,0,'C');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Less: VAT",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"",1,0,'C');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Amount Net of Vat",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"Vatable Sales",1,0,'L');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Less:SC/PWD Discount",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"Vat Exempt Sales",1,0,'L');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Amount Due",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"Zero rated Sales",1,0,'L');
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"Add: VAT",1,0,'R');
    $this->Cell(48,6,"",1,0,'C');
     $this->ln(6);
    $this->Cell(2);
    $this->Cell(46,6,"Vat Amount",1,0,'L');
    $this->SetFont('Times','B',10);
    $this->Cell(50,6,"",1,0,'C');
    $this->Cell(46,6,"TOTAL AMOUNT DUE",1,0,'R');
    $this->SetFont('Times','B',12);
    $this->Cell(48,6,"Php ".$invoice['total_amount'],1,0,'C');

    $this->ln(20);
    $this->SetFont('Times','B',10);
    $this->ln(8);
    $this->Cell(2);
    $this->Cell(40,8,"Checked by:",T,0,'C');
    $this->Cell(5);
    $this->Cell(40,8,"Delivered By:",T,0,'C');
     $this->ln(18);
    $this->Cell(2);
    $this->Cell(40,8,"Noted By:",T,0,'C');
    $this->Cell(5);
    $this->Cell(40,8,"Date Received:",T,0,'C');

    $this->ln(-15);
    $this->Cell(122);
    $this->Cell(65,8,"By:",0,0,'LT');
    $this->ln(8);
    $this->Cell(130);
    $this->Cell(60,8,"Signature Over Printed Name",T,0,'C');    
}
}

//Instanciation of inherited class above na di pre
$pdf = new PDF('P','mm',array(215.9,356));
$pdf->SetLineWidth(0);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',11);
$pdf->SetLineWidth(0);

$pdf->SetWidths(array(23,23,50,23, 23, 24, 24));
//ALIGNMENT SANG COLUMNS
$pdf->SetAligns(array('L','C','C','C','C','C','C'));
//HEADER
// Line break
$pdf->Ln(18);
$pdf->Cell(2);
$pdf->SetFillColor(230,230,0);
$pdf->HeadRow(array('Lot No.', 'Expiry Date', 'Description', 'Quantity', 'Unit Price', 'Discount','Amount'));

$payments=new Payments();
$products=new Products();
$list = $payments->get_invoice_items($_GET['invoice_id']);
$pdf->SetAligns(array('L','C','C','C','C','C', 'C'));
foreach($list as $row){
    $pdf->Cell(2);
    $pdf->SetDrawColor(255,255,255);
    $lot = $products->get_lot_details($row['lot_id']);
    $pdf->Row(array("Lot #".$lot['lot_number'], $lot['expiry_date'],$row['pro_brand']."(".$row['pro_generic'].")-".$row['pro_formulation']." [".$row['pro_packaging']."]", $row['total_qty'], $row['pro_unit_price'], $row['discount'], $row['total']));
}

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Times','',11);


//BORDER KA CONTENT
$pdf->SetDrawColor(0,0,0);
$pdf->Rect(12,95,23,150);
$pdf->Rect(35,95,23,150);
$pdf->Rect(58,95,50,150);
$pdf->Rect(108,95,23,150);
$pdf->Rect(131,95,23,150);
$pdf->Rect(154,95,24,150);
$pdf->Rect(178,95,24,150);
$pdf->Output();
?>