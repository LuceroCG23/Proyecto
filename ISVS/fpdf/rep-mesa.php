
<?php

require('fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      include '../conn/connection.php';//llamamos a la conexion BD

     $consulta_info = $conexion->query("SELECT mesa_examen.*, 
      materia.nombre AS nombre_materia, 
      ciclo_lectivo.nombre_ciclo,
      nombre_tipo AS nombre_tipo
FROM mesa_examen 
INNER JOIN materia ON mesa_examen.id_materia = materia.id_materia
LEFT JOIN ciclo_lectivo ON mesa_examen.id_ciclo = ciclo_lectivo.id_ciclo
LEFT JOIN tipo ON mesa_examen.id_tipo = tipo.id_tipo"); ///traemos datos de la empresa desde BD
     // $dato_info = $consulta_info->fetch_object();
      $this->Image('logo1.png', 25, 5, 30); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(45); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode('Instituto Superior Vida Silvestre'),0, 2, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Cell(110, 3, utf8_decode('Naturaleza y Aventura'), 0, 2, 'C', 0);
      $this->Ln(10); // Salto de línea
      $this->SetTextColor(103); //color


      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(15, 127, 27);
      $this->Cell(50); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("MESAS DE EXAMEN "), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(150, 194, 64); //colorFondo
      $this->SetTextColor(0, 0, 0); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(38, 10, utf8_decode('Nombre de Mesa'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Materia'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('Hora'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('Fecha Inicio'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('Fecha Fin'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('Año'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('Tipo'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

 //include '../conn/connection.php';
//require '../../funciones/CortarCadena.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */
//$consulta_info = $conexion->query(" select *from hotel ");
//$dato_info = $consulta_info->fetch_object();

$pdf = new PDF();
$pdf->AddPage(); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 10);
$pdf->SetDrawColor(163, 163, 163); //colorBorde
include '../conn/connection.php';
$consulta_mesa = $conexion->query("SELECT mesa_examen.*, 
materia.nombre AS nombre_materia, 
ciclo_lectivo.nombre_ciclo,
nombre_tipo AS nombre_tipo
FROM mesa_examen 
INNER JOIN materia ON mesa_examen.id_materia = materia.id_materia
LEFT JOIN ciclo_lectivo ON mesa_examen.id_ciclo = ciclo_lectivo.id_ciclo
LEFT JOIN tipo ON mesa_examen.id_tipo = tipo.id_tipo");

while ($datos_reporte = $consulta_mesa->fetch_object()) {      
  
$i = $i + 1;
/* TABLA  |x|  |y| el tamaño tiene q ser el mismo en todo el doc. */
$pdf->Cell(38, 10, utf8_decode($datos_reporte->nombre_mesa), 1, 0, 'C', 0);
$pdf->Cell(40, 10, utf8_decode($datos_reporte->nombre_materia), 1, 0, 'C', 0);
$pdf->Cell(20, 10, utf8_decode($datos_reporte->hora), 1, 0, 'C', 0);
$pdf->Cell(25, 10, utf8_decode($datos_reporte->fecha_i), 1, 0, 'C', 0);
$pdf->Cell(25, 10, utf8_decode($datos_reporte->fecha_fin), 1, 0, 'C', 0);
$pdf->Cell(20, 10, utf8_decode($datos_reporte->nombre_ciclo), 1, 0, 'C', 0);
$pdf->Cell(25, 10, utf8_decode($datos_reporte->nombre_tipo), 1, 1, 'C', 0);
 }

$pdf->Output('Reporte.pdf', 'I');//nombreDescarga, Visor(I->visualizar - D->descargar)
