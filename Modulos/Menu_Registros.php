<!--///////////// INICIO MODULO REGISTROS ///////////////////////////// -->
<li class="has_sub">
   <a href="javascript:void(0);" class="waves-effect <?php echo $subdrop_Registros ?>"><img src="../../assets/images/images_svg/documentos.png" style="width: 30px;"><span> Registro</span> <span class="menu-arrow"></span></a>
   <ul class="list-unstyled " style="display: <?php echo $style_Registros  ?>;">

      <?php  while ($row_item = sqlsrv_fetch_object($query_pantallas)){
          $tmp = $row_item->pantalla_id; 
          $rol = $row_item->rol_id; 

            if ($tmp == '1' && $rol == '1'){ ?>
              <!-- INICIO ASIGNACIONES-->
              <li>
                 <a href="../../Registros/Asignaciones.php" style="text-decoration: none;">
                      <span style="color: <?php echo $Asignaciones ?>; display: inline-flex; align-items: center;">
                          <img src="../../assets/images/images_svg/asignaciones.png" style="width: 30px; height: 30px; margin-right: 5px;">
                          Asignaciones
                      </span>
                  </a>
              </li>
              <!-- FIN ASIGNACIONES-->             
            <?php } //FIN IF-ELSE

            if ($tmp == '2' && $rol == '2'){ ?>
              <!-- INICIO ViajesDiarios-->
              <li>
                 <a href="../../Registros/ViajesDiarios.php" style="text-decoration: none;">
                      <span style="color: <?php echo $ViajesDiarios ?>; display: inline-flex; align-items: center;">
                          <img src="../../assets/images/images_svg/viajes.png" style="width: 30px; height: 30px; margin-right: 5px;">
                          Viajes Diarios
                      </span>
                  </a>
              </li>
              <!-- FIN ViajesDiarios-->             
            <?php } //FIN IF-ELSE

      } //FIN WHILE
      ?>

   </ul>
</li>
<!--///////////// FIN MODULO REGISTROS ///////////////////////////// -->
