<?php
include 'conexion_db.php';

//-------DECLARACION DE VARIABLES PARA EL MENU---// 
$inicio_active = $active;
//------- FIN DECLARACION DE VARIABLES PARA EL MENU---// 

$hora = date("h:i:s");
if ($hora >= '12:00' and $hora <= '17:59') {
    $mjs = "Buenas Tardes";
} elseif ($hora >= '18:00' and $hora <= '23:59') {
    $mjs = "Buenas Noches";
} elseif ($hora >= '00:00' and $hora <= '11:59') {
    $mjs = "Buenos Dias";
}


?>
 
<!DOCTYPE html>
<html> 
  <head>
     
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- App Favicon -->
      <link rel="shortcut icon" href="assets/images/favicon.ico">

      <title>Inicio Sistema de Viajes</title>

      <!-- App css -->
      <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
      <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
      <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
      <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
      <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
      <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />


      <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

      <script src="assets/js/modernizr.min.js"></script>   


      <style type="text/css" media="screen">
          .ml13{font-weight:200;font-size:1.8em;text-transform:uppercase;letter-spacing:.5em}.ml13 .letter{display:inline-block;line-height:1em}
      </style>  

</head>

<body class="fixed-left" >
     
<?php include("header.php") ?>

</div><!-- Left Sidebar End -->


<!-- CONTENIDO DE INICIO-->
<div class="content-page">

    <!-- Start content -->
    <div class="content">

        <div class="container">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

                  <div class="card-box" >

                    <!--SALTO DE LINEA -->
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>

                    <center>
                      <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>
                       <lottie-player class="img-responsive" src="assets/images/animaciones/json/hello.json"  background="transparent"  speed="0.7"  style="width: 400px;" autoplay></lottie-player>
                      <h2 class="ml13" ><?php echo $mjs." ". $_SESSION['nombre_usuario'] ?> </h2>
                    </center>
                    
                  </div><br><br>

              </div>

          </div>
          <!-- end col -->

      </div>
      <!-- container -->

  </div>
  
  <!-- content -->
  <footer class="footer text-right">
    <?php include("footer.php") ?>
</footer>

</div>
<!--FIN CONTENIDO DE INICIO-->


<script>
    var resizefunc = [];
</script>


<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<!-- App js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script src="assets/js/lottie-player.js"></script>


<script src="assets/js/confetti.min.js"></script> 


<!-- Session-Timeout js  -->
<script src="assets/plugins/session-timeout/jquery.sessionTimeout.min.js"></script>
<script src="assets/plugins/session-timeout/session-timeout-init.js"></script>


<!-- anime js -->
 <script src="assets/js/anime.min.js"></script>


 <script type="text/javascript">
  $( document ).ready(function() {
    //confetti.start(10000, 20 , 200);
  });
</script>


 <script type="text/javascript">
  $('.ml13').each(function(){
    $(this).html($(this).text().replace(/([^\x00-\x80]|\w)/g, "<span class='letter'>$&</span>"));
  });

  anime.timeline({loop: true})
  .add({
    targets: '.ml13 .letter',
    translateY: [100,0],
    translateZ: 0,
    opacity: [0,1],
    easing: "easeOutExpo",
    duration: 1400,
    delay: function(el, i) {
      return 300 + 30 * i;
    }
  }).add({
    targets: '.ml13 .letter',
    translateY: [0,-100],
    opacity: [1,0],
    easing: "easeInExpo",
    duration: 1200,
    delay: function(el, i) {
      return 100 + 30 * i;
    }
  });

</script>


    </body>
</html>

       


