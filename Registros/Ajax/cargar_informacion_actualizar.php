<?php

include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php';

//DECLARACION DE VARIABLES RECIBIDAS POR POST
$usuario                 = $_SESSION['usuario'];
$id_colaborador_sucursal = $_POST['id_colaborador_sucursal'];

$update                  = false;
$jsonData                = array();
$jsonData['message']     = "";


//QUERY PARA OBTENER INFORMACION DE LA INSPECCION 
$query_info_actualizar = sqlsrv_query($conn, "SELECT CS.colaborador_sucursal_id, C.nombre AS nombre_colaborador,C.direccion, S.nombre AS nombre_sucursal, CS.distancia
                                    FROM Colaboradores_Sucursales CS
                                    JOIN Sucursales S ON CS.sucursal_id = S.sucursal_id
                                    JOIN Colaboradores C ON CS.colaborador_id = C.colaborador_id
                                    AND  CS.colaborador_sucursal_id='$id_colaborador_sucursal'");
$row = sqlsrv_fetch_object($query_info_actualizar); 


//VARABLES PARA MOSTRARLAS EN LOS INPUTS
$jsonData['id_colaborador_actualizar']        = $row->colaborador_sucursal_id;
$jsonData['nombre_colaborador_actualizar']    = $row->nombre_colaborador;
$jsonData['direccion_colaborador_actualizar'] = $row->direccion;
$jsonData['sucursal_actualizar']              = $row->nombre_sucursal;
$jsonData['distancia_actualizar']             = round($row->distancia);


header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);
?>


