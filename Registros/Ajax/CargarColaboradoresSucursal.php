<?php 
include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

$sucursal = $_POST['sucursal'];

$query_sucursal_seleccionada = sqlsrv_query($conn,"SELECT CS.colaborador_sucursal_id, 
                                                    C.colaborador_id AS id_colaborador, 
                                                    C.nombre AS nombre_colaborador,
                                                    C.celular, 
                                                    C.direccion, 
                                                    ROUND(CS.distancia, 0) AS distancia
                                                  FROM Colaboradores_Sucursales CS
                                                  INNER JOIN Colaboradores C ON CS.colaborador_id = C.colaborador_id
                                                  INNER JOIN Sucursales S ON CS.sucursal_id = S.sucursal_id
                                                  WHERE S.sucursal_id = '$sucursal'
                                                  ORDER BY C.nombre ASC;",array(),array( "Scrollable" => 'static' ));
$count = sqlsrv_num_rows($query_sucursal_seleccionada); 

$query_transportista =  sqlsrv_query($conn,"SELECT transportista_id, nombre, tarifa_por_km 
                                         FROM Transportistas 
                                         WHERE estado = 'A'
                                         ORDER BY nombre ASC");
?>

<style>
.table-header-color {
  background-color: #BD1E1E;
  color: white; 
}

.floating-card{ 
position: fixed; 
top: 250px; 
right: 20px; 
z-index: 1000;
width: 225px; 
height: auto; 
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);

}
</style>


<!--SALTO DE LINEA -->
<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br></div>


<?php if($count>0): ?>

<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
    <h4 class="font-bold">Colaboradores Asignados a la Sucursal</h4><br>
</div>


<!-- TARJETA FLOTANTE -->
<div class="card-box widget-user floating-card" id="tarjeta_seleccionados">
<lottie-player class="img-responsive" src="../../assets/images/animaciones/json/seleccion.json"  background="transparent" speed="1.3"  autoplay loop style="width:125px; display: block; position:absolute; transform: translate(-22px, -18%);"></lottie-player>
    <div class="wid-u-info">
        <h5 class="text-primary text-center font-600" id="total_seleccionado">0</h5>
        <h5 class="font-600 text-center">Colaboradores Seleccionados</h5>
    </div>
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

  <center>
      
       <h5>Registrar Viaje</h5> 
       <a href="#" id="openModal" data-toggle="modal" data-target="#myModal_registro_viaje"><button type="button" id="Registro_Viaje" class="btn btn-primary btn-bordred btn-rounded waves-effect m-b-5 BtnRegistro_Viaje" data-toggle="tooltip" title="" data-placement="bottom"><i class="zmdi zmdi-directions-car"></i></button></a>

  </center>

</div>


<!--INICIO TABLA -->
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive" >

 <form method="POST" id="form_select">

      <table class="table table-hover table-bordered" id="table_colaboradores">

          <!-- INICIO CABECERA DE LA TABLA -->
           <thead class="table-header-color">
              <tr>
                  <th class="text-white text-center" width="30">#</th>
                  <th class ="text-center text-white">Nombre del Colaborador</th>
                  <th class ="text-center text-white">Celular</th>
                  <th class ="text-center text-white">Dirección Domiciliaria</th>
                  <th class ="text-center text-white">Distancia Sucursal-Casa en Km</th>
               </tr>     
          </thead>
          <!-- FIN CABECERA DE LA TABLA -->

          <tbody>

            <?php $contador=1;
            while ($row = sqlsrv_fetch_object($query_sucursal_seleccionada)): ?>
            <tr>
                <th class="text-center">
                     <strong><?php echo $contador++ ?></strong>
                      <div class="checkbox checkbox-primary">
                           <input id="checkbox_<?php echo $contador ?>" 
                                  name="colaboradores[]" 
                                  value="<?php echo $row->colaborador_sucursal_id ?>" 
                                  data-id_colaborador="<?php echo $row->id_colaborador ?>" 
                                  type="checkbox" 
                                  onclick="actualizarSeleccion(<?php echo $row->colaborador_sucursal_id?>)">
                           <label for="checkbox_<?php echo $contador ?>"></label>
                       </div>
                </th>
                <td><?php echo $row->nombre_colaborador?></td>
                <td><?php echo $row->celular?></td>
                <td><?php echo $row->direccion?></td>
                <td><?php echo number_format($row->distancia,0)?></td>
            </tr>
            <?php endwhile; ?>   

          </tbody>

      </table>

   </form>

</div>
<!--FIN TABLA -->

<?php else: ?>

     <!--SALTO DE LINEA -->
     <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br><br></div>

     <!--IMAGEN SI NO HAY NINGUN REGISTRO-->
     <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!--DIV SI NO HAY REGISTROS -->
        <center><img src="../../assets/images/images_svg/busqueda_sin.svg" style="width:140px;" class="img-responsive"><h3><hr style="width: 30%;"><b>No se encontró ningún colaborador asignado a sucursal seleccionada</b></h3><br></center>
     </div>

<?php endif; ?>



<!-- INICIO MODAL REGISTRO VIAJE -->
<div class="modal fade" id="myModal_registro_viaje" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">registrar Viaje</h4>
            </div>

            <div class="modal-body">

                <div class="container" >
                    <div class="row">


                        <form method="POST" id="form_Registro_Viaje">

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos del Viaje</h5>
                                <hr style="width: 100%">
                            </div>

                            <div class="col-lg-5 col-md-5 col-xs-5 col-sm-12" hidden>
                                <label>Arreglo de colaboradores</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="arreglo_colaboradores" name="arreglo_colaboradores" placeholder="Automática" class="form-control" value="" readonly required>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-5 col-xs-5 col-sm-12" hidden>
                                <label>Id's de colaboradores</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="arreglo_id_colaboradores" name="arreglo_id_colaboradores" placeholder="Automática" class="form-control" value="" readonly required>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-12">
                               <label>Fecha Viaje</label> 
                               <input id="fecha_viaje" value="" dateDropperSetup.autoInit = true; name="fecha_viaje"  data-large-mode="true" data-large-default="false" class="form-control" data-lang="es" data-format="Y-m-d" data-translate-mode="true" data-large-mode="true" data-min-year="2024" required/>
                           </div>

                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-12">
                                <label>Colaboradores a Viajar</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="cant_colaboradores" name="cant_colaboradores" placeholder="Automática" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-12">
                                <label>Distancia Total en Km</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="distancia_viaje" name="distancia_viaje" placeholder="Automática" class="form-control" value="" readonly required>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-5 col-xs-5 col-sm-12" hidden>
                                <label>Sucursal</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="id_sucursal" name="id_sucursal" placeholder="Automática" class="form-control" value="<?php echo $sucursal?>" readonly required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br></div>


                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h5 class="font-bold col-lg-push-4">Datos del Transportista</h5>
                                <hr style="width: 100%">
                            </div>

                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"></div>

                            <div class="col-lg-8 col-md-8 col-xs-8 col-sm-12">
                              <label>Transportistas Registrados</label>
                                  <select name="transportista" id="transportista" class="form-control" onchange="cargar_datos_transportista(this.value)" required>
                                     <option value="" disabled="false"selected="selected">Seleccione Transportistas</option>
                                     <?php while($row = sqlsrv_fetch_object($query_transportista) ) {?>
                                     <option value="<?php echo $row->transportista_id; ?>"><?php echo $row->nombre; ?></option>
                                     <?php } sqlsrv_free_stmt($query_transportista);?>
                                  </select>
                            </div>


                            <div class="col-lg-4 col-md-4 col-xs-4 col-sm-12">
                                <label>Tarifa por Km</label>
                                <div class="form-control">
                                  <input autocomplete="off" type="text" id="tarifa_transportista" name="tarifa_transportista" placeholder="Tarifa Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-2 col-xs-2 col-sm-12" hidden>
                                <label>Id Transportista</label>
                                <div class="input-group ">
                                    <input autocomplete="off" type="text" id="id_transportista" name="id_transportista" placeholder="Id Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>


                            <!--SALTO DE LINEA -->
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12"><br></div>


                            <div class="col-lg-8 col-md-8 col-xs-8 col-sm-12" hidden>
                                <label>Nombre Completo</label>
                                <div class="form-control">
                                    <input autocomplete="off" type="text" id="nombre_transportista" name="nombre_transportista" placeholder="Nombre Automático" class="form-control" value="" readonly required>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-bordred waves-effect waves-light BtnRegistro_viaje"><i class="fa fa-save"id="ico_guardar"></i> Registrar Viaje</button>
                <button type="button" class="btn btn-danger btn-bordred delete-event waves-effect waves-light" data-dismiss="modal" role="button"><i class="ti-close"> Cerrar</i></button>
            </div>

        </div>

    </div>
</div>
<!--FIN MODAL REGISTRO VIAJE -->


<script type="text/javascript">   
$(document).ready(function() {
    $('form').parsley();
    $('#fecha_viaje').dateDropper();
});
</script>


<script>
    $("#cant_colaboradores").val('');
    $("#distancia_viaje").val('');
    $("#arreglo_colaboradores").val('');
    $("#arreglo_id_colaboradores").val('');
</script>


<!-- INICIO PARA CONTAR COLABORADORES SELECCIONADOS -->
<script type="text/javascript">
function contador_colaboradores_seleccionados() {
    var seleccionados = document.querySelectorAll('input[name="colaboradores[]"]:checked');
    
    var colaboradores_id = [];
    var colaboradores_sucursal_id = [];
    
    seleccionados.forEach(function(checkbox) {
        // Obtener colaborador_id y colaborador_sucursal_id
        var colaborador_id = checkbox.getAttribute('data-id_colaborador');
        var colaborador_sucursal_id = checkbox.value;
        
        // Agregar ambos al arreglo correspondiente
        colaboradores_id.push(colaborador_id);
        colaboradores_sucursal_id.push(colaborador_sucursal_id);
    });

    // Enviar ambos arreglos al servidor a través de AJAX
    $.ajax({
        type: "POST",
        url: 'Ajax/operaciones_colaboradores_seleccionados.php',
        data: { 
            colaboradores: colaboradores_sucursal_id, 
            colaboradores_id: colaboradores_id 
        },
        cache: false,
        success: function (data) {
            $("#total_seleccionado").html(data.totalSeleccionados); 
            $("#cant_colaboradores").val(data.totalSeleccionados);
            $("#distancia_viaje").val(data.distancia_total);
            $("#arreglo_colaboradores").val(data.arreglo_colaboradores);
            $("#arreglo_id_colaboradores").val(data.arreglo_id_colaboradores);  
     }
    });
}
</script>
<!-- FIN PARA CONTAR COLABORADORES SELECCIONADOS -->


<!-- INICIO PARA ACTUALIZAR COLABORADORES SELECCIONADOS -->
<script type="text/javascript">
var seleccionados = []; 
function actualizarSeleccion(id) {
    
    if ($('input[value="' + id + '"]').is(':checked')) {
        if (!seleccionados.includes(id)) {
            seleccionados.push(id);
        }
    } else {
        
        var index = seleccionados.indexOf(id);
        if (index !== -1) {
            seleccionados.splice(index, 1); 
        }
    }
    
    contador_colaboradores_seleccionados();
}
</script>
<!-- FIN PARA ACTUALIZAR COLABORADORES SELECCIONADOS -->


<!-- INICIO MANTENER LAS SELECCIONES AL CAMBIAR PAGINACION Y DISEÑO DE TABLA-->
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#table_colaboradores').DataTable({
        stateSave: true,
        dom: 'lBfrtip',
        buttons: ['copy', 'excel'],
        "lengthMenu": [
            [10, 25, 35, 100, -1],
            [10, 25, 35, 100, "Todos"]
        ],
        "createdRow": function(row, data, index) {
            $('td', row).eq(0).addClass('font-600');
            $('td', row).eq(3).addClass('font-600 text-center').css('color', '#1D386E').css('font-size', '16px');
        }
    });

    
    $('#table_colaboradores').on('draw.dt', function() {
        $('input[name="colaboradores[]"]').each(function() {
            var id = $(this).val();
            if (seleccionados.includes(parseInt(id))) {
                $(this).prop('checked', true); 
            }
        });
    });
});
</script>
<!-- FIN MANTENER LAS SELECCIONES AL CAMBIAR PAGINACION-->


