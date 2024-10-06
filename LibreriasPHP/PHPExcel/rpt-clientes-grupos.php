<?php

$cliente = $_GET['cliente'];   
 
require('../fpdf/fpdf.php');
include("../conexionreal.php");

$pdf = new FPDF('L','mm','A3');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10); 
$pdf->Image('../assets/images/Logo3.png',20,25,20,10,'PNG');
$pdf->Cell(18, 10, '', 0); 
$pdf->SetFont('Arial', '', 9); 
$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(10, 8, '', 0);
$pdf->Cell(370, 8, 'Estado de Cuenta '.$cliente, 0, 0,'C'); 
$pdf->Ln(20);
//$pdf->Cell(1, 8, '', 0); 
$pdf->SetFont('Arial', 'B', 8); 
$pdf->Cell(10, 8, '', 0);
$pdf->Cell(10, 8, 'No.', 1);
$pdf->Cell(20, 8, 'Cod. Cliente', 1); 
$pdf->Cell(110, 8, 'Cliente', 1);
$pdf->Cell(30, 8, 'Cod. Movimiento', 1, 0,'C'); 
$pdf->Cell(30, 8, 'Serie Documento', 1, 0,'C'); 
$pdf->Cell(30, 8, 'Numero Documento', 1, 0,'C'); 
$pdf->Cell(30, 8, 'Fecha Documento', 1, 0,'C'); 
$pdf->Cell(30, 8, utf8_decode('Fecha Vencimiento'), 1, 0,'C'); 
$pdf->Cell(30, 8, 'Estado', 1, 0,'C'); 
$pdf->Cell(25, 8, 'Monto', 1, 0,'R'); 
$pdf->Cell(25, 8, 'Saldo', 1, 0,'R'); 
$pdf->Ln(8);
$pdf->SetFont('Arial', '', 9);
//CONSULTA
$sql = "SELECT ME.CODIGO_DE_CLIENTE,C.NOMBRE_CLIENTE, ME.CODIGO_MOVIMIENTO, ME.SERIE_DEL_DOCUMENTO, ME.NUMERO_DOCUMENTO, CASE WHEN(DATEDIFF(DAY,ME.FECHA_DOCUMENTO,GETDATE()))>CP.DIAS_PLAZO AND (ME.SALDO_TOTAL+ME.SALDO_IVA)>0 THEN 'VENCIDA'  ELSE('')END ESTADO, ME.FECHA_DOCUMENTO,DATEADD(DAY,CP.DIAS_PLAZO,ME.FECHA_DOCUMENTO) AS VENCIMIENTO,ME.MONTO_TOTAL+ME.MONTO_IVA AS MONTO, ME.SALDO_TOTAL+ME.SALDO_IVA AS SALDO FROM MOVIMIENTO_ENC ME, CLIENTES C, CONDICIONES_DE_PAGO CP WHERE ME.CODIGO_DE_CLIENTE=C.CODIGO_DE_CLIENTE AND C.CODIGO_DE_CONDICION=CP.CODIGO_DE_CONDICION AND ME.STATUS_MOVIMIENTO<>'AN' AND ME.SALDO_TOTAL>0 AND C.NOMBRE_CLIENTE LIKE UPPER('%$cliente%') ORDER BY ME.FECHA_DOCUMENTO"; 
$consultando=odbc_exec($conect,$sql);
$item = 0;
$monto = 0; 
$saldo = 0; 
$montoTotal=0;
$saldoTotal=0;
$vencido=0;

while($fila = odbc_fetch_array($consultando)){
	$pdf->SetTextColor(0,0,0);
	$item = $item+1;    
	$monto = $fila['MONTO']; 
	$saldo = $fila['SALDO']; 	  
	//$montoTotal = $montoTotal + $monto;
	$saldoTotal = $saldoTotal + $saldo;  
	$pdf->Cell(10, 8, '', 0);  
	$pdf->Cell(10, 8, $item, 0); 
	$pdf->Cell(20, 8, $fila['CODIGO_DE_CLIENTE'], 0);	
	$pdf->Cell(110, 8, $fila['NOMBRE_CLIENTE'], 0);	
	$pdf->Cell(30, 8, $fila['CODIGO_MOVIMIENTO'], 0, 0,'C');
	$pdf->Cell(30, 8, $fila['SERIE_DEL_DOCUMENTO'], 0, 0,'C');	
	$pdf->Cell(30, 8, $fila['NUMERO_DOCUMENTO'], 0, 0,'C');		
	$pdf->Cell(30, 8, date('d/m/Y', strtotime($fila['FECHA_DOCUMENTO'])), 0, 0,'C');
	//$pdf->Cell(30, 8, $fila['DIAS_PLAZO'], 0, 0,'C');
	$pdf->Cell(30, 8, date('d/m/Y', strtotime($fila['VENCIMIENTO'])), 0, 0,'C');
	if ($fila['ESTADO']=='VENCIDA') {
		$pdf->SetTextColor(201,0,0);
		$pdf->Cell(30, 8, $fila['ESTADO'], 0, 0,'C'); 
		$vence = $fila['SALDO']; 
		$vencido = $vencido + $vence;
	}else{
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(30, 8, $fila['ESTADO'], 0, 0,'C'); 	
	}
  	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(25, 8, number_format($fila['MONTO'], 2,".",","), 0, 0,'R');	 
	$pdf->Cell(25, 8, number_format($fila['SALDO'], 2,".",","), 0, 0,'R');	 
	$pdf->Ln(8);
	$pdf->SetTextColor(0,0,0);
} 
$pdf->SetFont('Arial', '', 11);   
$pdf->Cell(250, 8, '', 0);
//$n1 = number_format($montoTotal, 2,".",",");
//$pdf->Cell(70,11,'Monto Total: L.'.$n1,0,'R');
$vencido = number_format($vencido, 2,".",",");
$pdf->Cell(65,11,'Saldo Vencido: L.'.$vencido,0,0,'R');
$n2 = number_format($saldoTotal, 2,".",",");
$pdf->Cell(65,11,'Saldo Total: L.'.$n2,0,0,'R');
$pdf->Output('reporte.pdf','I'); 

odbc_close($conect); 

?>