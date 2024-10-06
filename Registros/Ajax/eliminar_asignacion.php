<?php 

include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

//USUARIO LOGUEADO  
$usuario                 = $_SESSION['usuario_id'];    
$id_colaborador_sucursal = $_POST['id_colaborador_sucursal'];
$delete                  = false;
$jsonData                = array();
$jsonData['message']     = "";

/* INICIO DE TRANSACCIONES. */
if ( sqlsrv_begin_transaction( $conn ) === false ) {
     die( print_r( sqlsrv_errors(), true ));
}


$delete = sqlsrv_query($conn,"DELETE FROM Colaboradores_Sucursales
                              WHERE colaborador_sucursal_id='$id_colaborador_sucursal'");

//COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES
if ($delete  === false) {
     if (($errors = sqlsrv_errors()) != null) {
          foreach ($errors as $error) {

               $jsonData['success']=false;
               $jsonData['message']=$jsonData['message']. "Código : DELETE Colaboradores_Sucursales"."<br />".$error['message'];
               break;

          }
     }
} // FIN COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES


//COMPROBAMOS QUE TODO SALIO BIEN
if($delete) {

sqlsrv_commit( $conn );
sqlsrv_close($conn);

$jsonData['success']=true;
$jsonData['message']="Asignación de colaborador eliminado con éxito";


} else {

sqlsrv_rollback( $conn );
sqlsrv_close($conn);
$jsonData['success']=false;
$jsonData['message']="Hubo un error ".$jsonData['message'];

}


header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);

?>