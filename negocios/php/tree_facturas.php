<?php
include "../../datos/conexion_mysql.php";

session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);

if ($_POST["Actividad"] == 'lst_facturas') {
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY a.fact_id ASC) AS 'fila', a.fact_id,  a.fact_codigo, 
                    DATE_FORMAT(a.fact_fecha, '%Y-%m-%d') Fecha, b.cli_nombre, CASE WHEN fact_tipo = 1 THEN 'CONTADO' ELSE 'CREDITO' END,
                    FORMAT(fact_total,2) as Total, 
                    format(fact_pagado,2) pagado,  
                    format(fact_total-fact_pagado,2) as Pendiente
            FROM t_factura a INNER JOIN t_clientes b ON a.cli_id = b.cli_id; ";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[6] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[8] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="actualizar_factura(' . $row[1] . ');"><i class="fas fa-file-signature"></i></button></td>';
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'insertar_factura') {

    $fecha = $_POST['fecha'];
    $cliente = $_POST['cliente'];
    $tipoFactura = $_POST['tipoFactura'];
    $tipoCodigo = $_POST['tipoCodigo'];
    $empresa = $_POST['empresa'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validad_factura($cliente, $empresa);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $cliente = trim($cliente);

        // GenerarCodigo si es Recibo
        if ($tipoCodigo == 1) {
            $query = "SELECT Count(*) FROM t_factura WHERE DATE_FORMAT(fact_fecha, '%Y-%m-%d') = '$fecha';";
            $consulta = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($consulta)) {
                $totalcot = $row[0];
            }

            if ($totalcot == 0) {
                $contador = 1;
            } else {
                $contador = $totalcot + 1;
            }

            $codigofactura = 'RCB-' . str_replace('-', '', $fecha) . '-' . $contador;
        } else {
            $query = "SELECT CONCAT(fact_rangoa1,'-', fact_rangoa2, '-', fact_rangoa3, '-', fact_rangoa4, '-', LPAD(fact_valorinicial+1,8,'0')) Codigo, 
                             LPAD(fact_valorinicial+1,8,'0')
                      FROM t_facturacion WHERE fact_estado = 1 and emp_id = '$empresa';";

            $consulta = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($consulta)) {
                $codigofactura = $row[0];
                $valorinicial = $row[1];
            }
        }
        $query1 = " INSERT INTO t_factura ( fact_codigo, cli_id, fact_fecha, fact_tipo, fact_recibo, usu_id, emp_id) 
                        VALUES('$codigofactura', '$cliente', '$fecha', '$tipoFactura', '$tipoCodigo', '$cod_user', '$empresa' ); ";
        $consulta2 = mysqli_query($conn, $query1);

        // Obtener ultimo id 
        $query3 = " select last_insert_id() t_factura;";
        $consulta3 = mysqli_query($conn, $query3);
        while ($row = mysqli_fetch_array($consulta3)) {
            $lastID = $row[0];
        }

        if (!$consulta2) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
            // actualizar valor inicial en facturas
            $query2 = "UPDATE t_facturacion SET fact_valorinicial = '$valorinicial' WHERE fact_estado = 1 and emp_id = '$empresa';";
            $consulta2 = mysqli_query($conn, $query2);
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje, "fact_id" => $lastID);
    echo json_encode($result);
}

function validad_factura($cliente, $empresa)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $cliente = trim($cliente);
    $empresa = trim($empresa);

    //Validar si no vienene vacios
    if (empty($cliente)) {
        $validacioncuerpo .= '<tr><td>Seleccione Cliente</td> </tr>';
    }
    if (empty($empresa)) {
        $validacioncuerpo .= '<tr><td>Seleccione Empresa</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'showmodal_factura') {

    $factura = $_POST['factura'];

    $query = "SELECT a.fact_id, fact_codigo, DATE_FORMAT(a.fact_fecha, '%Y-%m-%d') Fecha, a.cli_id, a.fact_tipo, a.fact_recibo, a.emp_id
              FROM t_factura a WHERE a.fact_id = '$factura';";

    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_factura[] = $row1[0];
        $showmodal_factura[] = $row1[1];
        $showmodal_factura[] = $row1[2];
        $showmodal_factura[] = $row1[3];
        $showmodal_factura[] = $row1[4];
        $showmodal_factura[] = $row1[5];
        $showmodal_factura[] = $row1[6];
    }
    echo json_encode($showmodal_factura);
}

