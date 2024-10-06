<?php

include $_SERVER['DOCUMENT_ROOT']. '/conexion_db.php'; 

$filtro = "nombre + ' ' + ISNULL(identidad, '')";

if(!empty($_POST["keyword"])){


      $result = sqlsrv_query($conn,"SELECT TOP 4 colaborador_id,nombre,identidad,direccion
        FROM Colaboradores 
        WHERE $filtro LIKE '%". strtoupper($_POST["keyword"]) . "%' GROUP BY colaborador_id,nombre,identidad,direccion",array(), array( "Scrollable" => 'static'));

      $row_count = sqlsrv_num_rows($result);

      if($row_count>0) { ?>
        <ul id="country-list" class="ms-list" tabindex="1" style="width: 96%; height: 300px; overflow: auto">

            <?php while ($row = sqlsrv_fetch_object($result)){ ?>
                <li class="ms-elem-selectable" onClick="llenar_info_colaborador(
                    `<?php echo ($row->colaborador_id); ?>`,
                    `<?php echo base64_encode($row->nombre); ?>`,
                    `<?php echo base64_encode($row->direccion); ?>`,
                    
                    )">
                    <?php echo 'Nombre de Colaborador: ' . trim($row->nombre) . '<br> Identidad: ' . $row->identidad ; ?>
                </li>
                <?php } //FIN WHILE ?>

        </ul>

    <?php }else{ ?>

        <ul id="country-list" class="ms-list" tabindex="1" style="width: 96%; height: 250px; overflow: auto">
            <li class="ms-elem-selectable">Ninguna Coincidencia, Intenta de nuevo</li>
        </ul>

    <?php } //FIN IF ?> <br>


<?php } 
//FIN IF CAMPO VACIO ?>

