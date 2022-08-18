<?php
include "../../datos/conexion_mysql.php";

session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);


if ($_POST["Actividad"] == 'lst_cotizacionescreadas') {
    $cod_user = $_SESSION['codusu'];
    $tipocotizacion = $_POST['tipocot'];

    $query = "SELECT ROW_NUMBER() OVER(ORDER BY a.cot_id ASC) AS 'fila', a.cot_id, DATE_FORMAT(a.cot_fecha, '%Y-%m-%d') AS Fecha, b.cli_nombre, 
                         b.cli_rtn, b.cli_telefonos, b.cli_contacto, b.cli_email, a.cot_estado, a.cot_codigo
                  FROM t_cotizacion a INNER JOIN t_clientes b ON a.cli_id = b.cli_id
                  WHERE a.cot_estado = '$tipocotizacion';";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[9] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="actualizar_cotizacion(' . $row[1] . ');">
                <i class="fas fa-file-signature"></i></button></td>';
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="reporte_cotizacion(' . $row[1] . ');">
        <i class="fas fa-print"></i></button></td>';

        if ($tipocotizacion == 0) {
            $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="estado_cotizacion(' . $row[1] . ', 1, ' . $tipocotizacion . ');">
            <i class="far fa-eye"></i></button></td>';
        }

        if ($tipocotizacion == 1) {
            $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="estado_cotizacion(' . $row[1] . ',0,' . $tipocotizacion . ');">
            <i class="fas fa-undo-alt"></i></td>';
            $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" onclick="estado_cotizacion(' . $row[1] . ',2,' . $tipocotizacion . ');">
            <i class="far fa-check-circle"></i></button></td>';
        }
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'cargarclientes') {
    $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 0;";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
    }
}

if ($_POST["Actividad"] == 'insertar_cotizacion') {

    $cliente = $_POST['cliente'];
    $cotfecha = $_POST['cotfecha'];
    $cod_user = $_SESSION['codusu'];

    $proceso = $_POST['proceso'];
    $fechaapertura = $_POST['fechaapertura'];
    $validez = $_POST['validez'];
    $entrega = $_POST['entrega'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_cotizacion($cliente);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $cliente = trim($cliente);

        // GenerarCodigo
        $query = "SELECT Count(*) FROM t_cotizacion WHERE DATE_FORMAT(cot_fecha, '%Y-%m-%d') = '$cotfecha';";
        $consulta = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($consulta)) {
            $totalcot = $row[0];
        }
        if ($totalcot == 0) {
            $contador = 1;
        } else {
            $contador = $totalcot + 1;
        }
        // $codigo = str_replace('-', '', $cotfecha) . '-' . $contador;
        $codigo = "COT" . '-' . $contador;

        // Query para insertar
        $query1 = " INSERT INTO t_cotizacion ( cot_codigo, cli_id, cot_fecha, cot_estado, usu_id, usu_fechacreacion,
                                               cot_proceso, cot_fechaapertura, cot_validez, cot_entrega) 
                    VALUES('$codigo', '$cliente', '$cotfecha', 0, '$cod_user', NOW(), '$proceso', 
                    " . ($fechaapertura == NULL ? "NULL" : "'$fechaapertura'") . " ,
                    " . ($validez == NULL ? "NULL" : "'$validez'") . " ,
                    " . ($entrega == NULL ? "NULL" : "'$entrega'") . " ); ";

        $consulta2 = mysqli_query($conn, $query1);

        // Obtener ultimo id 
        $query3 = " select last_insert_id() t_cotizacion;";
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
    $result = array("validacion" => $validacion, "mensaje" => $mensaje, "cot_id" => $lastID, "cot_codigo" => $codigo);
    echo json_encode($result);
}