if ($_POST["Actividad"] == 'lst_facturadetalle') {

    $cod_user = $_SESSION['codusu'];
    $factura = $_POST['factura'];

    $query = " SELECT a.factd_id, a.prod_id, concat(b.nombre_prod,' ( ',c.medida_nombre, ' )') as Producto, 
                    CASE WHEN b.prod_tipoisv = 0 THEN '0.00' WHEN b.prod_tipoisv = 15 THEN '15%' ELSE '18%' END AS TipoISV,
                    format(a.factd_precio,2) AS Precio, a.factd_cantidad, 
                    CASE WHEN b.prod_tipoisv = 0 THEN '0.00' WHEN b.prod_tipoisv = 15 THEN format((a.factd_cantidad *  a.factd_precio) * 0.15,2)
                        ELSE format((a.factd_cantidad *  a.factd_precio) * 0.18,2) END AS ISV, 
                    CASE WHEN b.prod_tipoisv = 0 THEN format((a.factd_cantidad *  a.factd_precio),2) 
                        WHEN b.prod_tipoisv = 15 THEN format(  ( (a.factd_cantidad * a.factd_precio) * 0.15 ) + (a.factd_cantidad * a.factd_precio),2)
                        ELSE format(  ( (a.factd_cantidad * a.factd_precio) * 0.18 ) + (a.factd_cantidad * a.factd_precio),2) END AS ISV, a.fact_id, a.prod_id
                FROM t_facturadetalle a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                                         INNER JOIN t_unidadmedida c on b.medida_id = c.medida_id
                WHERE fact_id = '$factura'; ";

    $result = mysqli_query($conn, $query);
    $html = "";

    $html .= ' <tbody style="font-size: 13px;"> ';
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[6] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" 
                                             onclick="eliminar_lineadetallefactura(' . $row[0] . ',' . $row[1] . ',' . $row[5] . ');">
                                             <i class="fas fa-trash-alt"></i>
                                    </button></td>';
        $html .= "</tr>";
    }
    $html .= ' </tbody> ';

    // Sacar el total y ponerlo en el footer 
    $query1 = "SELECT format( SUM( ( (a.factd_cantidad * a.factd_precio) * (b.prod_tipoisv/100) ) + (a.factd_cantidad * a.factd_precio)),2)
                FROM t_facturadetalle a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                WHERE fact_id = '$factura'; ";
    $consulta1 = mysqli_query($conn, $query1);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $total = $row1[0];
    }

    if ($total != '') {
        $html .= ' <tfoot> ';
        $html .= ' 	<tr> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center">Total </th> ';
        $html .= ' 		<th style="text-align: center"> ' . $total . ' </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 	</tr> ';
        $html .= ' </tfoot>';
    } else {
        $html .= ' <tfoot> ';
        $html .= ' 	<tr> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center">Total </th> ';
        $html .= ' 		<th style="text-align: center"> 0.00 </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 	</tr> ';
        $html .= ' </tfoot>';
    }
    // echo $html;
    echo json_encode($html);
}


if ($_POST["Actividad"] == 'ObtenerPrecio') {
    $producto = $_POST['producto'];

    $query = "SELECT prod_precioventa, format(prod_disponible,0) FROM t_productos WHERE prod_id = '$producto';";
    $consulta1 = mysqli_query($conn, $query);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_ingreso[] = $row1[0];
        $showmodal_ingreso[] = $row1[1];
    }
    echo json_encode($showmodal_ingreso);
}

