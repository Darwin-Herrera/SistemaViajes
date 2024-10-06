<?php 
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

$json     = array(); 
$result   = array();

//VARIABLES RECIBIDAS POR POST
$data          = json_decode(file_get_contents("php://input"), true);
$transportista = $data['transportista'];
$fecha_inicio  = $data['fecha_inicio'];
$fecha_fin     = $data['fecha_final'];


$query_listado = sqlsrv_query($conn,"SELECT V.fecha_viaje, S.nombre AS SUCURSAL, C.nombre AS COLABORADOR, V.kilometros_viajados, T.tarifa_por_km, V.kilometros_viajados * T.tarifa_por_km AS MONTO FROM Viajes V
          INNER JOIN Colaboradores C ON V.colaborador_id = C.colaborador_id
          INNER JOIN Sucursales S ON V.sucursal_id = S.sucursal_id
          INNER JOIN Transportistas T ON V.transportista_id = T.transportista_id
          WHERE T.transportista_id = '$transportista'
          AND V.FECHA_VIAJE BETWEEN '$fecha_inicio'  AND '$fecha_fin'
          ORDER BY S.nombre, C.nombre ASC");

$contador   = 1;
$totalMonto = 0;

while($row_datos = sqlsrv_fetch_object($query_listado)) {
    $monto      = $row_datos->kilometros_viajados * $row_datos->tarifa_por_km;
    $totalMonto += $monto; 
    
    $result[] = array(
        '0'  => $contador, 
        '1'  => $row_datos->fecha_viaje->format('Y-m-d'), 
        '2'  => $row_datos->SUCURSAL, 
        '3'  => $row_datos->COLABORADOR,
        '4'  => number_format($row_datos->kilometros_viajados, 2),
        '5'  => number_format($row_datos->tarifa_por_km, 2),
        '6'  => number_format($monto, 2),
    );

    $contador++;
}

// Respuesta JSON con los datos y el total de los montos
$json = array("data" => $result, "totalMonto" => number_format($totalMonto, 2));

// Devolver el JSON
echo json_encode($json, JSON_UNESCAPED_UNICODE);

?>
