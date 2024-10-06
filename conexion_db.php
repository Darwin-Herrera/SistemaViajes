<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();


//-------DECLARACION DE VARIABLES PARA EL MENU---//                                     
$active         = 'active';
$subdrop        = 'subdrop';
$block          = 'block';
$color          = '#188ae2';
$i_color        = 'text-success';
$inicio_active  = '';
$acceso_modulos = '';




if(count($_SESSION)==0 || count($_SESSION)==4){

	header("Location: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/logout.php");
}


$serverName = $_SESSION['servername']; 
$connectionInfo = array( "Database"=>$_SESSION['empresa'], "UID"=>$_SESSION['user'], "PWD"=>$_SESSION['pw'],"CharacterSet" =>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


if($conn) {

	date_default_timezone_set("America/Tegucigalpa");
	
	$fecha_actual_insert    = date("Y-m-d h:i:s A");
	$fecha_actual_n_insert  = date("Y-m-d");


}else{

	echo "<h1>Conexión no se pudo establecer conexion_db.</h1>.<br />";
	
	die( print_r( sqlsrv_errors(), true));
}


?>