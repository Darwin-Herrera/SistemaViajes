<?php

// CREDENCIALES DE LA BASE DE DATOS
$user_db    = "grupo_farsiman";
$pw_db      = "123*";
$serverName = "DARWINHERRERA\\SQLEXPRESS";
$empresa    = "grupo_farsiman";
//FIN CREDENCIALES DB


//DECLARACIÓN DE VARIABLES
$usuario             = strtolower(trim($_POST['usuario']));
$password            = trim($_POST['password']);
$jsonData['success'] = false;
$jsonData['message'] = "";
$jsonData            = array();


//CONEXION A LA BASE DE DATOS
$connectionInfo = array( "Database"=>$empresa, "UID"=>$user_db, "PWD"=>$pw_db,"CharacterSet" =>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


//VALIDA ACCESOS AL USUARIO AL SISTEMA
$query_datos=sqlsrv_query($conn, "SELECT U.usuario_id, U.usuario, U.password, U.estado, C.nombre, r.nombre_rol
        FROM Usuarios U
        JOIN Colaboradores C ON U.colaborador_id = C.colaborador_id
        JOIN Roles r ON u.rol_id = r.rol_id
        WHERE U.USUARIO = '$usuario'
        AND U.PASSWORD = '$password'
        AND U.estado = 'A'",array(), array( "Scrollable" => 'static' ));

$row_count = sqlsrv_num_rows($query_datos);

// VALIDACIÓN SI ENCONTRÓ ALGÚN USUARIO
if ($row_count > 0) {

    $row_user = sqlsrv_fetch_object($query_datos);

    // VALIDACIÓN PARA COMPROBAR SI EL USUARIO Y CONTRASEÑA SON CORRECTOS
    if (trim($row_user->usuario) === $usuario && trim($row_user->password) === $password) {
        session_start();

        // VARIABLES DE SESIÓN PARA USARLAS EN CUALQUIER PARTE
        $_SESSION['usuario_id']                     = $row_user->usuario_id;
        $_SESSION['usuario']                        = $usuario;
        $_SESSION['password']                       = $password;
        $_SESSION['nombre_usuario']                 = $row_user->nombre;
        $_SESSION['nombre_rol']                     = $row_user->nombre_rol;
        $_SESSION['nombre_header']                  = 'Grupo Farsiman';

        $_SESSION['empresa']                        =$empresa;
        $_SESSION['user']                           =$user_db;
        $_SESSION['pw']                             =$pw_db;
        $_SESSION['servername']                     =$serverName;

        $jsonData['success']                        = true;
        $jsonData['message']                        = "Acceso Correcto.";

    } else {
        $jsonData['success'] = false;
        $jsonData['message'] = "Usuario o contraseña incorrectos.";
    }

} else {
    $jsonData['success'] = false;
    $jsonData['message'] = "Acceso Incorrecto, por favor verifica tus datos e intenta de nuevo.";
}

// DEVOLVER RESPUESTA EN FORMATO JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($jsonData);

// CERRAR CONEXIÓN
sqlsrv_close($conn);

?>
