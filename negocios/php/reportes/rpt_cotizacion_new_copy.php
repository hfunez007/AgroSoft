<?php

//
// exemple de facture avec mysqli
// gere le multi-page
// Ver 1.0 THONGSOUME Jean-Paul
//

session_start();

include "../../../datos/conexion_mysql.php";
global $conn;

require('../../../fpdf/fpdf.php');
require('../../../fpdf/WriteTag.php');

$pdf = new PDF_WriteTag('P', 'mm', 'letter');
$pdf->SetTitle(utf8_decode("Presupuesto"));
$pdf->SetMargins(10, 8, 10);
$pdf->AliasNbPages();
$widthContent = 200;
// on sup les 2 cm en bas
$pdf->SetAutoPagebreak(False);

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

$var_id_facture = $_GET['id'];

// nb de page pour le multi-page : 18 lignes
$query = "SELECT COUNT(*) FROM t_cotizaciondet WHERE cot_id  = " . $_GET["id"] . " ";
$consulta = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($consulta)) {
	$total_registros = $row[0];
}

$nb_page = ceil($total_registros / 21);

// $nb_page = 2;
$num_page = 1;
$limit_inf = 0;
$limit_sup = 19;

while ($num_page <= $nb_page) {
	$pdf->AddPage();

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

	// Numero de Paginas en encabezado
	$pdf->SetXY(120, 5);
	$pdf->SetFont("Arial", "B", 12);
	$pdf->Cell(160, 8, $num_page . '/' . $nb_page, 0, 0, 'C');

	$pdf->SetFont('Calibri', 'BI', 9);
	$pdf->SetXY(90, 10);
	$pdf->Cell(200, 6, "RTN: 0801 9998 391040 ", '', 0, 'C');
	$pdf->Ln(20);

	$pdf->SetXY(10, 35);
	$pdf->SetFont('Calibri', 'BI', 18);
	$pdf->Cell(200, 6, "COTIZACION", '', 0, 'C');

	$pdf->SetXY(172, 35);
	$pdf->SetFont('Calibri', 'BI', 9);
	$pdf->Cell(55, 6,  $codigo, '', 0, 'L');
	$pdf->Ln(10);

	if (!empty($proceso)) {
		$pdf->SetXY(4, 42);
		$txt = "PROCESO: ";
		$pdf->Cell(55, 5, utf8_decode($txt) . $proceso, '', 0, 'L');

		$pdf->SetXY(85, 42);
		$txt = "APERTURA: ";
		$pdf->Cell(55, 5, utf8_decode($txt) . $fechaapertura, '', 0, 'L');
	}

	$pdf->SetXY(172, 42);
	$txt = "FECHA: " . $fecha;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetFont('Calibri', '', 8);
	// SEÑORES
	$pdf->SetXY(4, 46);
	$txt = "SEÑORES: " . $cliente;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	// Contacto, Email y Telfonos
	$pdf->SetXY(4, 50);
	$txt = "DIRECCION: " . $direccion;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetXY(4, 54);
	$txt = "ATENCION: " . $contacto;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetXY(85, 54);
	$txt = "CORREO: " . $email;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetXY(160, 54);
	$txt = "TELEFONO: " . $telefono;
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetFont('Calibri', 'BI', 9);
	$pdf->SetXY(4, 58);
	$txt = "Validez de la oferta: " . $validez . " dias a partir de la fecha de la cotizacion.";
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetXY(110, 58);
	$txt = "Tiempo de entrega: " . $entrega . " dias despues de recibida la orden de compra. ";
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');

	$pdf->SetXY(4, 62);
	$txt = "Por medio de la presente nos permitimos COTIZAR los productos que a contiuación detallamos: ";
	$pdf->Cell(55, 5, utf8_decode($txt), '', 0, 'L');
	$pdf->Ln(10);

	// Label GRIS de totales
	if ($num_page == $nb_page) {
		// les totaux, on n'affiche que le HT. le cadre apr�s les lignes, demarre a 213
		$pdf->SetLineWidth(0.1);
		$pdf->SetFillColor(192);
		$pdf->Rect(5, 213, 115, 20, "DF");

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
		$y = 221;
		while ($row4 = mysqli_fetch_array($result4)) {
			$pdf->SetFont('Calibri', 'B', 10);

			$pdf->SetXY(125, $y);
			$pdf->Cell(30, 6, utf8_decode($row4[0]), '', 0, 'R');

			$pdf->SetXY(170, $y);
			$pdf->Cell(35, 6, utf8_decode($row4[1]), '', 0, 'R');
			$y = $y + 6;
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
		$y = 239;
		while ($row5 = mysqli_fetch_array($result5)) {
			$pdf->SetFont('Calibri', 'B', 10);

			$pdf->SetXY(95, $y);
			$pdf->Cell(60, 6, utf8_decode($row5[0]), '', 0, 'R');

			$pdf->SetXY(170, $y);
			$pdf->Cell(35, 6, utf8_decode($row5[1]), '', 0, 'R');
			$y = $y + 6;
		}

		$query6 = " SELECT   SUM((a.cotd_cantidad * a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio))) as Total,
							 (select letras(SUM((a.cotd_cantidad * a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio))),'LPS')) AS ValorLetra
					FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id 
					WHERE a.cot_id = " . $_GET["id"] . " ";
		$result6 = mysqli_query($conn, $query6);
		while ($row6 = mysqli_fetch_array($result6)) {
			$grantotal = $row6[0];
			$totalLetas = $row6[1];
		}
		$pdf->SetXY(170, 252);
		$pdf->Cell(35, 6, utf8_decode(number_format($grantotal, 2)), 0, 0, 'R');

		$pdf->SetFont('Calibri', 'B', 9);
		$pdf->SetXY(5, 216);
		$pdf->Cell(50, 14, utf8_decode($totalLetas), 0, 0, 'L');
	}

	// ***********************
	// DETALLE DE LA TABLA
	// ***********************

	// cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
	$pdf->SetLineWidth(0.1);
	$pdf->Rect(5, 75, 200, 123, "D");

	// cadre titre des colonnes
	$pdf->Line(5, 85, 205, 85);

	// les traits verticaux colonnes
	$pdf->Line(20, 75, 20, 198);
	$pdf->Line(50, 75, 50, 198);
	$pdf->Line(165, 75, 165, 198);
	$pdf->Line(183, 75, 183, 198);

	// titre colonne
	$pdf->SetXY(2, 76);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(20, 8, "CANT.", 0, 0, 'C');

	$pdf->SetXY(25, 76);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(13, 8, "UNIDAD", 0, 0, 'C');

	$pdf->SetXY(100, 76);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(22, 8, "DESCRIPCION", 0, 0, 'C');

	$pdf->SetXY(169, 76);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(10, 8, "PRECIO", 0, 0, 'C');

	$pdf->SetXY(183, 76);
	$pdf->SetFont('Arial', 'B', 8);
	$pdf->Cell(22, 8, "TOTAL", 0, 0, 'C');

	// les articles
	$pdf->SetFont('Arial', '', 8);
	$y = 76;

	$query2 = " SELECT a.cotd_id, a.prod_id, b.nombre_prod, format(a.cotd_cantidad,0), b.prod_tipoisv, 
                   format((a.cotd_cantidad * a.prod_precio),2) AS SubTotal, 
                    format((b.prod_tipoisv/100)*(a.cotd_cantidad*a.prod_precio),2) as ISV, 
                    format((a.cotd_cantidad*a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio)),2) as Total, a.cot_id, 
                    format(a.prod_precio,2), c.medida_nombre
                    FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id 
                                            INNER JOIN t_unidadmedida c ON b.medida_id = c.medida_id
                    WHERE cot_id = " . $_GET["id"] . " ";

	// 1ere page = LIMIT 0,18 ;  2eme page = LIMIT 18,36 etc...
	$query2 .= ' LIMIT ' . $limit_inf . ',' . $limit_sup;
	$res = mysqli_query($conn, $query2);
	while ($row1 =  mysqli_fetch_array($res)) {
		// cantidad
		$pdf->SetXY(5, $y + 9);
		$pdf->Cell(15, 5, utf8_decode($row1[3]), '', 0, 'C');

		// UNIDAD
		$pdf->SetXY(20, $y + 9);
		$pdf->Cell(30, 5, utf8_decode($row1[10]), '', 0, 'C');

		// DESCRIPCION		
		$pdf->SetXY(50, $y + 9);
		$pdf->Cell(120, 5, utf8_decode($row1[2]), '', 0, 'L');

		// PRECIO
		$pdf->SetXY(165, $y + 9);
		$pdf->Cell(18, 5, utf8_decode($row1[9]), '', 0, 'L');

		// total
		$pdf->SetXY(183, $y + 9);
		$pdf->Cell(17, 5, utf8_decode($row1[5]), '', 0, 'L');

		$pdf->Line(5, $y + 14, 205, $y + 14);

		$y += 6;
	}
	mysqli_free_result($res);

	// si derniere page alors afficher cadre des TVA
	if ($num_page == $nb_page) {
		// le detail des totaux, demarre a 221 apr�s le cadre des totaux
		$pdf->SetLineWidth(0.1);
		$pdf->Rect(120, 213, 85, 45, "D");

		// les traits verticaux
		// $pdf->Line(147, 221, 147, 245);
		// $pdf->Line(164, 221, 164, 245);
		$pdf->Line(155, 213, 155, 258);

		// les traits horizontaux pas de 6 et demarre a 221
		$pdf->Line(120, 220, 205, 220);
		$pdf->Line(120, 227, 205, 227);
		$pdf->Line(120, 233, 205, 233);
		$pdf->Line(120, 239, 205, 239);
		$pdf->Line(120, 245, 205, 245);
		$pdf->Line(120, 251, 205, 251);

		// les titres
		$pdf->SetFont('Calibri', 'B', 10);

		$pdf->SetXY(131, 214);
		$pdf->Cell(24, 6, "SubTotal", '', 0, 'R');

		// Total del Detalle
		$pdf->SetXY(131, 252);
		$pdf->Cell(24, 6, 'Total', '', 0, 'R');

		if ($estado == 2) {
			$pdf->Image("../../../assets/images/firma.png", 40, $pdf->GetY() - 18, 38);
		}

		$x = 170;

		$query3 = "SELECT SUM(a.cotd_cantidad * a.prod_precio) as Total FROM t_cotizaciondet a WHERE cot_id = " . $_GET["id"] . " ";
		$res = mysqli_query($conn, $query3);
		while ($data =  mysqli_fetch_array($res)) {

			$pdf->SetXY($x, 214);
			$pdf->Cell(35, 6, utf8_decode(number_format($data[0], 2)), '', '', 'R');
		}
		mysqli_free_result($res);
	}

	// **************************
	// pied de page
	// **************************
	$pdf->SetLineWidth(0.1);
	$pdf->Rect(5, 260, 200, 6, "D");
	$pdf->SetXY(1, 260);
	$pdf->SetFont('Arial', '', 7);
	$pdf->Cell($pdf->GetPageWidth(), 7, "CALIDAD, EFICIENCIA Y BUEN SERVICIO", 0, 0, 'C');
	$y1 = 270;

	// par page de 18 lignes
	$num_page++;
	$limit_inf += 21;
	$limit_sup += 21;
}

// $pdf->Output("I", $nom_file);
$pdf->Output();
