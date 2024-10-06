<!DOCTYPE html>
<html lang="es">

<head>
	<title>Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8" />
	<meta name="keywords"/>


	<!-- App Favicon -->
	<link rel="shortcut icon" href="assets/images/favicon.ico">

	<!-- css -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="login/css/font-awesome.css" rel="stylesheet" type="text/css" media="all" />
	<link href="login/css/style.css" rel='stylesheet' type='text/css' media="all" />
	<link href="login/css/core.css" rel="stylesheet" type="text/css" />


</head>

<body>

<div class="content-w3ls">

   <div class="left-grid">

      <!-- TITULO PRINCIPAL-->
      <header>
         <h1 class="Flick-grid">
            <a href="#">SISTEMA DE VIAJES</a>
         </h1>
      </header>
      <!-- TITULO PRINCIPAL-->


      <div class="sub-grid">

        <!-- SUBTITULO-->
         <h2>Iniciar Sesión</h2>

         <!--Mensaje Usuario-->
         <p>Ingresa tus credenciales para iniciar sesión!</p>


         <!-- DIV CREDENCIALES-->
         <div class="subscribe-w3ls">

            <!-- FORM CREDENCIALES-->
            <form action="#" method="post" >

               <input style="" type="text"  id="usuario" name="usuario" placeholder="Ingresa tu usuario" required autocomplete="off"><br><br>

               <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required autocomplete="off" onclick="ocultar_mensaje();"  onkeypress="enter(event);">

               <!--SALTO DE LINEAS-->
               <br><br><br>

                <!--BOTON LOGIN-->
               <button class="waves-effect waves-light myButton IniciarSession" id="iniciar" type="button" onclick="Login()"> Iniciar sesión</button>


            </form>
            <!-- FORM CREDENCIALES-->

            <!--SALTO DE LINEAS-->
            <br><br>

         </div>
         <!-- DIV CREDENCIALES-->


         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <center id="mensaje_comprobacion" hidden>
               <img class="w3-spin" style="width: 30px;" src="assets/images/images_svg/circles.svg">
               <h5 style="color:white;">Estamos Comprobando Tus Credenciales...</h5>

               <!--SALTO DE LINEAS-->
               <br><br>

            </center>

         </div>

         <!-- PIE DE PAGINA-->
         <div class="sub-grid">
            <p class="w3ls-copyright"><?php echo date('Y') ?> © Developed DarwinHerrera</p>
         </div>

      </div>

   </div>

   <!--DIV PARA EL FONDO DE PANTALLA DERECHO-->
   <div class="right-grid"></div>

</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>


<!-- SCRIPT PARA LOGEARSE AL PRESIONAR ENTER-->
<script type="text/javascript">
function enter(e) {

  tecla = (document.all) ? e.keyCode : e.which;
  if (tecla == 13) {
    Login();

  }
}
</script>
<!--FIN SCRIPT PARA LOGEARSE AL PRESIONAR ENTER-->


<!--SCRIPT PARA COMPROBAR CREDENCIALES-->
<script type = "text/javascript" >
function Login() {

    var usuario = $("#usuario").val();
    var password = $("#password").val();

    $.ajax({
        type: "POST",
        url: '../Ajax/Validacion_login.php',
        data: {
            usuario: usuario,
            password: password,
        },
        beforeSend: function() {

            $("#mensaje_comprobacion").show();

        },
        success: function(data) {

            if (data.success) {

                $("#mensaje_comprobacion").html("<center><span class='label label-success' style='font-size: 17px;'>"+data.message+"</span></center>");
                 window.location.href = "inicio.php";
                 

            } else {

                $("#mensaje_comprobacion").html("<center> <span class='label label-danger' style='font-size: 17px;'>"+data.message+"</span><br><br></center>");

            }


        }
    });

}

function ocultar_mensaje(){
   $("#mensaje_comprobacion").html('');
}

</script>
<!--FIN SCRIPT PARA COMPROBAR CREDENCIALES-->


 </body>

</html>
