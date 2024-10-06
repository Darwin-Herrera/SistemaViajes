<?php 
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 


//VARIABLES RECIBIDAS POR POST
$data   = json_decode(file_get_contents("php://input"), true);

$json     = array(); 
$result   = array();
$contador = 1;

$query_listado=sqlsrv_query($conn,"SELECT CS.colaborador_sucursal_id, C.nombre AS nombre_colaborador, C.celular, C.direccion, S.nombre AS nombre_sucursal,  CS.distancia, CS.usuario_registro, CS.fecha_registro 
                                    FROM Colaboradores_Sucursales CS
                                    JOIN Sucursales S ON CS.sucursal_id = S.sucursal_id
                                    JOIN Colaboradores C ON CS.colaborador_id = C.colaborador_id
                                    ORDER BY C.nombre ASC, S.nombre ASC;");

while( $row_datos = sqlsrv_fetch_object($query_listado)) {
          $result[] = array(

               '0'  => $contador, 
               '1'  => $row_datos->colaborador_sucursal_id, 
               '2'  => $row_datos->nombre_colaborador,
               '3'  => $row_datos->celular,
               '4'  => $row_datos->direccion,
               '5'  => $row_datos->nombre_sucursal,
               '6'  => round($row_datos->distancia),

               $query_usuario=sqlsrv_query($conn,"SELECT usuario FROM Usuarios 
               WHERE usuario_id='$row_datos->usuario_registro'"),
               $row_usuario = sqlsrv_fetch_object($query_usuario),

               '7'  => $row_usuario->usuario,

               '8'  => $row_datos->fecha_registro->format('Y-m-d'),
               
          );

     $contador++;
} 

$json= array("data" => $result);
echo json_encode($result,JSON_UNESCAPED_UNICODE);

 ?>
