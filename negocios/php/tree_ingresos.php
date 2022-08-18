<?php
include "../../datos/conexion_mysql.php";

session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);

if ($_POST["Actividad"] == 'ObtenerPrecio') {
    $producto = $_POST['producto'];

    $query = "SELECT prod_preciocompra FROM t_productos WHERE prod_id = '$producto';";
    $consulta1 = mysqli_query($conn, $query);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_ingreso[] = $row1[0];
    }
    echo json_encode($showmodal_ingreso);
}

if ($_POST["Actividad"] == 'lst_ingresos') {
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY a.ingre_id ASC) AS 'fila', a.ingre_id,  a.ingre_codigo, 
                     DATE_FORMAT(a.ingre_fecha, '%Y-%m-%d') Fecha, b.cli_nombre, CASE WHEN ingre_tipo = 1 THEN 'CONTADO' ELSE 'CREDITO' END,
                     format(ingre_total,2) as Total, 
                     CASE WHEN ingre_tipo = 1 THEN format(ingre_total,2) ELSE format(ingre_pagado,2) END as pagado,  
                     CASE WHEN ingre_tipo = 1 THEN format(0,2) ELSE format(ingre_total-ingre_pagado,2)  END as Pendiente,
                     CASE WHEN ingre_estado = 1 THEN 'PENDIENTE' ELSE 'CANCELADO' END AS Estado, ingre_estado, ingre_tipo
              FROM t_ingresos a INNER JOIN t_clientes b ON a.cli_id = b.cli_id; ";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {
        if ($row[10] == 2) {
            $html .= "<tr>";
        } else {
            $html .= "<tr  class='table-danger'>";
        }
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[6] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[8] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[9] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="actualizar_ingreso(' . $row[1] . ');">
                                             <i class="fas fa-file-signature"></i>
                                    </button></td>';
        if ($row[10] == 2 && $row[11] == 1) {
            $html .= "<td class='align-middle' align='center'>--</td>";
        } else {
            $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="abonosIngresos(' . $row[1] . ');">
                                                <i class="fas fa-comment-dollar"></i>
                                        </button></td>';
        }
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'insertar_ingreso') {

    $proveedor = $_POST['proveedor'];
    $ingresofecha = $_POST['ingresofecha'];
    $codigoingreso = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cod_user = $_SESSION['codusu'];


    if ($tipo == 1) {
        $estado = 2;
    } else {
        $estado = 1;
    }

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validad_ingreso($proveedor);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $cliente = trim($cliente);

        // GenerarCodigo
        $query = "SELECT Count(*) FROM t_ingresos WHERE DATE_FORMAT(ingre_fecha, '%Y-%m-%d') = '$ingresofecha';";
        $consulta = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($consulta)) {
            $totalcot = $row[0];
        }

        if ($totalcot == 0) {
            $contador = 1;
        } else {
            $contador = $totalcot + 1;
        }

        $codigo = str_replace('-', '', $ingresofecha) . '-' . $contador;

        if ($tipo == 1) {
            $estado = 2;
        } else {
            $estado = 1;
        }

        if (empty($codigoingreso)) {
            $query1 = " INSERT INTO t_ingresos ( ingre_codigo, cli_id, ingre_fecha, ingre_tipo, ingre_estado, usu_id, usu_fechacreacion) 
                        VALUES('$codigo', '$proveedor', '$ingresofecha', '$tipo', '$estado', '$cod_user', NOW()); ";
            $codvolver = $codigo;
        } else {
            $query1 = " INSERT INTO t_ingresos ( ingre_codigo, cli_id, ingre_fecha, ingre_tipo, ingre_estado, usu_id, usu_fechacreacion) 
                        VALUES('$codigoingreso', '$proveedor', '$ingresofecha', '$tipo', '$estado', '$cod_user', NOW()); ";
            $codvolver = $codigoingreso;
        }
        $consulta2 = mysqli_query($conn, $query1);

        // Obtener ultimo id 
        $query3 = " select last_insert_id() t_ingresos;";
        $consulta3 = mysqli_query($conn, $query3);
        while ($row = mysqli_fetch_array($consulta3)) {
            $lastID = $row[0];
        }

        if (!$consulta2) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje, "cot_id" => $lastID, "cot_codigo" => $codvolver);
    echo json_encode($result);
}

