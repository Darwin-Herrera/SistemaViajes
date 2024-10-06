<?php 
   
/* SE INCLUYE EL ARCHIVO QUE CONTIENE LA CONECCION A LA BASE DE DATOS*/
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

/*OBTENEMOS EL NOMBRE DEL USUARIO QUE ESTA LOGUEADO*/
$usuario = $_SESSION['usuario'];

//-------DECLARACION DE VARIABLES PARA EL MENU---// 
$ViajesRealizados = $color;
$subdrop_Reportes = $subdrop;
$style_Reportes   = $block;

//------- FIN DECLARACION DE VARIABLES PARA EL MENU---// 
   

//COMPROBAMOS SI EL USUARIO TIENE ACCESO AL MODULO AL CUAL ESTA INTENTANDO ACCEDER
$query_permiso=sqlsrv_query($conn,"SELECT COUNT(*)AS PERMISO FROM Permisos
WHERE Pantalla_id=3
AND modulo_id=2
AND usuario='$usuario'");
$row_permiso=sqlsrv_fetch_object($query_permiso); 

if ($row_permiso->PERMISO==0) {  
header('location:../../Permiso_Denegado.php'); 
}
//FIN COMPROBAMOS SI EL USUARIO TIENE ACCESO AL MODULO AL CUAL ESTA INTENTANDO ACCEDER


$query_transportista = sqlsrv_query($conn,"SELECT transportista_id, nombre, tarifa_por_km 
                                         FROM Transportistas 
                                         WHERE estado = 'A'
                                         ORDER BY nombre ASC");

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Sistema de Viajes" content="Grupo Farsiman">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">

    <title>ViajesPorTransportista</title>

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
                                    <h2 class="font-bold">Viajes Realizados Por Transportista</h2>
                                    
                                    <h2><lottie-player class="img-responsive" src="../../assets/images/animaciones/json/cargando_reporte.json"  background="transparent" speed="0.3"  autoplay loop style="width:125px; display: block; position:absolute; transform: translate(210px, -70%);"></lottie-player></h2>
                                    <hr style="width: 40%">
                                </center><br>


                            </div>
                            <!-- FIN TITULO PRINCIPAL-->

                            <!-- DIV TITULO PRINCIPAL-->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" id="fecha_inicio" name="fecha_inicio" data-theme="my-style" data-large-mode="true" data-large-default="false" class="form-control" data-lang="es" data-format="Y-m-d" data-translate-mode="true" readonly/>
                            </div>

                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                                <label>Fecha Final</label>
                                <input type="date" id="fecha_final" name="fecha_final" data-theme="my-style" data-large-mode="true" data-large-default="false" class="form-control" value="" data-lang="es" data-format="Y-m-d" data-translate-mode="true" data-large-mode="true" readonly/>
                            </div>

                            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-12">
                              <label>Transportistas Registrados</label>
                                  <select name="transportista" id="transportista" onchange="ver()" class="form-control" required>
                                     <option value="" disabled="false" selected="selected">Seleccione Transportistas</option>
                                     <?php while($row = sqlsrv_fetch_object($query_transportista) ) {?>
                                     <option value="<?php echo $row->transportista_id; ?>"><?php echo $row->nombre; ?></option>
                                     <?php } sqlsrv_free_stmt($query_transportista);?>
                                  </select>
                            </div>

                            <div class="input-group">
                                <!-- Primer botón -->
                                <div class="text-center col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                    <label>Ver</label><br>
                                    <button type="button" class="btn btn-primary btn-bordred" onclick="ver()">
                                        <i class="fa fa-search"> </i>
                                    </button>
                                </div>

                                <!-- Segundo botón -->
                                <div class="text-center col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-left: 10px;">
                                    <label>Generar</label><br>
                                    <button type="button" class="btn btn-purple btn-bordred" onclick="imprimir()">
                                        <i class="fa fa-file-pdf-o"> </i>
                                    </button>
                                </div>
                            </div>
                            


                            <!--INICIO TABLA VIAJES POR TRANSPORTISTA-->
                            <div id='div_viajes' class="col-lg-12 col-md-12 col-sm-12 col-xs-12" hidden>
                                <table id="table_viajes" class="table table-hover table-bordered">

                                      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                          
                                          <h4 class="font-bold">Lista de Viajes Realizados</h4>
                                          <hr style="width: 100%">
                                          
                                      </div>

                                        <thead class="btn-primary">

                                            <tr>
                                                <th class="text-white text-center" width="1">#</th>
                                                <th class="text-white text-center" width="5">Fecha de Viaje</th>
                                                <th class="text-white text-center" width="10">Sucursal</th>
                                                <th class="text-white text-center" width="50">Colaborador</th>
                                                <th class="text-white text-center" width="70">Km Recorrido por Colaborador</th>
                                                <th class="text-white text-center" width="30">Tarifa por Kilometro</th>
                                                <th class="text-white text-center" width="30">Monto Total</th>
                                            </tr>

                                        </thead>

                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>Total Neto a Pagar</th>
                                                <th class="font-600 text-center" style="font-size: 20px;" id="totalMonto"></th>
                                            </tr>
                                        </tfoot>

                                </table>
                            </div>
                            <!--FIN TABLA TABLA VIAJES POR TRANSPORTISTA-->

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
 

<!-- inicializacion de parley datedropper -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#fecha_inicio').dateDropper();
        $('#fecha_final').dateDropper();
    });
