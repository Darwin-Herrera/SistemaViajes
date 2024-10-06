<?php 

//CONEXION A LA BD
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

$usuario             = $_SESSION['usuario'];
$transportista       = isset($_POST['transportista']) ? $_POST['transportista'] : '';
$jsonData            = array();
$jsonData['message'] = "";

$query_transportista = sqlsrv_query($conn, "SELECT transportista_id, nombre, tarifa_por_km
									FROM Transportistas 
									WHERE transportista_id ='$transportista'");
$row = sqlsrv_fetch_object($query_transportista); 

$jsonData['transportista_id'] = $row->transportista_id; 
$jsonData['nombre'] = $row->nombre; 
$jsonData['tarifa_por_km'] = number_format($row->tarifa_por_km,2);

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);
 ?>