function validar_cotizacion($cliente)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $cliente = trim($cliente);

    //Validar si no vienene vacios
    if (empty($cliente)) {
        $validacioncuerpo .= '<tr><td>Seleccione Cliente</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'instertar_detcotizacion') {

    $producto = $_POST['producto'];
    $cantidad = $_POST['cantidad'];
    $cotid = $_POST['cotid'];
    $cod_user = $_SESSION['codusu'];
    $precio = str_replace(',', '', $_POST['precio']);

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = valida_det($producto);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $producto = trim($producto);
        $cantidad = trim($cantidad);

        $query = " INSERT INTO t_cotizaciondet (cot_id, prod_id, cotd_cantidad, usu_id, usu_fechacreacion, prod_precio) 
                   VALUES ('$cotid', '$producto', '$cantidad', '$cod_user', NOW(), '$precio'); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje, "cot_id" => $lastID, "cot_codigo" => $codigo);
    echo json_encode($result);
}

function valida_det($producto)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $producto = trim($producto);

    //Validar si no vienene vacios
    if (empty($producto)) {
        $validacioncuerpo .= '<tr><td>Seleccione Producto</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'buscarPrecio') {

    $producto = $_POST['producto'];

    $query = "SELECT format(prod_precioventa,2) FROM t_productos WHERE prod_id = '$producto';";
    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_precio[] = $row1[0];
    }
    echo json_encode($showmodal_precio);
}

if ($_POST["Actividad"] == 'showmodal_cotizacion') {

    $cot_id = $_POST['cot_id'];
    $query = "SELECT cot_id, cot_codigo, DATE_FORMAT(cot_fecha, '%Y-%m-%d'), cli_id, cot_proceso, DATE_FORMAT(cot_fechaapertura, '%Y-%m-%d %h:%i') , cot_validez, cot_entrega
              FROM t_cotizacion WHERE cot_id = '$cot_id';";
    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_cotizacion[] = $row1[0];
        $showmodal_cotizacion[] = $row1[1];
        $showmodal_cotizacion[] = $row1[2];
        $showmodal_cotizacion[] = $row1[3];
        $showmodal_cotizacion[] = $row1[4];
        $showmodal_cotizacion[] = $row1[5];
        $showmodal_cotizacion[] = $row1[6];
        $showmodal_cotizacion[] = $row1[7];
    }
    echo json_encode($showmodal_cotizacion);
}

if ($_POST["Actividad"] == 'lst_cotizaciondetalle1') {

    $cod_user = $_SESSION['codusu'];
    $cotid = $_POST['cot_id'];

    $query = " SELECT a.cotd_id, a.prod_id, b.nombre_prod, a.cotd_cantidad, 
                    CASE WHEN b.prod_tipoisv = 0 THEN 0 else b.prod_tipoisv END AS prod_tipoisv, 
                    FORMAT((a.cotd_cantidad * a.prod_precio),2) AS SubTotal, 
                    CASE WHEN b.prod_tipoisv = 15 THEN FORMAT((15/100)*(a.cotd_cantidad*a.prod_precio),2) 
                        WHEN b.prod_tipoisv = 18 THEN FORMAT((18/100)*(a.cotd_cantidad*a.prod_precio),2) 
                        ELSE 0 END AS ISV, 
                    CASE WHEN b.prod_tipoisv = 15 THEN FORMAT((a.cotd_cantidad*a.prod_precio) + ((15/100)*(a.cotd_cantidad*a.prod_precio)),2) 
                        WHEN b.prod_tipoisv = 18 THEN FORMAT((a.cotd_cantidad*a.prod_precio) + ((18/100)*(a.cotd_cantidad*a.prod_precio)),2) 
                        ELSE FORMAT ((a.cotd_cantidad*a.prod_precio),2) END AS Total, a.cot_id, FORMAT(a.prod_precio,2)                    
                FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                WHERE cot_id = '$cotid'; ";
    $result = mysqli_query($conn, $query);
    $html = "";

    $html .= ' <tbody style="font-size: 15px;"> ';
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        // Cantidad
        $html .= "<td class='align-middle' align='center' id1='" . $row[0] . ":" . $row[0] . "' contenteditable='true' tabIndex='-1'>" . $row[3] . "</td>";
        // Precio        
        $html .= "<td class='align-middle' align='center' id2='" . $row[0] . ":" . $row[0] . "' contenteditable='true' tabIndex='-1'>" . $row[9] . "</td>";
        // $html .= "<td class='align-middle' align='center'>" . $row[9] . "</td>";

        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[6] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" 
                                             onclick="eliminar_lineadetallecot1(' . $row[8] . ',' . $row[0] . ');"><i class="fas fa-trash-alt"></i></button></td>';
        $html .= "</tr>";
    }
    $html .= ' </tbody> ';

    // Sacar el total y ponerlo en el footer 
    $query1 = " SELECT format(SUM((a.cotd_cantidad * a.prod_precio) + ((b.prod_tipoisv/100)*(a.cotd_cantidad * a.prod_precio))),2) as Total
                FROM t_cotizaciondet a  INNER JOIN t_productos b ON a.prod_id = b.prod_id
                WHERE cot_id = '$cotid'; ";
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

