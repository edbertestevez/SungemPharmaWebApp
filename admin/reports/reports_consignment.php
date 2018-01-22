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

function Row($data)
{

    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=(5*$nb)+3; //+3 sa height nya nadi pre
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

        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,6,$data[$i],0,$a);
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
    // Logo
    $this->Image('../img/logo_orig.png',10,3,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(30);
    // Title
    $this->Cell(220,5,'SUNGEM PHARMA',0,0,'C');
    // Line break
    $this->Ln(5);
    // Move to the right
    $this->Cell(30);
    // Line break
    $this->Cell(220,8,'(Consignment Report)',0,0,'C');
    // Line break
    $this->Ln(5);
    //ALL VALUES RETRIEVED FROM URL CONVERT
    $start_date = date('F d, Y', strtotime($_GET['start']));
    $end_date = date('F d, Y', strtotime($_GET['end']));
    
    // Move to the right
    $this->Cell(30);
    //SUBTITLE
    $this->SetFont('Arial','',12);
    $this->Cell(220,10, $start_date." - ".$end_date ,0,0,'C');
    // Line break
    $this->Ln(15);
    
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Instanciation of inherited class above na di pre
$pdf = new PDF();
//TABLE HEADER
//CLASS FUNCTION


$pdf->AliasNbPages();
$pdf->AddPage(L);

$pdf->SetFont('Arial','B',12);
$pdf->ln(1);
$pdf->Cell(275,5,'CLIENT / AREA CONSIGNMENT REPORT',0,0,'C');
$pdf->ln(8);
$pdf->SetFont('Arial','B',10);
$pdf->SetWidths(array(85,70,30,30,30,30));
//ALIGNMENT SANG COLUMNS
$pdf->SetAligns(array('L','L','C','C','C','C'));
//HEADER

$pdf->Row(array('CLIENT', 'AREA','TOTAL CONSIGNED', 'TOTAL WITHDRAWN', '   TOTAL    SOLD', 'TOTAL SALES VALUE' ));
$client_list = $clients->get_clients_desc();
$pdf->SetFont('Arial','',11);
foreach($client_list as $client_row){
    $consigned=0;
    $sales=0;
    $withdrawn=0;
    $sold=0;

    $client_consigned = $reports->get_client_report_consigned($client_row['client_id'],$_GET['start'], $_GET['end']);
    $client_withdrawn = $reports->get_client_report_withdrawn($client_row['client_id'],$_GET['start'], $_GET['end']);
    $client_qty = $reports->get_client_report_sold($client_row['client_id'],$_GET['start'], $_GET['end']);
    $client_sales = $reports->get_client_report_sales($client_row['client_id'],$_GET['start'], $_GET['end']);

    if($client_consigned!=""){$consigned=$client_consigned;}
    if($client_sales!=""){$sales=$client_sales;}
    if($client_withdrawn!=""){$withdrawn=$client_withdrawn;}
    if($client_qty!=""){$sold=$client_qty;}

    if(($consigned+$sales+$withdrawn+$sold)>0){
    $x = $pdf->Row(array($client_row['client_name'],$client_row['area_name'],$consigned,$withdrawn,$sold,"P".number_format($sales, 2, '.', ',')));
    }
}

//PAGE BREAK
$pdf->AddPage(L);
$pdf->SetFont('Arial','B',12);
$pdf->ln(1);
$pdf->Cell(275,5,'MEDICINE CONSIGNMENT REPORT',0,0,'C');
$pdf->ln(8);
$pdf->SetFont('Arial','B',10);
//MEDICINES NA DI NA AREA
$data = $products->get_products();
//WIDTH SANG COLUMN
$pdf->SetWidths(array(35,50,50,30,26,27,25,30));
//ALIGNMENT SANG COLUMNS
$pdf->SetAligns(array('L','L','L','L','C','C','C','C'));
//HEADER
$pdf->Row(array('BRAND NAME', 'GENERIC NAME', 'FORMULATION', 'PACKAGING', 'TOTAL CONSIGNED', 'TOTAL WITHDRAWN', 'QTY SOLD', 'SALES VALUE'));
//CONTENT
$pdf->SetFont('Arial','',11);
foreach($data as $row) {
    $total_consign_specific=$reports->total_consign_specific($_GET['start'], $_GET['end'], $row['pro_id']);
    if($total_consign_specific==""){
        $total_consign_specific=0;
    }
    $total_consign_withdrawn_specific=$reports->total_consign_withdrawn_specific($_GET['start'], $_GET['end'], $row['pro_id']);
    if($total_consign_withdrawn_specific==""){
        $total_consign_withdrawn_specific=0;
    }
    $total_consign_sold_specific=$reports->total_consign_sold_specific($_GET['start'], $_GET['end'], $row['pro_id']);
    if($total_consign_sold_specific==""){
        $total_consign_sold_specific=0;
    }
    $total_consign_value_specific=$reports->total_consign_value_specific($_GET['start'], $_GET['end'], $row['pro_id']);
    if($total_consign_value_specific==""){
        $total_consign_value_specific=0;
    }
    $overall = $total_consign_specific + $total_consign_withdrawn_specific + $total_consign_sold_specific + $total_consign_value_specific;

    if($overall>0){
    $x = $pdf->Row(array($row['pro_brand'],$row['pro_generic'],$row['pro_formulation'],$row['pro_packaging'],$total_consign_specific,$total_consign_withdrawn_specific,$total_consign_sold_specific,"P".number_format($total_consign_value_specific, 2, '.', ',')));
    }
}
//$pdf->BasicTable($header, $data);
$pdf->Output();
?>