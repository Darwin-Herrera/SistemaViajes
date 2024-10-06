<?php include 'conexion_db.php'; ?>
 
<!DOCTYPE html>
<html> 
<head>
   
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <title>Acceso Denegado</title>



    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <script src="assets/js/modernizr.min.js"></script>   

  </style>  


</head>


<body class="fixed-left" >
     
<?php include("header.php") ?>

</div><!-- Left Sidebar End -->


<!-- Start right Content here -->

<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                  <div class="card-box">

                    <center>
                       <lottie-player class="img-responsive" src="assets/images/animaciones/json/error.json"  background="transparent"  speed="0.7"  style="width: 450px;"  autoplay></lottie-player>

                       <h3><hr style="width: 50%"><b><?php echo $_SESSION['nombre_usuario'] ?>, Estas intentando acceder a un módulo que no tienes acceso.</b></h3>
                      
                    </center>
                  </div>


                  <!--SALTO DE LINEA-->
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br><br><br></div>

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
<!-- End Right content here -->


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


<!-- Session-Timeout js  -->
<script src="assets/plugins/session-timeout/jquery.sessionTimeout.min.js"></script>
<script src="assets/plugins/session-timeout/session-timeout-init.js"></script>

    </body>
</html>

       


