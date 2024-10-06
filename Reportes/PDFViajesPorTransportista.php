<?php

include "../LibreriasPHP/fpdf/fpdf.php";
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php';

$usuario              = $_SESSION['usuario'];
$nombre_rol           = $_SESSION['nombre_rol'];
$transportista        = $_GET['transportista'];
$fecha_inicio         = $_GET['fecha_inicio'];
$fecha_final          = $_GET['fecha_final'];

$fechas = utf8_decode('Desde ').$_GET['fecha_inicio'].' Hasta '.$_GET['fecha_final'];

$jsonData             =array();
$jsonData['message']  ="";
$jsonData['success']  =true;

$reporte_transportista =sqlsrv_query($conn,"SELECT nombre FROM Transportistas WHERE transportista_id='$transportista'");
$row_transportista = sqlsrv_fetch_object($reporte_transportista);
$nombre_transportista = isset($row_transportista->nombre) ? $row_transportista->nombre : 'Desconocido';


class PDF_Sector extends FPDF
{
    var $widths;
    var $aligns;

    function Header()

    {
     
    //Fondo
    $this->Image('../assets/images/images_svg/viajes.png',5,0,25); 


    global $nombre_transportista;
    global $fechas;
    global $usuario;
    global $nombre_rol;

    //TITULO DEL REPORTE
    $this->setY(35);$this->setX(29);
    $this->SetFont('Arial','B',15);
    $this->Cell(20,-40,utf8_decode('Reporte de Viajes del Transportista '.$nombre_transportista),0,0,'L');

    $this->Ln(5);

    $this->setY(40);$this->setX(50);
    $this->SetFont('Arial','',10);
    $this->Cell(20,-40,utf8_decode($fechas),0,0,'C');

    $this->Ln(5);

    }

    //Pie de página
    function Footer()
    {

        $this->SetY(-13);

        $this->SetFont('courier','I',10);

        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'',0,0,'C');
    }     

    function SetWidths($w) {
        //Set the array of column widths
        $this->widths=$w;
    }

    function SetAligns($a) {
        //Set the array of column alignments
        $this->aligns=$a;
    }


 function Row($data) { // Identical to "Table with MultiCells" - (Olivier, 2002-11-17) but reformatted
 //Calculate the height of the row
    $nb = 0;
    for ($i = 0; $i < count($data); $i++)
       $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
   $h = 5 * $nb;
 //Issue a page break first if needed
   $this->CheckPageBreak($h);
 //Draw the cells of the row
   for ($i = 0; $i < count($data); $i++) {
       $w = $this->widths[$i];
       $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
 //Save the current position
       $x = $this->GetX();
       $y = $this->GetY();
 //Draw the border
       $this->Rect($x, $y, $w, $h);
 //Print the text
       $this->MultiCell($w, 5, $data[$i], 0, $a);
 //Put the position to the right of the cell
       $this->SetXY($x + $w, $y);
   }
 //Go to the next line
   $this->Ln($h);
}


        function CheckPageBreak($h) {
            //If the height h would cause an overflow, add a new page immediately
            if($this->GetY()+$h>$this->PageBreakTrigger)
                $this->AddPage($this->CurOrientation);
        }

        function NbLines($w,$txt) {

            $this->SetTextColor(0, 0, 0);
            //Computes the number of lines a MultiCell of width w will take
            $cw=&$this->CurrentFont['cw'];
            if($w==0)
                $w=$this->w-$this->rMargin-$this->x;
            $wmax=($w-1*$this->cMargin)*1000/$this->FontSize;
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



    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
    {
        $d0 = $a - $b;
        if($cw){
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        }else{
            $b += $o;
            $a += $o;
        }
        while($a<0)
            $a += 360;
        while($a>360)
            $a -= 360;
        while($b<0)
            $b += 360;
        while($b>360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b/360*2*M_PI;
        $a = $a/360*2*M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2*M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d/2))
            $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
        //draw the arc
        if ($d < M_PI/2){
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }else{
            $b = $a + $d/4;
            $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }
        //terminate drawing
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='b';
        else
            $op='s';
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k,
            ($h-$y1)*$this->k,
            $x2*$this->k,
            ($h-$y2)*$this->k,
            $x3*$this->k,
            ($h-$y3)*$this->k));
    }
}


class PDF_Diag extends PDF_Sector {
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;

    function PieChart($w, $h, $data, $format, $colors=null)
    {

        $this->SetFont('Inter-Regular', '', 10);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $hLegend = 5;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;
        if($colors == null) {
            for($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray,$gray,$gray);
            }
        }