if ($_POST["Actividad"] == 'instertar_detfactura') {

    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $factura = $_POST['factura'];
    $disponible = $_POST['disponible'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = valida_det($producto, $cantidad, $precio);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $producto = trim($producto);
        $precio = trim($precio);
        $cantidad = trim($cantidad);

        $query = " INSERT INTO t_facturadetalle ( fact_id, prod_id, factd_precio, factd_cantidad, usu_id) 
                   VALUES ('$factura', '$producto', '$precio', '$cantidad','$cod_user'); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";

            // Restar producto al disponible
            $query2 = "UPDATE t_productos SET prod_disponible = prod_disponible - '$cantidad' WHERE prod_id = '$producto';";
            $consulta2 = mysqli_query($conn, $query2);

            // Sacar el total del Ingreso            
            $query3 = "SELECT coalesce(SUM(a.factd_cantidad * a.factd_precio),0)
                        FROM t_facturadetalle a WHERE fact_id = '$factura'; ";


            $consulta3 = mysqli_query($conn, $query3);

            while ($row3 = mysqli_fetch_array($consulta3)) {
                $total = $row3[0];
            }

            // Actualizar el total del Ingreso
            $query4 = "UPDATE t_factura SET fact_total = '$total' WHERE fact_id = '$factura';";
            $consulta2 = mysqli_query($conn, $query4);
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje, "cot_id" => $lastID, "cot_codigo" => $codigo);
    echo json_encode($result);
}

function valida_det($producto, $cantidad, $precio)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $producto = trim($producto);
    $precio = trim($precio);
    $cantidad = trim($cantidad);

    //Validar si no vienene vacios
    if (empty($producto)) {
        $validacioncuerpo .= '<tr><td>Seleccione Producto</td> </tr>';
    }
    if (empty($precio) || $precio == 0) {
        $validacioncuerpo .= '<tr><td>Ingrese Precio del Producto</td> </tr>';
    }
    if (empty($cantidad) || $cantidad == 0) {
        $validacioncuerpo .= '<tr><td>Ingrese Cantidad del Producto</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'eliminar_lineadetallefactura') {

    $facturad = $_POST['facturad'];
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $factura = $_POST['factura'];

    $query = "DELETE FROM t_facturadetalle WHERE factd_id = '$facturad';";

    $consulta = mysqli_query($conn, $query);
    if (!$consulta) {
        $mensaje = "error";
    } else {
        $mensaje = "exito";
        // Sumar producto al disponible
        $query2 = "UPDATE t_productos SET prod_disponible = prod_disponible + '$cantidad' WHERE prod_id = '$producto';";
        $consulta2 = mysqli_query($conn, $query2);

        // Sacar el total del Ingreso            
        $query3 = "SELECT coalesce(SUM(a.factd_cantidad * a.factd_precio),0)
                    FROM t_facturadetalle a WHERE fact_id = '$factura'; ";
        $consulta3 = mysqli_query($conn, $query3);

        while ($row3 = mysqli_fetch_array($consulta3)) {
            $total = $row3[0];
        }

        // Actualizar el total del Ingreso
        $query4 = "UPDATE t_factura SET fact_total = '$total' WHERE fact_id = '$factura';";
        $consulta2 = mysqli_query($conn, $query4);
    }
    echo json_encode($mensaje);
}

if ($_POST["Actividad"] == 'InsertarPagosFactura') {
    $factura = $_POST['factura'];

    $query = "SELECT COUNT(*) FROM t_facturadetalle WHERE fact_id = '$factura';";
    $consulta1 = mysqli_query($conn, $query);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_ingreso[] = $row1[0];
    }
    echo json_encode($showmodal_ingreso);
}

if ($_POST["Actividad"] == 'fcn_datosgenerales') {
    $facturaID = $_POST['facturaID'];

    $query = "SELECT a.fact_id, DATE_FORMAT(a.fact_fecha, '%Y-%m-%d') AS Fecha, a.fact_codigo,
                    b.cli_nombre, CASE WHEN a.fact_tipo = 1 THEN 'CONTADO' ELSE 'CREDITO' END AS TpoFactura,
                    CASE WHEN a.fact_recibo = 1 THEN 'RECIBO' ELSE 'CAI' END AS TipoCodigo,
                    c.emp_nombre,
                    FORMAT(fact_total,2) AS Total, FORMAT(fact_pagado,2) AS Pagado, 
                    FORMAT(fact_total-fact_pagado,2) AS Pendiente, fact_total-fact_pagado
                FROM t_factura a INNER JOIN t_clientes b ON a.cli_id = b.cli_id 
                            INNER JOIN t_empresas c ON a.emp_id = c.emp_id
                WHERE a.fact_id = '$facturaID';";
    $consulta1 = mysqli_query($conn, $query);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_factura[] = $row1[0];
        $showmodal_factura[] = $row1[1];
        $showmodal_factura[] = $row1[2];
        $showmodal_factura[] = $row1[3];
        $showmodal_factura[] = $row1[4];
        $showmodal_factura[] = $row1[5];
        $showmodal_factura[] = $row1[6];
        $showmodal_factura[] = $row1[7];
        $showmodal_factura[] = $row1[8];
        $showmodal_factura[] = $row1[9];
        $showmodal_factura[] = $row1[10];
    }
    echo json_encode($showmodal_factura);
}

