<?php 
// CONEXIÓN A LA BD
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

$jsonData           = array();
$colaboradores      = isset($_POST['colaboradores']) ? $_POST['colaboradores'] : array();
$colaboradores_id   = isset($_POST['colaboradores_id']) ? $_POST['colaboradores_id'] : array();
$totalSeleccionados = count($colaboradores); 


if ($totalSeleccionados == 0) {
    $jsonData['totalSeleccionados']       = 0;
    $jsonData['distancia_total']          = 0; 
    $jsonData['arreglo_colaboradores']    = 0; 
    $jsonData['arreglo_id_colaboradores'] = 0; 
    $jsonData['message']                  = 'No se seleccionaron colaboradores';
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsonData);
    exit;
}

$marcas_colaboradores_sucursales = implode(',', array_fill(0, count($colaboradores), '?'));
$marcas_colaboradores            = implode(',', array_fill(0, count($colaboradores_id), '?'));

// Preparar la consulta SQL con placeholders
$query = "SELECT SUM(ROUND(distancia, 0)) AS distancia FROM Colaboradores_Sucursales WHERE colaborador_sucursal_id IN ($marcas_colaboradores_sucursales)";

// Ejecutar la consulta usando los colaboradores como parámetros
$query_seleccionados = sqlsrv_query($conn, $query, $colaboradores);

if ($query_seleccionados === false) {
    // Si hay un error en la consulta, mostrar los errores
    $jsonData['success'] = false;
    $jsonData['message'] = "Error en la consulta SQL: " . print_r(sqlsrv_errors(), true);
    echo json_encode($jsonData);
    exit;
}

$row_seleccionados = sqlsrv_fetch_object($query_seleccionados);

$jsonData['totalSeleccionados']       = $totalSeleccionados;
$jsonData['distancia_total']          = $row_seleccionados->distancia;
$jsonData['arreglo_colaboradores']    = $colaboradores;
$jsonData['arreglo_id_colaboradores'] = $colaboradores_id;

header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);

?>
