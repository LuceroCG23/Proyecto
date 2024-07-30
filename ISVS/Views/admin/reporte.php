<?php
require '../../conn/connection.php';
// ------------------------------------------------
$query = "SELECT mesa_examen.*, 
                 materia.Nombre AS nombre_materia, 
                 ciclo_lectivo.nombre_ciclo,
                 nombre_tipo AS nombre_tipo
          FROM mesa_examen 
          INNER JOIN materia ON mesa_examen.id_materia = materia.id_materia
          LEFT JOIN ciclo_lectivo ON mesa_examen.id_ciclo = ciclo_lectivo.id_ciclo
          LEFT JOIN tipo ON mesa_examen.id_tipo = tipo.id_tipo";
$result = $db->query($query);
?>
<!-- -------------------------------------------------- -->
<?php require 'navbar.php'; ?>
<body>
    <div class="container mt-3">
        <div class="card rounded-2 border-0">
            <h5 class="card-header bg-dark text-white">Listar Mesas de Examen  <a href="reporte.php"  class="btn btn-primary float-right mb-2" >PDF</a></h5>          
            <div class="card-body bg-light">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Nombre de Mesa</th>
                        <th>Materia</th>
                        <th>Hora</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Ciclo Lectivo</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . $row['nombre_mesa'] . "</td>";
                        echo "<td>" . $row['nombre_materia'] . "</td>";
                        echo "<td>" . $row['hora'] . "</td>";
                        echo "<td>" . $row['fecha'] . "</td>";
                        echo "<td>" . $row['fecha_fin'] . "</td>";
                        echo "<td>" . $row['nombre_ciclo'] . "</td>";
                        echo "<td>" . $row['nombre_tipo'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>      
            </table>          
            </div>
        </div>
    </div>
        <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="jquery/jquery-3.3.1.min.js"></script>
    <script src="popper/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>      
    <!-- datatables JS -->
    <script type="text/javascript" src="datatables/datatables.min.js"></script>    
    <!-- para usar botones en datatables JS -->  
    <script src="datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>  
    <script src="datatables/JSZip-2.5.0/jszip.min.js"></script>    
    <script src="datatables/pdfmake-0.1.36/pdfmake.min.js"></script>    
    <script src="datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="datatables/Buttons-1.5.6/js/buttons.html5.min.js"></script>     
    <!-- código JS propìo-->    
    <script type="text/javascript" src="js/main.js"></script>  
</body>
</html>
