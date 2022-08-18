<?php

session_start();

include "../../../datos/conexion_mysql.php";
global $conn;

require('../../../fpdf/fpdf.php');
require('../../../fpdf/WriteTag.php');

$pdf = new PDF_WriteTag('P', 'mm', 'letter');


$pdf->SetTitle(utf8_decode("Presupuesto"));

$pdf->SetMargins(10, 8, 10);
$pdf->AddPage();
$pdf->AliasNbPages();
$widthContent = 200;
$pdf->SetAutoPageBreak(true, 10);

// Stylesheet
$pdf->AddFont('Calibri', '', 'Calibri.php');
$pdf->AddFont('Calibri', 'B', 'CalibriB.php');
$pdf->AddFont('Calibri', 'BI', 'CalibriBI.php');
$pdf->AddFont('Calibri', 'I', 'CalibriI.php');

$pdf->SetStyle("h1", "Calibri", "I", 20, "5,5,5", 5);
$pdf->SetStyle("h2", "Calibri", "I", 16, "5,5,5", 5);

$pdf->SetStyle("p", "Calibri", "B", 11, "5,5,5");
$pdf->SetStyle("b", "Calibri", "", 15, "5,5,5");
$pdf->SetStyle("bu", "Calibri", "", 11, "5,5,5");

$pdf->SetStyle("li", "times", "", 12, "5,5,5", 6);
$pdf->SetStyle("lii", "times", "", 12, "5,5,5", 11);
$pdf->SetStyle("ident", "times", "", 12, "5,5,5", 40);

$query = " SELECT ROW_NUMBER() OVER(ORDER BY a.cot_id ASC) AS 'fila', a.cot_id, DATE_FORMAT(a.cot_fecha, '%Y-%m-%d') AS Fecha, b.cli_nombre, 
                b.cli_rtn, b.cli_telefonos, b.cli_contacto, b.cli_email, a.cot_estado, a.cot_codigo, b.cli_direccion, a.cot_proceso,
                a. cot_fechaapertura, a.cot_validez, a.cot_entrega
            FROM t_cotizacion a INNER JOIN t_clientes b ON a.cli_id = b.cli_id
            WHERE a.cot_id = " . $_GET["id"] . " ";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $fecha = $row[2];
    $cliente = $row[3];
    $rtn = $row[4];
    $telefono = $row[5];
    $contacto = $row[6];
    $email = $row[7];
    $codigo = $row[9];
    $direccion = $row[10];
    $estado = $row[8];

    $proceso = $row[11];
    $fechaapertura = $row[12];
    $validez = $row[13];
    $entrega = $row[14];
}
// Logo
$pdf->Image("../../../assets/images/logoPH1.png", 8, 8, 70);
$pdf->Image("../../../assets/images/datosPH1.png", 80, 9, 80);
$pdf->Ln(10);

$pdf->SetFont('Calibri', 'BI', 9);
$pdf->SetX(85);
$pdf->Cell(200, 6, "RTN: 0801 9998 391040 ", '', 0, 'C');
$pdf->Ln(20);

$pdf->SetX(9);
$pdf->SetFont('Calibri', 'BI', 18);
$pdf->Cell(200, 6, "COTIZACION", '', 0, 'C');

$pdf->SetX(172);
$pdf->SetFont('Calibri', 'BI', 9);
$pdf->Cell(55, 6,  $codigo, '', 0, 'L');
$pdf->Ln(10);

if (!empty($proceso)) {
    $pdf->SetX(9);
    $txt = "PROCESO: ";
    $pdf->Cell(55, 5, utf8_decode($txt) . $proceso, '', 0, 'L');

    $pdf->SetX(85);
    $txt = "APERTURA: ";
    $pdf->Cell(55, 5, utf8_decode($txt) . $fechaapertura, '', 0, 'L');
}

$pdf->SetX(172);
$txt = "FECHA: " . $fecha;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(5);

$pdf->SetFont('Calibri', '', 8);

// SEÑORES
$pdf->SetX(9);
$txt = "SEÑORES: " . $cliente;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(5);

// Contacto, Email y Telfonos
$pdf->SetX(9);
$txt = "DIRECCION: " . $direccion;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(5);

$pdf->SetX(9);
$txt = "ATENCION: " . $contacto;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

$pdf->SetX(85);
$txt = "CORREO: " . $email;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

$pdf->SetX(160);
$txt = "TELEFONO: " . $telefono;
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(5);

