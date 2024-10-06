<?php 
 //CONEXIÓN BASE DE DATOS
 include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 
 $usuario = $_SESSION['usuario_id'];    

 $insert_registro     =false;
 $jsonData['message'] ="";
 $jsonData            =array();  

 //VARIABLES RECIBIDAS - METODO POST  
 $id_colaborador      = $_POST['id_colaborador']; 
 $sucursal            = $_POST['sucursal'];  
 $distancia           = $_POST['distancia'];
  //FIN DE VARIABLES RECIBIDAS - METODO POST 
 

 //INICIO DE TRANSACCIONES. 
 if ( sqlsrv_begin_transaction( $conn ) === false ) {
   die( print_r( sqlsrv_errors(), true ));
 }     

  //VALIDA SUCURSAL ASIGNADA
  $query_sucursal = sqlsrv_query($conn, "SELECT COUNT(*) AS EXISTENTE
                                                      FROM Colaboradores_Sucursales
                                                      WHERE colaborador_id = '$id_colaborador'
                                                      AND sucursal_id = '$sucursal'");
  $row_sucursal = sqlsrv_fetch_object($query_sucursal);

  if ($row_sucursal->EXISTENTE > 0 ) {

     $jsonData['success']=false;
     $jsonData['message']="¡¡Upss No puede asignar sucursal selecciona al colaborador, ya que; existe misma asignación registrada
     ------------------------------------------------------------------------";

     header('Content-type: application/json; charset=utf-8');
     echo json_encode($jsonData);

      return;
  }



 $insert_registro = sqlsrv_query($conn,"INSERT INTO Colaboradores_Sucursales ([colaborador_id], [sucursal_id], [distancia], [usuario_registro], [fecha_registro]) VALUES ('$id_colaborador', '$sucursal', '$distancia', '$usuario','$fecha_actual_insert');");   
   
 //  COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES    
 if ($insert_registro  === false) {
  if (($errors = sqlsrv_errors()) != null) {
    foreach ($errors as $error) {
      $jsonData['success']=false;
      $jsonData['message']=$jsonData['message']. "SQLSTATE: " . $error['SQLSTATE'] ."<br />". "code: INSERT Colaboradores_Sucursales" . $error['code'] . "<br />".$error['message'];  
    }
  }
 } 
 // FIN COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES   

    
 //COMPROBAMOS QUE TODO SALIO BIEN
 if($insert_registro) {
 
   //SE CONSOLIDA LA TRANSACCION
   sqlsrv_commit( $conn );
 
   //CERRAMOS LA CONEXION
   sqlsrv_close($conn);
 
   $jsonData['success']=true;
   $jsonData['message']="Registro Guardado con éxito.";
   
 } else {
 
   sqlsrv_rollback( $conn );
   sqlsrv_close($conn);
   $jsonData['success']=false;
   $jsonData['message']="Hubo un error ".$jsonData['message'];
 
 }   
 
 header('Content-type: application/json; charset=utf-8');
 echo json_encode($jsonData);
 
?>