// Actualizar Cantidad en Detalle de Cotizacion
if ($_POST["Actividad"] == 'update_cantidadCotizacionDetalle') {
    $dato = $_POST['dato'];

    // Dividir dato enviado y obtener campos que se necesitan. 
    $split_data = explode(':', $dato);

    $cotizacionD = $split_data[0];
    $cantidad = $split_data[2];

    $validacion = ' <div class="table-responsive">
             <table class="table text-center">
             <thead>
             <tr>
             </tr>
             </thead>
             <tbody>';

    $validar = validar_cantidad($cantidad);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {
        //Limpiar contenido
        $cantidad = trim($cantidad);
        if ($cantidad != filter_var($cantidad, FILTER_SANITIZE_NUMBER_INT)) {
            $validacion .= $validacion . '<tr><td>Error: Debe ingresar solo numeros.</td> </tr>';
        } else {

            $query = "UPDATE t_cotizaciondet SET cotd_cantidad = '$cantidad' WHERE cotd_id = '$cotizacionD';";

            $consulta = mysqli_query($conn, $query);
            if (!$consulta) {
                $mensaje = "error";
            } else {
                $mensaje = "exito";
            }
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

function validar_cantidad($cantidad)
{
    // Quitar espacios en blanco
    $cantidad = trim($cantidad);
    $validacioncuerpo = '';

    //Validar si no vienene vacios
    if ($cantidad = '') {
        $validacioncuerpo .= '<tr><td>Error: Ingrese Cantidad</td> </tr>';
    }
    return $validacioncuerpo;
}

// Actualizar precio en Detalle de Cotizacion
if ($_POST["Actividad"] == 'update_precioCotizacionDetalle') {
    $dato = $_POST['dato'];

    // Dividir dato enviado y obtener campos que se necesitan. 
    $split_data = explode(':', $dato);

    $cotizacionD = $split_data[0];
    $precio = str_replace(',', '', $split_data[2]);

    $validacion = ' <div class="table-responsive">
             <table class="table text-center">
             <thead>
             <tr>
             </tr>
             </thead>
             <tbody>';

    $validar = validar_precio($precio);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {
        //Limpiar contenido
        $precio = trim($precio);
        // if ($campo_valor != filter_var($campo_valor, FILTER_SANITIZE_NUMBER_INT)) {
        //     $validacion .= $validacion . '<tr><td>Error: Debe ingresar solo numeros.</td> </tr>';
        // } else {

        $query = "UPDATE t_cotizaciondet SET  prod_precio = '$precio' WHERE cotd_id = '$cotizacionD';";

        $consulta = mysqli_query($conn, $query);
        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
        // }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

function validar_precio($precio)
{
    // Quitar espacios en blanco
    $canpreciotidad = trim($precio);
    $validacioncuerpo = '';

    //Validar si no vienene vacios
    if ($precio = '') {
        $validacioncuerpo .= '<tr><td>Error: Ingrese Precio</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'actualizarEstadoCot') {

    $cot_id = $_POST['cot_id'];
    $cot_estado = $_POST['cot_estado'];

    $query = "UPDATE t_cotizacion SET cot_estado = '$cot_estado' WHERE cot_id = '$cot_id';";

    $consulta = mysqli_query($conn, $query);
    if (!$consulta) {
        $mensaje = "error";
    } else {
        $mensaje = "exito";
    }
    echo json_encode($mensaje);
}

// Cierre de conexion 
mysqli_close($conn);