<!-- INICIO DE REESTRABLECER OPTIONES DE CAMPOS DEL MODAL-->
<script>
    $('#myModal_registro_viaje').on('hidden.bs.modal', function () {

    $('#nombre_transportista').val('');
    $('#tarifa_transportista').val('');
    $('#transportista').val('');

    });
</script>
<!-- FIN DE REESTRABLECER OPTION DEL MODAL-->


<!--INICIO MOSTRAR DATOS DE TRANSPORTISTA SELECCIONADO-->
<script type="text/javascript">
function cargar_datos_transportista(transportista) {
      $.ajax({
          type: "POST",
          url: 'Ajax/cargar_informacion_transportista.php',
          data: {transportista: transportista },
          cache: false,

          beforeSend: function () {

          },
          success: function (data) {

           $("#nombre_transportista").val(data.nombre);
           $("#tarifa_transportista").val(data.tarifa_por_km);
           $("#id_transportista").val(data.transportista_id);
          } //FIN SUCCESS
      });

}
</script>
<!--FIN MOSTRAR DATOS DE TRANSPORTISTA SELECCIONADO-->


<!--INICIO EFECTOS DE BOTON GUARDAR-->
<script type="text/javascript">
function flag_guardando(){
  $("#ico_guardar").removeClass("fa fa-save");
  $("#ico_guardar").addClass("fa fa-spin fa-spinner");
  $('.BtnRegistro_viaje').attr("disabled", "disabled");
}

