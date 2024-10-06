<!--///////////// INICIO MODULO REPORTES  ///////////////////////////// -->
<li class="has_sub">
   <a href="javascript:void(0);" class="waves-effect <?php echo $subdrop_Reportes ?>"><img src="../../assets/images/images_svg/reporte.png" style="width: 30px;"><span> Reportes</span> <span class="menu-arrow"></span></a>
   <ul class="list-unstyled " style="display: <?php echo $style_Reportes ?>;">

      <?php  while ($row_item = sqlsrv_fetch_object($query_pantallas)){
          $tmp = $row_item->pantalla_id; 

          if ($tmp == '3'){ ?>
              <!-- INICIO VIAJES POR TRANSPORTISTA-->
              <li>
                 <a href="../../Reportes/ViajesPorTransportista.php" style="text-decoration: none;">
                      <span style="color: <?php echo $ViajesRealizados ?>; display: inline-flex; align-items: center;">
                          <img src="../../assets/images/images_svg/reporte_transporte.png" style="width: 30px; height: 30px; margin-right: 5px;">
                          Viajes Realizados
                      </span>
                  </a>
              </li>
              <!-- FIN VIAJES POR TRANSPORTISTA-->             
            <?php } //FIN IF-ELSE

      } //FIN WHILE
      ?>

   </ul>
</li>
<!--///////////// FIN MODULO REGISTROS ///////////////////////////// -->
