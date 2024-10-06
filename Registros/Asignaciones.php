<?php 
   
/* SE INCLUYE EL ARCHIVO QUE CONTIENE LA CONECCION A LA BASE DE DATOS*/
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

/*OBTENEMOS EL NOMBRE DEL USUARIO QUE ESTA LOGUEADO*/
$usuario = $_SESSION['usuario'];

//-------DECLARACION DE VARIABLES PARA EL MENU---// 
$Asignaciones      = $color;
$subdrop_Registros = $subdrop;
$style_Registros   = $block;

//------- FIN DECLARACION DE VARIABLES PARA EL MENU---// 
   
//COMPROBAMOS SI EL USUARIO TIENE ACCESO AL MODULO AL CUAL ESTA INTENTANDO ACCEDER
$query_permiso=sqlsrv_query($conn,"SELECT COUNT(*)AS PERMISO FROM Permisos
WHERE Pantalla_id=1
AND rol_id=1
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

    <title>Asignación</title>

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
                                    <h2 class="font-bold">Colaboradores en Sucursales</h2>
                                    <hr style="width: 40%"> </center>
                            </div>
                            <!-- FIN TITULO PRINCIPAL-->


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <center>
                                    
                                <h5>Asignar Nuevo Colaborador a Sucursal</h5> 

                                <a href="#" id="openModal" data-toggle="modal" data-target="#myModal_Agregar_colaborador"><button type="button" id="Agregar_colaborador" class="btn btn-primary btn-bordred btn-rounded waves-effect m-b-5 BtnAgregar_colaborador" data-toggle="tooltip" title="" data-placement="bottom"><i class="fa fa-plus"></i></button></a>

                                <hr style="width: 40%">

                                </center>

                            </div>

                            <!--SALTO -->
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br></div>

                             <!--INICIO TABLA -->
                            <div class="table-striped col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <div class="table-responsive">

                                    <table id="DataTable_colaboradores_asignados_sucursales"class="table table-bordered">

                                        <!-- INICIO CABECERA DE LA TABLA -->
                                        <thead class="btn-primary">
                                            <tr>
                                                <th class ="text-center  text-white" width="5">#</th>
                                                <th class ="text-white">Opciones</th>
                                                <th class ="text-center text-white">Nombre del Colaborador</th>
                                                <th class ="text-center text-white">Celular</th>
                                                <th class ="text-center text-white">Dirección Domiciliaria</th>
                                                <th class ="text-center text-white">Sucursal Asignada</th>
                                                <th class ="text-center text-white">Distancia sucursal-casa</th>
                                                <th class ="text-center text-white">Usuario Registro</th>
                                                <th class ="text-center text-white">Fecha Registro</th>
                                        </thead>
                                        <!-- FIN CABECERA DE LA TABLA -->

                                    </table>

                                </div>

                            </div>
                            <!--FIN TABLA -->

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


 <!-- MODAL NUEVO COLABORADOR -->
<div class="modal fade" id="myModal_Agregar_colaborador" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Nueva Asignación</h4>
            </div>

            <div class="modal-body">

                <div class="container" >
                    <div class="row">

                        <div class="form-group col-md-10 col-lg-10 col-xs-10 col-sm-12">
                            <label>Búsqueda de Colaborador</label>
                            <div class="input-group">
                                <span class="input-group-addon bg-success b-20 text-white"><i class="fa fa-search"></i></span>
                                <input autocomplete="off" type="text" id="colaborador" name="colaborador" placeholder="Buscar por Nombre ó Identidad" class="form-control">
                            </div>
                            <!-- DIV RESULTADO BUSQUEDA AJAX-->
                            <div id="resultado_busqueda"></div><br>
                        </div>

                        <form method="POST" id="form_Agregar_colaborador">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos del Colaborador</h5>
                                <hr style="width: 100%">
                            </div>

                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>

              
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12" hidden>
                                <label>Id Colaborador</label>
                                <div class="input-group ">
                                    <input autocomplete="off" type="text" id="id_colaborador" name="id_colaborador" placeholder="Id Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                <label>Nombre Completo</label>
                                <div class="input-group ">
                                    <span class="input-group-addon bg-primary b-0 text-white"><i class="fa fa-user"></i></span>
                                    <input autocomplete="off" type="text" id="nombre_colaborador" name="nombre_colaborador" placeholder="Nombre Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                <label>Domicilio</label>
                                <div class="input-group ">
                                  <span class="input-group-addon bg-primary b-0 text-white"><i class="fa fa-map-marker"></i></span>
                                  <input autocomplete="off" type="text" id="direccion_colaborador" name="direccion_colaborador" placeholder="Dirección Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br></div>


                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos de la Sucursal</h5>
                                <hr style="width: 100%">
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>


                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                  <label>Sucursales Registradas</label>
                                  <select name="sucursal" id="sucursal" class="form-control" required>
                                    <option value="" disabled="false"selected="selected">Seleccione Sucursal</option>
                                    <?php while( $row = sqlsrv_fetch_object($query_sucursales) ) {?>
                                    <option value="<?php echo $row->sucursal_id; ?>"><?php echo $row->nombre; ?></option>
                                    <?php } sqlsrv_free_stmt($query_sucursales);?>
                                 </select>
                            </div>
 

                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                <label>Ingrese Km desde sucursal-casa</label>
                                <div class="input-group">
                                    <input autocomplete="off" id="distancia" name="distancia" class="form-control" value="" max=50  min=1 type="number" required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>



                        </form>

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-bordred waves-effect waves-light BtnNuevaAsignacion"><i class="fa fa-save"id="ico_guardar"></i> Guardar</button>
                <button type="button" class="btn btn-danger btn-bordred delete-event waves-effect waves-light" data-dismiss="modal" role="button"><i class="ti-close"> Cerrar</i></button>
            </div>

        </div>

    </div>
</div>
 <!--FIN MODAL NUEVO COLABORADOR -->



<!-- MODAL ACTUALIZAR COLABORADOR -->
<div class="modal fade" id="myModal_Actualizar_colaborador" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Actualizar Colaborador</h4>
            </div>

            <div class="modal-body">

                <div class="container" >
                    <div class="row">

                        <form method="POST" id="form_Actualizar_colaborador">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos del Colaborador</h5>
                                <hr style="width: 100%">
                            </div>

                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>

              
                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12" hidden>
                                <label>Id Colaborador</label>
                                <div class="input-group ">
                                    <input autocomplete="off" type="text" id="id_colaborador_actualizar" name="id_colaborador_actualizar" placeholder="Id Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <label>Nombre Completo</label>
                                <div class="input-group ">
                                    <span class="input-group-addon bg-primary b-0 text-white"><i class="fa fa-user"></i></span>
                                    <input autocomplete="off" type="text" id="nombre_colaborador_actualizar" name="nombre_colaborador_actualizar" placeholder="Nombre Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <label>Domicilio</label>
                                <div class="input-group ">
                                  <span class="input-group-addon bg-primary b-0 text-white"><i class="fa fa-map-marker"></i></span>
                                  <input autocomplete="off" type="text" id="direccion_colaborador_actualizar" name="direccion_colaborador_actualizar" placeholder="Dirección Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br></div>


                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos de la Sucursal</h5>
                                <hr style="width: 100%">
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>


                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                  <label>Sucursales Asignada</label>
                                  <div class="input-group ">
                                  <input autocomplete="off" type="text" id="sucursal_actualizar" name="sucursal_actualizar" placeholder="Sucursal Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-xs-6 col-sm-12">
                                <label>Ingrese Km desde sucursal-casa</label>
                                <div class="input-group">
                                    <input autocomplete="off" id="distancia_actualizar" name="distancia_actualizar" class="form-control" value="" max=50  min=1 type="number" required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>



                        </form>

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-bordred waves-effect waves-light BtnActualizarAsignacion"><i class="fa fa-paper-plane-o"id="ico_guardar"></i> Actualizar</button>
                <button type="button" class="btn btn-danger btn-bordred delete-event waves-effect waves-light" data-dismiss="modal" role="button"><i class="ti-close"> Cerrar</i></button>
            </div>

        </div>

    </div>
</div>
 <!--FIN MODAL ACTUALIZAR COLABORADOR -->



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
 


<!-- inicializacion de parley datedropper -->
<script type="text/javascript">   
$(document).ready(function() {

    $('form').parsley();
    cargar_datos();

});
</script>
<!--FIN INICIALIZACION DE PARLEY Y DATEDROPPER -->


<!-- SCRIPT PARA CARGAR LOS DATOS DE TABLA-->
<script type="text/javascript">
function cargar_datos() {

    $('#DataTable_colaboradores_asignados_sucursales > tbody').html(
        '<tr class="odd">' +
        '<td colspan="20" class="dataTables_empty"><center><img src="../../assets/images/animaciones/loading-datatable.gif" alt="" style="width:55px;"><h4>Cargando datos...</h4></center></td>' +
        '</tr>'
    );

    axios.post('Ajax/Json_lista_colaborador_sucursal.php', {

    }).then(function(response) {

        $('#DataTable_colaboradores_asignados_sucursales').DataTable().clear();
        $('#DataTable_colaboradores_asignados_sucursales').DataTable().rows.add(response.data).draw();

    })

}
</script>
<!--FIN SCRIPT PARA CARGAR LOS DATOS DE TABLA -->


<!-- INICIO DISEÑO DE TABLE -->
<script type="text/javascript">
$(document).ready(function() {

    var table = $('#DataTable_colaboradores_asignados_sucursales').DataTable({
        dom: 'lBfrtip',
        stateSave: true,
        buttons: ['excel', 'colvis', ],
        "lengthMenu": [
            [10, 25, 35, 100, -1],
            [10, 25, 35, 100, "Todos"]
        ],
        "createdRow": function(row, data, index) {

            $('td', row).eq(0).addClass('font-600 text-center');
            $('td', row).eq(1).addClass('text-center');
            $('td', row).eq(2).addClass('font-600');
            $('td', row).eq(5).addClass('font-600 text-center');
            $('td', row).eq(6).addClass('font-600 text-center').css('color', '#1D386E').css('font-size', '16px');
            $('td', row).eq(7).addClass('text-center');
            $('td', row).eq(8).addClass('text-center');
            $('td', row).eq(12).addClass('text-center');

        },
        "columnDefs": [

            { targets: 1,
            render: function(data, type, row) {
              return '<a href="#javascript"<a onclick="eliminar_asignacion(`'+((row[1]))+'`,)"><button class="btn btn-sm btn-icon btn-danger btn-bordred m-r-5 waves-effect waves-inverse" data-toggle="tooltip" title="Eliminar Colaborador de Asignación Registrada " data-placement="bottom"> <i class="ti-trash"></i></button></button></a><a href="#javascript"<a onclick="actualizar_asignacion(`'+((row[1]))+'`,)"><button class="btn btn-sm btn-icon btn-success btn-bordred m-r-5 waves-effect waves-inverse" data-toggle="tooltip" title="Actualizar Colaborador de Asignación" data-placement="bottom"> <i class="fa fa-refresh"></i></button></button></a>';
            }
          },
        ]
    });

    cargar_datos();
});
</script>
<!--FIN SCRIPT DISEÑO DE TABLA -->


<!--INICIO BUSQUEDA DE COLABORADOR -->
<script type="text/javascript">
 $(document).ready(function() {
   $("#colaborador").keyup(function() {
     $.ajax({
       type: "POST",
       url: "Ajax/buscar_colaborador.php",
       data: 'keyword=' + $(this).val(),
       success: function(data) {
         $("#resultado_busqueda").show();
         $("#resultado_busqueda").html(data);    
       }
     });
   });         
 });
</script>
<!--FIN BUSQUEDA DE CONTRIBUYENTE PARA ENLAZARLO CON EL NEGOCIO -->



<!--INICIO LLENAR INFORMACION DEL COLABORADOR--> 
<script  type="text/javascript">
 function llenar_info_colaborador(id_colaborador,nombre_colaborador,direccion_colaborador){   
 
   $("#id_colaborador").val(id_colaborador);
   $("#nombre_colaborador").val(Utf8.decode(atob(nombre_colaborador)));
   $("#direccion_colaborador").val(Utf8.decode(atob(direccion_colaborador)));
   $("#colaborador").val('');         
   $("#resultado_busqueda").hide();            
   }         
</script>
<!--FIN LLENAR INFORMACION DEL NEGOCIO--> 


<!-- INICIO DE REESTRABLECER OPTIONES DE CAMPOS DEL MODAL-->
<script>
    $('#myModal_Agregar_colaborador').on('hidden.bs.modal', function () {

    $('#sucursal').val('');
    $('#distancia').val('');
    $('#direccion_colaborador').val('');
    $('#nombre_colaborador').val('');

    });
</script>
<!-- FIN DE REESTRABLECER OPTION DEL MODAL-->


<!--INICIO EFECTOS DE BOTON GUARDAR-->
<script type="text/javascript">
function flag_guardando(){
  $("#ico_guardar").removeClass("fa fa-save");
  $("#ico_guardar").addClass("fa fa-spin fa-spinner");
  $('.BtnNuevaAsignacion').attr("disabled", "disabled");
}

function flag_listo(){
  $("#ico_guardar").removeClass("fa fa-spin fa-spinner");
  $("#ico_guardar").addClass("fa fa-save");
  $(".BtnNuevaAsignacion").removeAttr("disabled");
  $('#form_Agregar_colaborador').css("opacity", "");

}
</script>
<!-- FIN EFECTOS DE BOTON GUARDAR-->



<!--INICIO SCRIPT PARA GUARDAR EL NUEVA ASIGNACION DE COLABORADOR--> 
<script type="text/javascript">
$(".BtnNuevaAsignacion").click(function(e) {

   flag_guardando();

  e.preventDefault();

  $('#form_Agregar_colaborador').submit(function(event) {
    event.preventDefault()
  });


  if ($('#form_Agregar_colaborador').submit().parsley().isValid()) {


    $.ajax({
      type: "POST",
      url: 'Ajax/GuardarAsignacionColaborador.php',
      cache: false,
      data: $("#form_Agregar_colaborador").serialize(),

      beforeSend: function() {
      },
       success: function(data) {

         if (data.success) {

             swal(data.message, "Exito!!!", "success");
             $("#form_Agregar_colaborador")[0].reset();
             $('#myModal_Agregar_colaborador').modal('toggle');
             cargar_datos();

        } else {

           swal("Alerta!", data.message, "warning");
        } //FIN ELSE

          flag_listo();

       } //FIN SUCCESS

    });

  } else {

    flag_listo();

  } //FIN IF VALIDACIONES


});
</script>
<!--FIN DE SCRIPT PARA GUARDAR EL NUEVA ASIGNACION DE COLABORADOR--> 


<!-- SCRIPT PARA ELIMINAR ASIGNACION DEL COLABORADOR--> 
<script type="text/javascript">
function eliminar_asignacion(id_colaborador_sucursal) {

    $.ajax({
        url: 'Ajax/eliminar_asignacion.php',
        data: {
            id_colaborador_sucursal : id_colaborador_sucursal,
        },
        type: "POST",
        beforeSend: function() {

        },
        success: function(respuesta) {

            if (respuesta.success) {

                cargar_datos();
                toastr["success"](respuesta.message, "Success");

            } else {

                toastr["error"](respuesta.message, "Alerta");

            }
        }

    });

} //FIN FUNCION
</script>
<!--FIN SCRIPT PARA ELIMINAR ASIGNACION DEL COLABORADOR --> 


<script type="text/javascript">
function actualizar_asignacion (id_colaborador_sucursal) {
    $.ajax({
        type: "POST",
        url: 'Ajax/cargar_informacion_actualizar.php',
        cache: false,
        data: { id_colaborador_sucursal : id_colaborador_sucursal },
        beforeSend: function() {
            // Aquí podrías mostrar algún indicador de carga si es necesario
        },
        success: function(data) {
            // Asegúrate de que los nombres de los campos sean correctos
            $("#id_colaborador_actualizar").val(data.id_colaborador_actualizar);
            $("#nombre_colaborador_actualizar").val(data.nombre_colaborador_actualizar);
            $("#direccion_colaborador_actualizar").val(data.direccion_colaborador_actualizar);
            $("#sucursal_actualizar").val(data.sucursal_actualizar);
            $("#distancia_actualizar").val(data.distancia_actualizar);
            
           
            $("#myModal_Actualizar_colaborador").modal('show');


        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX: " + error);
        }
    });
} 
</script>

<!--INICIO SCRIPT PARA ACTUALIZAR ASIGNACION DE COLABORADOR--> 
<script type="text/javascript">
$(".BtnActualizarAsignacion").click(function(e) {

   flag_guardando();

  e.preventDefault();

  $('#form_Actualizar_colaborador').submit(function(event) {
    event.preventDefault()
  });


  if ($('#form_Actualizar_colaborador').submit().parsley().isValid()) {


    $.ajax({
      type: "POST",
      url: 'Ajax/ActualizarAsignacionColaborador.php',
      cache: false,
      data: $("#form_Actualizar_colaborador").serialize(),

      beforeSend: function() {
      },
       success: function(data) {

         if (data.success) {

             swal(data.message, "Exito!!!", "success");
             $("#form_Actualizar_colaborador")[0].reset();
             $('#myModal_Actualizar_colaborador').modal('toggle');
             cargar_datos();

        } else {

           swal("Alerta!", data.message, "warning");
        } //FIN ELSE

          flag_listo();

       } //FIN SUCCESS

    });

  } else {

    flag_listo();

  } //FIN IF VALIDACIONES


});
</script>
<!--FIN DE SCRIPT PARA ACTUALIZAR ASIGNACION DE COLABORADOR--> 
  




   </body>
</html>