$pdf->SetFont('Calibri', 'BI', 9);
$pdf->SetX(9);
$txt = "Validez de la oferta: " . $validez . " dias a partir de la fecha de la cotizacion.";
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');


$pdf->SetX(110);
$txt = "Tiempo de entrega: " . $entrega . " dias despues de recibida la orden de compra. ";
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(5);

$pdf->SetX(9);
$txt = "Por medio de la presente nos permitimos COTIZAR los productos que a contiuación detallamos: ";
$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
$pdf->Ln(10);

// Linea
$pdf->SetX(4);
$txt = "<p>___</p> ";
$pdf->MultiCell(201, 0, " ", 'T', "L", 0);
$pdf->Ln();
// Detalle 
$query2 = " SELECT a.cotd_id, a.prod_id, b.nombre_prod, format(a.cotd_cantidad,0), b.prod_tipoisv, 
                   format((a.cotd_cantidad * a.prod_precio),2) AS SubTotal, 
                    format((b.prod_tipoisv/100)*(a.cotd_cantidad*a.prod_precio),2) as ISV, 
                    format((a.cotd_cantidad*a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio)),2) as Total, a.cot_id, 
                    format(a.prod_precio,2), c.medida_nombre
                    FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id 
                                            INNER JOIN t_unidadmedida c ON b.medida_id = c.medida_id
                    WHERE cot_id = " . $_GET["id"] . " ";

$result2 = mysqli_query($conn, $query2);

$pdf->SetFont('Calibri', 'B', 10);

$pdf->SetX(4);
$pdf->Cell(20, 5, 'CANT.', 'B', 0, 'L');

$pdf->SetX(20);
$pdf->Cell(26, 5, 'UNIDAD', 'B', 0, 'L');

$pdf->SetX(46);
$pdf->Cell(108, 5, 'DESCRIPCION', 'B', 0, 'C');

$pdf->SetX(154);
$pdf->Cell(35, 5, 'PRECIO', 'B', 0, 'L');

$pdf->SetX(180);
$pdf->Cell(25, 5, 'TOTAL', 'B', 0, 'L');

$pdf->Ln(8);

while ($row1 = mysqli_fetch_array($result2)) {
    $pdf->SetFont('Calibri', '', 9);

    $current_y = $pdf->GetY();
    $cell_width = 40;  //define cell width
    $cell_height = 2;  //define cell height
    $cell_width1 = 98;

    // CANTIDAD
    $pdf->SetX(4);
    $pdf->MultiCell(12, $cell_height, utf8_decode($row1[3]), '', "L", 0);

    // UNIDAD
    $pdf->SetXY(16, $current_y);
    $pdf->MultiCell(30, $cell_height, utf8_decode($row1[10]), '', "L", 0);

    // Descripcion
    $pdf->SetXY(46, $current_y);
    $pdf->MultiCell(108, $cell_height, utf8_decode($row1[2]), '', "L", 0);

    // // PRECIO
    $pdf->SetXY(154, $current_y);
    $pdf->MultiCell($cell_width, $cell_height, utf8_decode($row1[9]), '', "L", 0);

    // // SubTotal
    $pdf->SetXY(180, $current_y);
    $pdf->MultiCell($cell_width, $cell_height, utf8_decode($row1[5]), '', "L", 0);

    $pdf->Ln();
}
// Linea final de detalle de productos.
$pdf->SetX(4);
$txt = "<p>___</p> ";
$pdf->MultiCell(201, 0, " ", 'T', "L", 0);
$pdf->Ln();

// $a = $pdf->GetY();
// $pdf->SetY($a + 90);
// Totales al pie


// $pdf->Footer();


// informacion de totales
// SubTotal del Detalle
$pdf->SetFont('Calibri', 'B', 10);
$pdf->SetX(4);

$pdf->Cell(160, 6, 'SubTotal:', '', 0, 'R');
$query3 = "SELECT SUM(a.cotd_cantidad * a.prod_precio) as Total
       FROM t_cotizaciondet a WHERE cot_id = " . $_GET["id"] . " ";
$result3 = mysqli_query($conn, $query3);
while ($row2 = mysqli_fetch_array($result3)) {
    $total = $row2[0];
}

$pdf->SetX(180);
$pdf->Cell(60, 6, utf8_decode(number_format($total, 2)), 0, 0, 'L');
$pdf->Ln(6);