if ($_POST["Actividad"] == 'buscar_pagosFactura') {
    $facturaID = $_POST['facturaID'];

    $query = " SELECT pagfact_id, fact_id, DATE_FORMAT(pagfact_fecha, '%Y-%m-%d') fecha, 
                    CASE WHEN pagfact_tipo = 1 then 'EFECTIVO' WHEN pagfact_tipo = 2 THEN 'TARJETA' else 'TRANSFERENCIA' end AS TIPO, 
                    pagfact_recibo,
                    format(pagfact_valor,2) AS Valor 
                FROM t_pagosFacturas WHERE fact_id = '$facturaID';";
    $consulta = mysqli_query($conn, $query);

    $html .= ' <tbody style="font-size: 13px;"> ';
    while ($row = mysqli_fetch_array($consulta)) {
        $html .= '<tr>';
        $html .= '<td class="align-middle" align="center">' . $row[2] . '</td>';
        $html .= '<td class="align-middle" align="center">' . $row[3] . '</td>';
        $html .= '<td class="align-middle" align="center">' . $row[4] . '</td>';
        $html .= '<td class="align-middle" align="center">' . $row[5] . '</td>';
        $html .= "</tr>";
    }

    // Sacar el total y ponerlo en el footer 
    $query1 = "SELECT SUM(pagfact_valor) FROM t_pagosFacturas WHERE fact_id = '$facturaID';";
    $consulta1 = mysqli_query($conn, $query1);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $total = $row1[0];
    }

    if ($total != '') {
        $html .= ' <tfoot> ';
        $html .= ' 	<tr> ';
        // $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center">Total </th> ';
        $html .= ' 		<th style="text-align: center"> ' . number_format($total, 2) . ' </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 	</tr> ';
        $html .= ' </tfoot>';
    } else {
        $html .= ' <tfoot> ';
        $html .= ' 	<tr> ';
        // $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 		<th style="text-align: center">Total </th> ';
        $html .= ' 		<th style="text-align: center"> 0.00 </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 	</tr> ';
        $html .= ' </tfoot>';
    }

    $html1 = $html;
    $mensaje = "exito";

    $result1 = array("mensaje" => $mensaje, "tablatalonario" => $html1, "total" => $total);
    echo json_encode($result1);
}

if ($_POST["Actividad"] == 'insertar_pago') {
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor'];
    $facturaID = $_POST['facturaID'];
    $recibo = $_POST['recibo'];
    $estado = $_POST['estado'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_abono($valor);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        if (empty($recibo)) {
            $recibo = '00000';
        }

        // Insertar Abono
        $query = " INSERT INTO t_pagosFacturas (fact_id, pagfact_fecha, pagfact_tipo, pagfact_recibo, pagfact_valor, usu_id)
                       VALUES ( '$facturaID', '$fecha', '$tipo', '$recibo', '$valor', '$cod_user' ); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";

            // Sacar el total del Ingreso            
            $query3 = "SELECT coalesce(SUM(pagfact_valor),0) FROM t_pagosFacturas a WHERE fact_id = '$facturaID'; ";
            $consulta3 = mysqli_query($conn, $query3);

            while ($row3 = mysqli_fetch_array($consulta3)) {
                $total = $row3[0];
            }

            // Actualizar el total del Ingreso
            $query4 = "UPDATE t_factura SET fact_pagado = '$total', fact_estado = '$estado' WHERE fact_id = '$facturaID';";
            $consulta2 = mysqli_query($conn, $query4);
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

function validar_abono($valor)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $valor = trim($valor);

    //Validar si no vienene vacios
    if (empty($valor)) {
        $validacioncuerpo .= '<tr><td>Ingrese Valor</td> </tr>';
    }
    return $validacioncuerpo;
}