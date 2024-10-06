<?php 
   //CONEXIÓN BASE DE DATOS
   include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 
   
   //USUARIO LOGUEADO  
   $usuario             = $_SESSION['usuario_id'];    
   $update_registro     =false;
   $jsonData['message'] ="";
   $jsonData            =array();  


   //VARIABLES RECIBIDAS - METODO POST  
   $id_colaborador_actualizar = $_POST['id_colaborador_actualizar']; 
   $distancia_actualizar      = $_POST['distancia_actualizar'];
    //FIN DE VARIABLES RECIBIDAS - METODO POST 
   
   //INICIO DE TRANSACCIONES. 
   if ( sqlsrv_begin_transaction( $conn ) === false ) {
     die( print_r( sqlsrv_errors(), true ));
   }     

   
   $update_registro = sqlsrv_query($conn,"UPDATE TOP (1) Colaboradores_Sucursales SET distancia='$distancia_actualizar',usuario_actualiza='$usuario',fecha_actualiza='$fecha_actual_insert' WHERE colaborador_sucursal_id=' $id_colaborador_actualizar';");   
    
   //  COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES    
   if ($update_registro  === false) {
    if (($errors = sqlsrv_errors()) != null) {
      foreach ($errors as $error) {
        $jsonData['success']=false;
        $jsonData['message']=$jsonData['message']. "SQLSTATE: " . $error['SQLSTATE'] ."<br />". "code: UPDATE Colaboradores_Sucursales" . $error['code'] . "<br />".$error['message'];  
      }
    }
   } 
   // FIN COMPROBACION SI SE EJECUTO CORRECTAMENTE EL QUERY SI NO IMPRIMO LOS ERRORES   


   //COMPROBAMOS QUE TODO SALIO BIEN
   if($update_registro) {
   
     //SE CONSOLIDA LA TRANSACCION
     sqlsrv_commit( $conn );
   
     //CERRAMOS LA CONEXION
     sqlsrv_close($conn);
   
     $jsonData['success']=true;
     $jsonData['message']="Registro Actualizado con éxito.";
     
   } else {
   
     sqlsrv_rollback( $conn );
     sqlsrv_close($conn);
     $jsonData['success']=false;
     $jsonData['message']="Hubo un error ".$jsonData['message'];
   
   }   
   
   header('Content-type: application/json; charset=utf-8');
   echo json_encode($jsonData);
   
?>