function validad_ingreso($proveedor)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $proveedor = trim($proveedor);

    //Validar si no vienene vacios
    if (empty($proveedor)) {
        $validacioncuerpo .= '<tr><td>Seleccione Proveedor</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'instertar_detingreso') {

    $producto = $_POST['producto'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $ingresoid = $_POST['ingresoid'];
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

        $query = " INSERT INTO t_ingresosdetalle ( ingre_id, prod_id, ingred_precio, ingred_cantidad, usu_id) 
                   VALUES ('$ingresoid', '$producto', '$precio', '$cantidad','$cod_user'); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
            // Sumar producto al disponible
            $query2 = "UPDATE t_productos SET prod_disponible = prod_disponible + '$cantidad' WHERE prod_id = '$producto';";
            $consulta2 = mysqli_query($conn, $query2);

            // Sacar el total del Ingreso            
            $query3 = "SELECT coalesce(SUM(a.ingred_cantidad * a.ingred_precio),0)
                        FROM t_ingresosdetalle a WHERE ingre_id = '$ingresoid'; ";
            $consulta3 = mysqli_query($conn, $query3);

            while ($row3 = mysqli_fetch_array($consulta3)) {
                $total = $row3[0];
            }

            // Actualizar el total del Ingreso
            $query4 = "UPDATE t_ingresos SET ingre_total = '$total' WHERE ingre_id = '$ingresoid';";
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

if ($_POST["Actividad"] == 'lst_ingresodetalle') {

    $cod_user = $_SESSION['codusu'];
    $ingreso_id = $_POST['ingreso_id'];

    $query = " SELECT a.ingred_id, a.prod_id, concat(b.nombre_prod,' ( ',c.medida_nombre, ' )') as Producto, 
                      a.ingred_cantidad, format(a.ingred_precio,2) AS Precio, 
                      format((a.ingred_cantidad *  a.ingred_precio),2) AS SubTotal, a.ingre_id, a.prod_id
                FROM t_ingresosdetalle a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                                          INNER JOIN t_unidadmedida c on b.medida_id = c.medida_id
                WHERE ingre_id = '$ingreso_id'; ";
    $result = mysqli_query($conn, $query);
    $html = "";

    $html .= ' <tbody style="font-size: 13px;"> ';
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" 
                                             onclick="eliminar_lineadetalleingreso(' . $row[0] . ',' . $row[1] . ',' . $row[3] . ');">
                                             <i class="fas fa-trash-alt"></i>
                                    </button></td>';
        $html .= "</tr>";
    }
    $html .= ' </tbody> ';

    // Sacar el total y ponerlo en el footer 
    $query1 = "SELECT format(SUM((a.ingred_cantidad * a.ingred_precio)),2) AS Total
                FROM t_ingresosdetalle a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                WHERE ingre_id = '$ingreso_id'; ";
    $consulta1 = mysqli_query($conn, $query1);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $total = $row1[0];
    }

    if ($total != '') {
        $html .= ' <tfoot> ';
        $html .= ' 	<tr> ';
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
        $html .= ' 		<th style="text-align: center">Total </th> ';
        $html .= ' 		<th style="text-align: center"> 0.00 </th> ';
        $html .= ' 		<th style="text-align: center"> </th> ';
        $html .= ' 	</tr> ';
        $html .= ' </tfoot>';
    }
    // echo $html;
    echo json_encode($html);
}

if ($_POST["Actividad"] == 'eliminar_lineadetalleingreso') {

    $ingresoiddet = $_POST['ingresodet'];
    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $ingresoid = $_POST['ingresoid'];

    $query = "DELETE FROM t_ingresosdetalle WHERE ingred_id = '$ingresoiddet';";

    $consulta = mysqli_query($conn, $query);
    if (!$consulta) {
        $mensaje = "error";
    } else {
        $mensaje = "exito";
        // Sumar producto al disponible
        $query2 = "UPDATE t_productos SET prod_disponible = prod_disponible - '$cantidad' WHERE prod_id = '$producto';";
        $consulta2 = mysqli_query($conn, $query2);

        // Sacar el total del Ingreso            
        $query3 = "SELECT coalesce(SUM(a.ingred_cantidad * a.ingred_precio),0)
                    FROM t_ingresosdetalle a WHERE ingre_id = '$ingresoid'; ";
        $consulta3 = mysqli_query($conn, $query3);

        while ($row3 = mysqli_fetch_array($consulta3)) {
            $total = $row3[0];
        }

        // Actualizar el total del Ingreso
        $query4 = "UPDATE t_ingresos SET ingre_total = '$total' WHERE ingre_id = '$ingresoid';";
        $consulta2 = mysqli_query($conn, $query4);
    }
    echo json_encode($mensaje);
}