        //Sectors
        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        //Legends
        $this->SetFont('Inter-Regular', '', 10);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
        for($i=0; $i<$this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
            $this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2,$y1);
            $this->Cell(0,$hLegend,$this->legends[$i]);
            $y1+=$hLegend + $margin;
        }
    }

    function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
    {
        $this->SetFont('Inter-Black', '', 10);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);
        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) {
            $maxVal = max($data);
        }
        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Inter-Black', '', 10);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0;
        foreach($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int)($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->Rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->SetXY(0, $yval);
            $this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->Text($xpos, $ypos, $val);
        }
    }

    function SetLegends($data, $format)
    {

        $this->SetTextColor(0, 0, 0);
        $this->legends=array();
        $this->wLegend=0;
        $this->sum=array_sum($data);
        $this->NbVal=count($data);
        foreach($data as $l=>$val)
        {
            $p=sprintf('%.2f',$val/$this->sum*100).'%';
            $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
            $this->legends[]=$legend;
            $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
        }
    }
}


//TAMAÑO Y HORIENTACION DE HOJA
$pdf=new PDF_Diag('P','mm','Letter');

$pdf->AddFont('Inter-Regular', '', 'Inter-Regular.php');
$pdf->AddFont('Inter-Medium', '', 'Inter-Medium.php');
$pdf->AddFont('Inter-SemiBold', '', 'Inter-SemiBold.php');
$pdf->AddFont('Inter-Bold', '', 'Inter-Bold.php');
$pdf->AddFont('Inter-ExtraBold', '', 'Inter-ExtraBold.php');
$pdf->AddFont('Inter-Black', '', 'Inter-Black.php');


#Establecemos el margen inferior:
$pdf->SetAutoPageBreak(true,7); 

#Establecemos los márgenes izquierda, arriba y derecha:
$pdf->SetMargins(5, 5, 5);

$pdf->AliasNbPages();
$pdf->AddPage();


$pdf->SetFont('Inter-ExtraBold','', 8);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(31, 54, 61);
$pdf->Cell(6,7,utf8_decode('#'),0,0,'C',TRUE);
$pdf->Cell(20,7,utf8_decode('Fecha de Viaje'),0,0,'C',TRUE);
$pdf->Cell(40,7,utf8_decode('Sucursal'),0,0,'C',TRUE);
$pdf->Cell(60,7,utf8_decode('Colaborador'),0,0,'C',TRUE);
$pdf->Cell(30,7,utf8_decode('Km Recorridos'),0,0,'C',TRUE);
$pdf->Cell(25,7,utf8_decode('Tarifa por Km'),0,0,'C',TRUE);
$pdf->Cell(25,7,utf8_decode('Monto Total'),0,0,'C',TRUE);
$pdf->Ln(8);




$filtro = "SELECT V.fecha_viaje, S.nombre AS SUCURSAL, C.nombre AS COLABORADOR, V.kilometros_viajados, T.tarifa_por_km, V.kilometros_viajados*T.tarifa_por_km AS MONTO
            FROM Viajes V
            INNER JOIN Colaboradores C ON V.colaborador_id = C.colaborador_id
            INNER JOIN Sucursales S ON V.sucursal_id = S.sucursal_id
            INNER JOIN Transportistas T ON V.transportista_id = T.transportista_id
            WHERE T.transportista_id = '$transportista'
          AND V.FECHA_VIAJE BETWEEN '$fecha_inicio'  AND '$fecha_final'
            ORDER BY S.nombre,C.nombre ASC";

$query_viajes=sqlsrv_query($conn,"$filtro");

$sum_tt = 0; $count = 1;
while ($row = sqlsrv_fetch_object($query_viajes)){

    $pdf->SetFont('Inter-Regular', '', 8);
    $pdf->SetWidths(array(6,20,40,60,30,25,25));

    $pdf->Row(array($count,$row->fecha_viaje->format('Y-m-d'),utf8_decode((mb_strtoupper($row->SUCURSAL))),utf8_decode((mb_strtoupper($row->COLABORADOR,'UTF-8'))),number_format($row->kilometros_viajados,0),number_format($row->tarifa_por_km,2),number_format($row->MONTO,2)));

    $sum_tt+=$row->MONTO;
    $count++;
    $items = $count-1;

}
$pdf->Ln(5);
$pdf->SetWidths(array(50, 50));
    $pdf->SetFont('Inter-SemiBold','', 11);
    $pdf->Row(array(utf8_decode('Colaboradores Transportados ').$items, 'Monto Total Neto a Pagar L. '.number_format($sum_tt,2)));


$pdf->Ln(35);
$pdf->SetFont('arial','', 8);
$pdf->Cell(138,-80);
$pdf->Cell(21,-75,utf8_decode('Usuario Imprime: ').$usuario,0,0);
$pdf->Ln(3);
$pdf->Cell(138,-80);
$pdf->Cell(21,-75,utf8_decode('Fecha Impresión: ').$fecha_actual_insert,0,0);
$pdf->Ln(3);
$pdf->Cell(138,-80);
$pdf->Cell(21,-75,utf8_decode('Tipo Usuario: ').$nombre_rol,0,0);
$pdf->Ln(3);



$pdf->Output('Reporte de Viajes del Transportista '.$nombre_transportista.', desde el '.$fecha_inicio.' Al: '.$fecha_final.'.pdf','I');

//CERRAMOS LA CONEXION A LA BASE DE DATOS
sqlsrv_close($conn);

?>
