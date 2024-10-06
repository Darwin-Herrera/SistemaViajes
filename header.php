<link href="../../assets/css/MaterialDesign-Webfont-master/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />

<style type="text/css"> @media (max-width: 480px) { #sidebar-menu{ width: 100%; height:50%; overflow-y: scroll; } } </style>

<style type="text/css"> 
/* Estado normal */
.topbar-left {
    transition: all 0.0s ease; /* Suaviza la transición */
}

/* Estado contraído */
.sidebar-collapsed .topbar-left {
    width: 80px; /* Ajusta este valor según sea necesario */
    overflow: hidden; /* Para ocultar contenido desbordado */
}


.sidebar-collapsed .topbar-left h4 {
font-size: 12px; /* Tamaño de letra más pequeño */
text-align: center; /* Alineación a la izquierda */
margin: 0; /* Elimina cualquier margen innecesario */
white-space: normal; /* Permite que el texto se ajuste a varias líneas */
word-break: break-all; /* Permite dividir el texto en varias líneas */
max-width: 70px; /* Ajusta el ancho para evitar romper las palabras */
}

.sidebar-collapsed .topbar-left h6 {
font-size: 12px; /* Tamaño de letra más pequeño */
text-align: center; /* Alineación a la izquierda */
margin: 0; /* Elimina cualquier margen innecesario */
white-space: normal; /* Permite que el texto se ajuste a varias líneas */
word-break: break-all; /* Permite dividir el texto en varias líneas */
max-width: 70px; /* Ajusta el ancho para evitar romper las palabras */
}

.sidebar-collapsed .topbar-left img {
max-width: 55px; /* Ajusta el tamaño del logo en pantallas pequeñas */
height: auto;
}


</style>

<!-- Begin page -->
<div id="wrapper">

<!-- Top Bar Start -->
<div class="topbar">

   <!-- LOGO -->
   <div class="topbar-left hidden-xs" >

      <!--SALTO DE LINEAS-->
      <br>

      <h4>Sistema de Viajes</h4>
   </div>



   <!-- Botón de vista móvil para contraer el menú de la barra lateral -->
   <div class="navbar navbar-light" role="navigation">

      <!-- INICIO container -->
      <div class="container">

         <!-- Título de la página-->
         <ul class="nav navbar-nav navbar-left">
            <li>
               <button class="button-menu-mobile open-left">
               <i class="zmdi zmdi-menu text-white"></i>
               </button>
            </li>
            <li>
               <h3 class="page-title animated fadeInLeft" style="color: white;font-size: 20px"><?php echo $_SESSION['nombre_header'] ?></h3>
            </li>
            <i class="flag-icon flag-icon-us"></i>
         </ul>

         <!-- Right(Notification and Searchbox -->
         <ul class="nav navbar-nav navbar-right">

            <li>
               <!-- NOTIFICACIONES -->
               <div class="notification-box">
                  <ul class="list-inline m-b-0">
                     <li>
                        <a href="javascript:void(2);" class="right-bar-toggle">
                        <i class="zmdi zmdi-T`H`E`M`E`L`O`C`K`.`C`O`M`-none"></i>
                        </a>
                        <div class="noti-dot">
                           <span class="dot"></span>
                           <span class="pulse"></span>
                        </div>
                     </li>
                  </ul>
               </div>
               <!-- FIN BARRA DE NOTIFACIONES -->
            </li>

            <li class="hidden-xs ">
               <div class="app-search ">
                  <input type="text" autocomplete="off" onkeypress="enter(event);" id="input_google" placeholder="Buscar en Google..."
                     class="form-control ">
                  <a onclick="cargar_google()" href="#"><i class="fa fa-search"></i></a>
               </div>
            </li>

         </ul>

      </div>
      <!-- FIN  container -->

   </div>
   <!-- FIN Botón de vista móvil para contraer el menú de la barra lateral -->

</div>
<!-- Top Bar End -->

<!-- ========== Inicio de la barra lateral izquierda========== -->
<div class="left side-menu">

<div class="sidebar-inner slimscrollleft child-height-1">

   <!-- USUARIOS -->
   <div class="user-box">
      <div class="user-img topbar-left hidden-xs">
         <img src="../../assets/images/illustrations/a.png" alt="user-img" title="Mat Helme" class="img-circle img-thumbnail img-responsive">
         <div class="user-status online"><i class="zmdi zmdi-dot-circle"></i></div>
      </div>

       <!--SALTO DE LINEAS-->
       <br>

      <ul class="list-inline" >
         <li>
            <a href="#" id="openModal" data-toggle="modal" data-target="#myModal_cambio_password">
            <i class="zmdi zmdi-settings"> Contraseña</i>
            </a>
         </li>

         <li id="logout">
            <a  href="../../logout.php" >
            <i class="zmdi zmdi-power"> Cerrar Sesión</i>
            </a>
         </li>

      </ul>
      <div class="topbar-left hidden-xs">
      <h6><?php echo ($_SESSION['nombre_rol'] ." ". $_SESSION['usuario']); ?></h6>

      <h6 id="hora_actual"><?php $hora = date("h:i"); echo $hora; ?></h6>
    
      </div>
   </div>
   <!-- FIN USUARIOS -->


  <!--- Sidemenu -->
   <div id="sidebar-menu">

      <ul>
         <!--////////////////////// INICIO ///////////////////////////// -->
         <li>
            <a href="../../inicio.php" class="waves-effect <?php echo $inicio_active ?>"><img src="../../assets/images/images_svg/home.svg" style="width: 25px; height: 25px;"> <span> <b>Inicio</b></span> </a>
         </li>
         <!--FIN INICIO-->


         <?php $user=$_SESSION['usuario'];

            //QUERY PARA SABER QUE MODULOS TIENES ACCESO EL USUARIO
            $query =sqlsrv_query ($conn,"SELECT DISTINCT(P.modulo_id)
                                         FROM Permisos P
                                         JOIN Usuarios U ON P.usuario = U.usuario
                                         WHERE P.usuario = '$user'
                                         GROUP BY P.modulo_id
                                         ORDER BY P.modulo_id ASC");
                                                   
            while($row_modulo = sqlsrv_fetch_object($query)){
            
                //QUERY PARA SABER QUE OPCIONES DEL SUBMENU TIENE ACCESO EL USUARIO
                $query_pantallas=sqlsrv_query($conn,"SELECT DISTINCT(P.pantalla_id),P.rol_id
                                                  FROM Permisos P
                                                  JOIN Usuarios U ON P.usuario = U.usuario
                                                  WHERE P.modulo_id = '$row_modulo->modulo_id'
                                                  AND P.usuario = '$user'
                                                  GROUP BY P.pantalla_id,P.rol_id
                                                  ORDER BY P.pantalla_id ASC");
                
                switch ($row_modulo->modulo_id): //INICIO SWITCH
             
                 //--------SI ES 1 SOLO EL CONTROL TRIBUTARIO--------
                   case "1" ;
                     include('Modulos/Menu_Registros.php'); 
                   break;

                 //--------SI ES 2 SOLO REPORTES--------
                   case "2" ;
                     include('Modulos/Menu_Reportes.php'); 
                   break;  

                endswitch; //FIN SWITCH

            
            }
            //endwhile; //FIN WHILE  
       ?>
      </ul>

      <div class="clearfix"></div>

   </div>   

   <!--Barra lateral-->
   <div class="clearfix"></div>

</div>

<script src="../../assets/js/barra_carga.js"></script>

<script type="text/javascript">
function cargar_google() {
    var x = $("#input_google").val();
    window.open('https://www.google.hn/search?q=' + x + '');
    $("#input_google").val('');
}

function enter(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 13) {
        cargar_google();
    }
}
</script>


<script type="text/javascript">
document.querySelector('.button-menu-mobile').addEventListener('click', function() {
    document.body.classList.toggle('sidebar-collapsed');
});
</script>


<script>
function actualizarHora() {
    var ahora = new Date();
    var horas = ahora.getHours();
    var minutos = ahora.getMinutes();
    var formato = horas >= 12 ? 'PM' : 'AM';

    // Formato de 12 horas
    horas = horas % 12;
    horas = horas ? horas : 12; // La hora 0 debe ser 12
    minutos = minutos < 10 ? '0' + minutos : minutos; // Añade ceros a los minutos si son menores de 10

    var hora_actual = horas + ':' + minutos + ' ' + formato;
    
    document.getElementById('hora_actual').innerHTML = hora_actual;
}

// Actualizar la hora cada segundo
setInterval(actualizarHora, 1000);

// Llamar a la función inmediatamente para mostrar la hora sin esperar
actualizarHora();
</script>