if ($_POST["Actividad"] == 'showmodal_ingreso') {

    $ingreso = $_POST['ingreso'];

    $query = "SELECT ingre_id, ingre_codigo, DATE_FORMAT(ingre_fecha, '%Y-%m-%d'), cli_id, ingre_tipo FROM t_ingresos WHERE ingre_id = '$ingreso';";

    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_ingreso[] = $row1[0];
        $showmodal_ingreso[] = $row1[1];
        $showmodal_ingreso[] = $row1[2];
        $showmodal_ingreso[] = $row1[3];
        $showmodal_ingreso[] = $row1[4];
    }
    echo json_encode($showmodal_ingreso);
}

if ($_POST["Actividad"] == 'fcn_datosgenerales') {
    $ingresoID = $_POST['ingresoID'];

    $query = "SELECT a.ingre_id, DATE_FORMAT(a.ingre_fecha, '%Y-%m-%d') AS Fecha, a.ingre_codigo,
                    b.cli_nombre, CASE WHEN ingre_tipo = 1 THEN 'CONTADO' ELSE 'CREDITO' END AS TipoIngreso,
                    FORMAT(ingre_total,2) AS Total, FORMAT(ingre_pagado,2) AS Pagado, FORMAT(ingre_total-ingre_pagado,2) AS Pendiente,
                    ingre_total-ingre_pagado
                FROM t_ingresos a INNER JOIN t_clientes b ON a.cli_id = b.cli_id 
                WHERE a.ingre_id = '$ingresoID';";
    $consulta1 = mysqli_query($conn, $query);

    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_ingreso[] = $row1[0];
        $showmodal_ingreso[] = $row1[1];
        $showmodal_ingreso[] = $row1[2];
        $showmodal_ingreso[] = $row1[3];
        $showmodal_ingreso[] = $row1[4];
        $showmodal_ingreso[] = $row1[5];
        $showmodal_ingreso[] = $row1[6];
        $showmodal_ingreso[] = $row1[7];
        $showmodal_ingreso[] = $row1[8];
    }
    echo json_encode($showmodal_ingreso);
}

if ($_POST["Actividad"] == 'insertar_pago') {
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor'];
    $ingresoID = $_POST['ingresoID'];
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
        $query = " INSERT INTO t_pagosIngresos (ingre_id, pagin_fecha, pagin_tipo, pagin_recibo, pagin_valor, usu_id)
                       VALUES ( '$ingresoID', '$fecha', '$tipo', '$recibo', '$valor', '$cod_user' ); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";

            // Sacar el total del Ingreso            
            $query3 = "SELECT coalesce(SUM(pagin_valor),0) FROM t_pagosIngresos a WHERE ingre_id = '$ingresoID'; ";
            $consulta3 = mysqli_query($conn, $query3);

            while ($row3 = mysqli_fetch_array($consulta3)) {
                $total = $row3[0];
            }

            // Actualizar el total del Ingreso
            $query4 = "UPDATE t_ingresos SET ingre_pagado = '$total', ingre_estado = '$estado' WHERE ingre_id = '$ingresoID';";
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

if ($_POST["Actividad"] == 'buscar_pagosIngreso') {
    $ingresoID = $_POST['ingresoID'];

    $query = " SELECT pagin_id, ingre_id, DATE_FORMAT(pagin_fecha, '%Y-%m-%d') fecha, 
                      CASE WHEN pagin_tipo = 1 then 'EFECTIVO' WHEN pagin_tipo = 2 THEN 'TARJETA' else 'TRANSFERENCIA' end AS TIPO, 
                      pagin_recibo,
                      format(pagin_valor,2) AS Valor 
                    FROM t_pagosIngresos WHERE ingre_id = '$ingresoID';";
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
    $query1 = "SELECT SUM(pagin_valor) FROM t_pagosIngresos WHERE ingre_id = '$ingresoID';";
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

// Cierre de conexion 
mysqli_close($conn);