// detalle de ventas por impuesto
$query4 = " SELECT * FROM (
SELECT 'Ventas Exentas', CASE WHEN b.prod_tipoisv = 0 THEN FORMAT (SUM(a.cotd_cantidad * a.prod_precio),2) ELSE '0.00' END
FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '0'
UNION ALL 
SELECT 'Ventas Grabadas 15%', CASE WHEN b.prod_tipoisv = 15 THEN FORMAT (SUM(a.cotd_cantidad * a.prod_precio),2) ELSE '0.00' END
FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '15'
UNION ALL 
SELECT 'Ventas Grabadas 18%', CASE WHEN b.prod_tipoisv = 18 THEN FORMAT (SUM(a.cotd_cantidad * a.prod_precio),2) ELSE '0.00' END
FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '18'
) A; ";


$result4 = mysqli_query($conn, $query4);
while ($row4 = mysqli_fetch_array($result4)) {
    $pdf->SetFont('Calibri', 'B', 10);

    $pdf->SetX(4);
    $pdf->Cell(160, 6, utf8_decode($row4[0]), '', 0, 'R');

    $pdf->SetX(180);
    $pdf->Cell(200, 6, utf8_decode($row4[1]), '', 0, 'L');
    $pdf->Ln();
}

if ($estado == 2) {

    // $pdf->Ln(15);
    $pdf->SetFont('Calibri', 'B', 10);

    $pdf->Image("../../../assets/images/firma.png", 30, $pdf->GetY() - 18, 40);
    // $pdf->Image("../../../assets/images/firmas/sello1.png", 45, $pdf->GetY() - 20, 25);
    // $pdf->Ln();
    // $pdf->SetX(25);
    // $txt = "Fredy Antonio Galo Matamoros";
    // $pdf->Cell(40, 5, utf8_decode($txt), '', 0, 'L');
    // $pdf->Ln();
    // $pdf->SetX(34);
    // $txt = "Gerente de Ventas";
    // $pdf->Cell(40, 5, utf8_decode($txt), '', 0, 'L');
}

// detalle de IMPUESTOS
// SELECT 'Exento', CASE WHEN b.prod_tipoisv = 0 THEN FORMAT(SUM((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio)),2) ELSE '0.00' END
// FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
// WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '0'
// UNION ALL 
$query5 = " SELECT * FROM (
SELECT '15% ISV', CASE WHEN b.prod_tipoisv = 15 THEN FORMAT(SUM((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio)),2) ELSE '0.00' END
FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '15'
UNION ALL 
SELECT '18% ISV', CASE WHEN b.prod_tipoisv = 18 THEN FORMAT(SUM((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio)),2) ELSE '0.00' END
FROM t_cotizaciondet a INNER JOIN t_productos b ON a.prod_id = b.prod_id 
WHERE a.cot_id = " . $_GET["id"] . " and b.prod_tipoisv = '18'
) A; ";
$result5 = mysqli_query($conn, $query5);
while ($row5 = mysqli_fetch_array($result5)) {
    $pdf->SetFont('Calibri', 'B', 10);

    $pdf->SetX(104);
    $pdf->Cell(60, 6, utf8_decode($row5[0]), '', 0, 'R');

    $pdf->SetX(180);
    $pdf->Cell(200, 6, utf8_decode($row5[1]), '', 0, 'L');
    $pdf->Ln();
}

// Linea
$pdf->SetX(4);
$txt = "<p>___</p> ";
$pdf->MultiCell(201, 0, " ", 'T', "L", 0);
$pdf->Ln();

// Total del Detalle
$pdf->SetFont('Calibri', 'B', 10);
$pdf->SetX(4);
$pdf->Cell(160, 6, 'Total:', '', 0, 'R');

$query6 = " SELECT   SUM((a.cotd_cantidad * a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio))) as Total
        FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id 
        WHERE a.cot_id = " . $_GET["id"] . " ";
$result6 = mysqli_query($conn, $query6);
while ($row6 = mysqli_fetch_array($result6)) {
    $grantotal = $row6[0];
}

$pdf->SetX(180);
$pdf->Cell(60, 6, utf8_decode(number_format($grantotal, 2)), 0, 0, 'L');
// $pdf->Ln(6);

// $pdf->Ln(35);
// $pdf->Image("../../../assets/images/firma_innova.jpeg", 90, $pdf->GetY() - 30, 40);
// FIRMAS



$pdf->Output();
