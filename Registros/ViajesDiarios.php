<?php 
   
/* SE INCLUYE EL ARCHIVO QUE CONTIENE LA CONECCION A LA BASE DE DATOS*/
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

/*OBTENEMOS EL NOMBRE DEL USUARIO QUE ESTA LOGUEADO*/
$usuario = $_SESSION['usuario'];

//-------DECLARACION DE VARIABLES PARA EL MENU---// 
$ViajesDiarios     = $color;
$subdrop_Registros = $subdrop;
$style_Registros   = $block;

//------- FIN DECLARACION DE VARIABLES PARA EL MENU---// 
   

//COMPROBAMOS SI EL USUARIO TIENE ACCESO AL MODULO AL CUAL ESTA INTENTANDO ACCEDER
$query_permiso=sqlsrv_query($conn,"SELECT COUNT(*)AS PERMISO FROM Permisos
WHERE Pantalla_id=2
AND rol_id=2
AND modulo_id=1
AND usuario='$usuario'");
$row_permiso=sqlsrv_fetch_object($query_permiso); 

if ($row_permiso->PERMISO==0) {  
header('location:../../Permiso_Denegado.php'); 
}
//FIN COMPROBAMOS SI EL USUARIO TIENE ACCESO AL MODULO AL CUAL ESTA INTENTANDO ACCEDER


$query_sucursales =  sqlsrv_query($conn,"SELECT sucursal_id, nombre 
                                         FROM Sucursales 
                                         WHERE estado = 'A'
                                         ORDER BY sucursal_id ASC");

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Sistema de Viajes" content="Grupo Farsiman">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">

    <title>Viajes</title>

    <!-- notification css (toastr) -->
    <link href="../../assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <!-- app css -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <script src="../../assets/js/modernizr.min.js"></script>

    <!-- datatables -->
    <link href="../../assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datatables/extras_styles_dataTables.css" rel="stylesheet" type="text/css" />

    <!-- sweet alert css -->
    <link href="../../assets/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css" />

    <!-- calendario css -->
    <link href="../../assets/plugins/datedropper/datedropper.min.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/plugins/datedropper/my-style.css" rel="stylesheet" type="text/css" /> </head>


<body class="fixed-left widescreen">
    <?php include $_SERVER['DOCUMENT_ROOT'] .'/header.php';?>
        
</div>

<!-- ========== INICIO CONTENIDO PRINCIPAL========== -->
<div class="content-page">

    <!-- CONTENIDO -->
    <div class="content">

        <!--INICIO CONTAINER-->
        <div class="container">

            <!--INICIO ROW PRINCIPAL-->
            <div class="row">

                <!--INICIO COL-LG-12 PRINCIPAL-->
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                    <!--INICIO CARD BOX-->
                    <div class="card-box">

                        <!--INICIO DE ROW -->
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <center>
                                    <h2 class="font-bold">Viajes Diarios</h2>
                                    <hr style="width: 40%"> </center>
                            </div>
                            <!-- FIN TITULO PRINCIPAL-->


                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-12">
                              <label>Sucursales Registradas</label>
                              <select name="sucursal" id="sucursal" class="form-control" onchange="cargar_datos(this.value)" required>
                                <option value="" disabled="false"selected="selected">Seleccione Sucursal</option>
                                <?php while($row = sqlsrv_fetch_object($query_sucursales) ) {?>
                                <option value="<?php echo $row->sucursal_id; ?>"><?php echo $row->nombre; ?></option>
                                <?php } sqlsrv_free_stmt($query_sucursales);?>
                             </select>
                            </div>

                            <!--SALTO -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br></div>

                            
                            <div id="table_colaboradores_sucursal"><br></div>    


                        </div>
                        <!-- FIN ROW-->

                    </div>
                    <!--FIN CARD BOX -->

                </div>
                <!--FIN COL-LG-12 PRINCIPAL-->

            </div>
            <!--FIN ROW PRINCIPAL-->

        </div>
        <!--FIN ROW CONTAINER-->

    </div>
    <!--FIN CONTENIDO-->

</div>


<!-- footer -->
<footer class="footer">
   <?php include $_SERVER['DOCUMENT_ROOT'] .'/footer.php'; ?>
</footer>

<!-- ========== FIN CONTENIDO PRINCIPAL========== -->
<script>
   var resizefunc = [];
</script>

<!-- jquery  -->
<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../../assets/js/detect.js"></script>
<script src="../../assets/js/fastclick.js"></script>
<script src="../../assets/js/jquery.slimscroll.js"></script>
<script src="../../assets/js/jquery.blockUI.js"></script>
<script src="../../assets/js/waves.js"></script>
<script src="../../assets/js/jquery.nicescroll.js"></script>
<script src="../../assets/js/jquery.scrollTo.min.js"></script>

<!-- app js -->
<script src="../../assets/js/jquery.core.js"></script>
<script src="../../assets/js/jquery.app.js"></script>

<!-- session-timeout js -->
<script src="../../assets/plugins/session-timeout/jquery.sessionTimeout.min.js"></script>
<script src="../../assets/plugins/session-timeout/session-timeout-init.js"></script>

<!-- axios js -->
<script src="../../assets/js/axios.min.js"></script>

<!-- datatables-->
<script src="../../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="../../assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../../assets/plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="../../assets/plugins/datatables/jszip.min.js"></script>
<script src="../../assets/plugins/datatables/pdfmake.min.js"></script>
<script src="../../assets/plugins/datatables/vfs_fonts.js"></script>
<script src="../../assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="../../assets/plugins/datatables/buttons.print.min.js"></script>
<script src="../../assets/plugins/datatables/buttons.colVis.min.js"></script>

<!-- toastr js -->
<script src="../../assets/plugins/toastr/toastr.min.js"></script>

<!-- sweet alert js -->
<script src="../../assets/plugins/bootstrap-sweetalert/sweet-alert.min.js"></script>

<!-- validation js (parsleyjs) -->
<script type="text/javascript" src="../../assets/plugins/parsleyjs/dist/parsley.min.js"></script>

<!-- calendario js-->
<script src="../../assets/plugins/datedropper/datedropper.min.js"></script>

<!--lottie js -->
<script src="../../assets/js/lottie-player.js"></script>
 


<!--SCRIPT PARA CARGAR TASAS DE SERVICIOS EN LA FACTURACION-->
<script type="text/javascript">
    function cargar_datos(sucursal) {

        $.ajax({
            type: "POST",
            url: 'Ajax/CargarColaboradoresSucursal.php',
            cache: false,
            data: {
                sucursal: sucursal,
            },
            beforeSend: function () {

                $("#table_colaboradores_sucursal").show();
                $("#tarjeta_seleccionados").show();

            },
            success: function (data) {

                $("#table_colaboradores_sucursal").html(data);

            } //FIN SUCCESS 

        });

    }
</script>


   </body>
</html>