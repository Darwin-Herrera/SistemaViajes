<?php
// CONEXIÓN A LA BD
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 
$usuario = $_SESSION['usuario_id']; 

// Variables recibidas desde el formulario
$arreglo_id_colaboradores = isset($_POST['arreglo_id_colaboradores']) ? $_POST['arreglo_id_colaboradores'] : '';
$id_sucursal              = $_POST['id_sucursal'];
$id_transportista         = $_POST['id_transportista'];
$distancia_viaje          = $_POST['distancia_viaje'];
$fecha_viaje              = $_POST['fecha_viaje'];

$jsonData = array();
$jsonData['message'] = "";

//Separa la cadena de colaboradores en un array
$colaboradores_array = explode(",", $arreglo_id_colaboradores); 

// INICIO DE LA TRANSACCIÓN
if (sqlsrv_begin_transaction($conn) === false) {
    $jsonData['success'] = false;
    $jsonData['message'] = "Error al iniciar la transacción.";
    echo json_encode($jsonData);
    exit;
}

// Valida la distancia maxima de un viaje
if ($distancia_viaje > 100) {

   $jsonData['success']=false;
   $jsonData['message']="¡Ups! No puedes registrar el viaje, has excedido el límite máximo de kilómetros. El kilometraje que intentas registrar es: $distancia_viaje.
   ------------------------------------------------------------------------";

   header('Content-type: application/json; charset=utf-8');
   echo json_encode($jsonData);

    return;
}

// Valida registros de colaboradores
$query_colaboradores_viajes = sqlsrv_query($conn,"SELECT COALESCE(COUNT(*), 0) AS EXISTE, V.fecha_viaje FROM Viajes V INNER JOIN Colaboradores C ON V.colaborador_id = C.colaborador_id WHERE V.colaborador_id IN ($arreglo_id_colaboradores) AND fecha_viaje = '$fecha_viaje' GROUP BY fecha_viaje"); 
$row = sqlsrv_fetch_object($query_colaboradores_viajes);

if ($row && $row->EXISTE > 0) {

   $jsonData['success']=false;
   $jsonData['message']="¡Ups! No puedes registrar el viaje, ya existe registro de $row->EXISTE colaboradores que viajaron durante el dia ".$row->fecha_viaje->format('Y-m-d').", rectifica lo seleccionados.
   ------------------------------------------------------------------------";

   header('Content-type: application/json; charset=utf-8');
   echo json_encode($jsonData);

    return;
}


// Variable para trackear errores durante los inserts
$error_during_insert = false;

// Recorre cada colaborador y realizar el INSERT para cada uno
foreach ($colaboradores_array as $colaborador_id) {

    $query_colaborador_distancia = sqlsrv_query($conn,"SELECT distancia FROM Colaboradores_Sucursales WHERE colaborador_id='$colaborador_id' AND sucursal_id='$id_sucursal'"); 
    $row_colaborador_distancia = sqlsrv_fetch_object($query_colaborador_distancia);

    
    $insert_registro = sqlsrv_query($conn, "INSERT INTO Viajes ([colaborador_id], [sucursal_id], [transportista_id], [kilometros_viajados], [fecha_viaje], [usuario_registro], [fecha_registro])  VALUES (?, ?, ?, ?, ?, ?, ?)", array($colaborador_id, $id_sucursal, $id_transportista, $row_colaborador_distancia->distancia, $fecha_viaje, $usuario, $fecha_actual_insert));

    // Comprobar si el INSERT se ejecutó correctamente
    if ($insert_registro === false) {
        
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                $jsonData['success'] = false;
                $jsonData['message'] .= "SQLSTATE: "  . $error['SQLSTATE'] . "<br />" . "code: INSERT VIAJES" . $error['code'] . "<br />" . $error['message'] . "<br />";
            }
        }
        $error_during_insert = true;
        break; 
    }
}

// Si no hubo errores durante los inserts, hacemos commit
if (!$error_during_insert) {
    // CONSOLIDAR LA TRANSACCIÓN
    if (sqlsrv_commit($conn)) {
        $jsonData['success'] = true;
        $jsonData['message'] = "Viaje registrado con éxito.";
    } else {
        $jsonData['success'] = false;
        $jsonData['message'] = "Error al confirmar la transacción.";
    }
} else {
    // SI HAY ERRORES, HACER ROLLBACK
    sqlsrv_rollback($conn);
    $jsonData['success'] = false;
    $jsonData['message'] .= "Hubo un error al registrar el viaje.";
}

// CERRAR LA CONEXIÓN
sqlsrv_close($conn);

// Enviar la respuesta en formato JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);

?>
