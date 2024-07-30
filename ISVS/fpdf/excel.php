<?php
$timestamp = time();
$filename = 'Reporte' . $timestamp . '.xls';

//***************** inicio lineas para convertir a excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
echo "
        <html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
        <head><meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\" /></head><body>";   
?>

<div class="container mt-3">
        <div class="card rounded-2 border-0">

          
            <div class="card-body bg-light">
            <h5 class="card-header bg-dark text-white"> Mesas de Examen </h5>
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead >
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
                                
$conexion=mysqli_connect("localhost","root","","vidasilvestre");               
$SQL="SELECT mesa_examen.*, 
materia.nombre AS nombre_materia, 
ciclo_lectivo.nombre_ciclo,
nombre_tipo AS nombre_tipo
FROM mesa_examen 
INNER JOIN materia ON mesa_examen.id_materia = materia.id_materia
LEFT JOIN ciclo_lectivo ON mesa_examen.id_ciclo = ciclo_lectivo.id_ciclo
LEFT JOIN tipo ON mesa_examen.id_tipo = tipo.id_tipo";
$dato = mysqli_query($conexion, $SQL);

if($dato -> num_rows >0){
while($fila=mysqli_fetch_array($dato)){
                             
                                    echo "<tr>";
                                    echo "<td>" .$fila['nombre_mesa'] . "</td>";
                                    echo "<td>" .$fila['nombre_materia'] . "</td>";
                                    echo "<td>" .$fila['hora'] . "</td>";
                                    echo "<td>" .$fila['fecha_i'] . "</td>";
                                    echo "<td>" .$fila['fecha_fin'] . "</td>";
                                    echo "<td>" .$fila['nombre_ciclo'] . "</td>";
                                    echo "<td>" .$fila['nombre_tipo'] . "</td>";
                                    echo "</tr>";
                                }
                                $pdf->Output('Reporte.pdf', 'I');
                                echo "</body></html>"; // lineas para convertir a excel
                            }
                                ?>
                                


                            </tbody>      
                       </table>          
            </div>
        </div>
    </div>