</script>
<!--FIN INICIALIZACION DE PARLEY Y DATEDROPPER -->


<!-- SCRIPT PARA CARGAR LOS DATOS -->
<script type="text/javascript">
   function ver() {

    var transportista = $('#transportista').val();
    var fecha_inicio  = $('#fecha_inicio').val();
    var fecha_final   = $('#fecha_final').val();

    // Verificar si los campos están vacíos
    if (!fecha_inicio || !fecha_final || !transportista) {
        swal('Debe ingresar todos los campos requeridos como un transportista, fecha de inicio y fecha final para poder visualizar el reporte.', '', 'warning');
        return; // Detener la ejecución si falta algún campo
    }

    // Mostrar un mensaje de carga mientras los datos se procesan
    $('#table_viajes > tbody').html(
        '<tr class="odd">' +
        '<td colspan="9" class="dataTables_empty"><center><img src="../../assets/images/animaciones/loading-datatable.gif" alt="" style="width:55px;"><h4>Cargando datos...</h4></center></td>' +
        '</tr>'
    );

    axios.post('Ajax/Json_Viaje_Transportista.php', {
        transportista: transportista,
        fecha_inicio: fecha_inicio,
        fecha_final: fecha_final
    })
    .then(function (response) {
        // Asegurarse de que la respuesta contenga datos
        if (response.data && response.data.data && response.data.totalMonto) {
            // Limpiar y agregar los nuevos datos a la tabla
            var data = response.data;

            $('#table_viajes').DataTable().clear();
            $('#table_viajes').DataTable().rows.add(data.data).draw();

            // Actualizar el total en el pie de la tabla
            $('#table_viajes tfoot th.font-600').text(data.totalMonto);

            // Mostrar el contenedor de la tabla si está oculto
            $("#div_viajes").show(); 
        } else {
            // Si la respuesta está vacía, mostrar un mensaje de error
            $('#table_viajes > tbody').html(
                '<tr class="odd">' +
                '<td colspan="9" class="dataTables_empty"><center><h4>No se encontraron datos</h4></center></td>' +
                '</tr>'
            );
        }
    })
    .catch(function(error) {
        // Manejo de errores
        if (error.response) {
            $('#table_viajes > tbody').html(
                '<tr class="odd">' +
                '<td colspan="9" class="dataTables_empty"><center><h3>' + error.response.status + '</h3></center></td>' +
                '</tr>'
            );
        } else {
            $('#table_viajes > tbody').html(
                '<tr class="odd">' +
                '<td colspan="9" class="dataTables_empty"><center><h3>Error en la solicitud</h3></center></td>' +
                '</tr>'
            );
        }
    });
}
</script>
<!--FIN SCRIPT PARA CARGAR LOS DATOS -->

<script type="text/javascript">
    $(document).ready(function() {
    
      var table = $('#table_viajes').DataTable({
        dom: 'lBfrtip',
        stateSave: true,
        buttons: [ 'excel', 'colvis', ],
        "lengthMenu": [
          [10,25,50, 100, 150, 500, -1],
          [10,25,50, 100, 150, 500, "Todos"]
        ],
        "createdRow": function(row, data, index) {
    
          $('td', row).eq(0).addClass('text-center');
          $('td', row).eq(1).addClass('text-center');
          $('td', row).eq(6).addClass('text-center font-600');
    
        },

      "columnDefs": []  

      });
    
    });
</script>


<script type="text/javascript">
    function imprimir() {
        var fecha_inicio  = $('#fecha_inicio').val();
        var fecha_final   = $('#fecha_final').val();
        var transportista = $('#transportista').val();

        // Verificar si los campos están vacíos
        if (!fecha_inicio || !fecha_final || !transportista) {
            swal('Debe ingresar todos los campos requeridos como un transportista, fecha de inicio y fecha final para poder imprimir el pdf del reporte.', '', 'warning');
            return; // Detener la ejecución si falta algún campo
        }

        var myLeft = (screen.width - 1024) / 2;
        var myTop = (screen.height - 768) / 2;
        var features = "";
        features += ',left=' + myLeft + ',top=' + myTop;

        $.ajax({
            success: function(data) {
                if (data.success) {
                    // Puedes agregar alguna acción si es necesario
                } else {
                    var mensaje = data.message;
                    if (mensaje !== undefined) {
                        swal(mensaje, "", "warning");
                    } else {
                        window.open('PDFViajesPorTransportista.php?fecha_inicio=' + fecha_inicio + '&fecha_final=' + fecha_final + '&transportista=' + transportista, "Impresión Reporte", 'width=1024,height=768,toolbar=no,left=' + myLeft + ',top=' + myTop + '');
                    }
                }
            }
        });
    }
</script>

   </body>
</html>