function flag_listo(){
  $("#ico_guardar").removeClass("fa fa-spin fa-spinner");
  $("#ico_guardar").addClass("fa fa-save");
  $(".BtnRegistro_viaje").removeAttr("disabled");
  $('#form_Registro_Viaje').css("opacity", "");

}
</script>
<!-- FIN EFECTOS DE BOTON GUARDAR-->


<!--INICIO GUARDAR REGISTRO-->
<script type="text/javascript">
$(".BtnRegistro_viaje").click(function(e) {

   flag_guardando();

  e.preventDefault();

  $('#form_Registro_Viaje').submit(function(event) {
    event.preventDefault()
  });


  if ($('#form_Registro_Viaje').submit().parsley().isValid()) {


    $.ajax({
      type: "POST",
      url: 'Ajax/GuardarViaje.php',
      cache: false,
      data: $("#form_Registro_Viaje").serialize(),

      beforeSend: function() {
      },
       success: function(data) {

         if (data.success) {

             swal(data.message, "Exito!!!", "success");
             $("#form_Registro_Viaje")[0].reset();
             $('#myModal_registro_viaje').modal('toggle');
             $("#table_colaboradores_sucursal").hide();
             $("#tarjeta_seleccionados").hide();
             $("#sucursal").val('');

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
<!--FIN GUARDAR REGISTRO-->