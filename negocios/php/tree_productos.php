<?php
include "../../datos/conexion_mysql.php";
session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);

if ($_POST["Actividad"] == 'listaproductos') {
    $cod_user = $_SESSION['codusu'];
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY prod_id ASC) AS 'fila', prod_id, prod_codigo, nombre_prod,
        medida_nombre, marca_nombre, format(prod_precioventa,2), format(prod_preciocompra,2), prod_disponible, prod_cantminima
        FROM t_productos a
        left join t_marca b on b.marca_id = a.marca_id 
        left join t_unidadmedida c on c.medida_id = a.medida_id;";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {


        if ($row[8] <= $row[9]) {
            $html .= "<tr class='table-danger'>";
        } else {
            $html .= "<tr>";
        }
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[7] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[6] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[8] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[9] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" 
            data-bs-target="#updProducto" onclick="showmodal_producto(' . $row[1] . ');"><i class="fas fa-file-signature"></i></button></td>';
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'insertar_producto') {
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $marca = $_POST['marca'];
    $medida = $_POST['medida'];
    $precioCompra = $_POST['precioCompra'];
    $cantMinima = $_POST['cantMinima'];
    $precioVenta = $_POST['precioVenta'];
    $tipoImpuesto = $_POST['tipoImpuesto'];
    $comentario = $_POST['comentario'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';

    $validar = validar_producto(
        $producto,
        $marca,
        $medida,
        $precioCompra,
        $cantMinima,
        $precioVenta,
        $tipoImpuesto
    );
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {
        //Limpiar contenido
        $codigo = trim($codigo);
        $producto = trim($producto);
        $precioCompra = trim($precioCompra);
        $cantMinima = trim($cantMinima);
        $precioVenta = trim($precioVenta);
        $comentario = trim($comentario);

        $codigo = filter_var($codigo, FILTER_SANITIZE_STRING);
        $producto = filter_var($producto, FILTER_SANITIZE_STRING);
        $precioCompra = filter_var($precioCompra, FILTER_SANITIZE_STRING);
        $cantMinima = filter_var($cantMinima, FILTER_SANITIZE_STRING);
        $precioVenta = filter_var($precioVenta, FILTER_SANITIZE_STRING);
        $comentario = filter_var($comentario, FILTER_SANITIZE_STRING);

        // GenerarCodigo
        $query = "SELECT Count(*) FROM t_productos;";
        $consulta = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_array($consulta)) {
            $totalproductos = $row[0];
        }

        if ($totalproductos == 0) {
            $contador = 1;
        } else {
            $contador = $totalproductos + 1;
        }

        date_default_timezone_set('America/Tegucigalpa');
        $hoy = date('Y-m-d');

        $codigonuevo = str_replace('-', '', $hoy) . '-' . $contador;


        if (empty($codigo)) {
            $cod = $codigonuevo;
        } else {
            $cod = $codigo;
        }

        $query1 = "INSERT INTO t_productos (prod_codigo, nombre_prod, marca_id, medida_id, prod_preciocompra, prod_cantminima, 
                                            prod_precioventa, prod_tipoisv, prod_comentario, usu_id)
                    VALUES ('$cod', '$producto', '$marca','$medida', '$precioCompra', '$cantMinima', '$precioVenta', '$tipoImpuesto', 
                            '$comentario', '$cod_user' )";
        $consulta2 = mysqli_query($conn, $query1);

        if (!$consulta2) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

function validar_producto(
    $producto,
    $marca,
    $medida,
    $precioCompra,
    $cantMinima,
    $precioVenta,
    $tipoImpuesto
) {
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $producto = trim($producto);
    $precioCompra = trim($precioCompra);
    $cantMinima = trim($cantMinima);
    $precioVenta = trim($precioVenta);


    //Validar si no vienene vacios
    if (empty($producto)) {
        $validacioncuerpo .= '<tr><td>Ingrese el nombre del producto</td> </tr>';
    }
    if ($marca == 99) {
        $validacioncuerpo .= '<tr><td>Seleccione la marca del producto</td> </tr>';
    }
    if ($medida == 99) {
        $validacioncuerpo .= '<tr><td>Seleccione la unidad de medida del producto</td> </tr>';
    }
    if (empty($cantMinima)) {
        $validacioncuerpo .= '<tr><td>Ingrese la cantidad minina de existencia de inventario del producto</td> </tr>';
    }
    if (empty($precioCompra)) {
        $validacioncuerpo .= '<tr><td>Ingrese el precio de compra del producto</td> </tr>';
    }
    if (empty($precioVenta)) {
        $validacioncuerpo .= '<tr><td>Ingrese el precio de venta del producto</td> </tr>';
    }
    if ($tipoImpuesto == 99) {
        $validacioncuerpo .= '<tr><td>Seleccione impuesto</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'modal_producto') {
    $idprod = $_POST['idprod'];

    $query = "SELECT  prod_id, prod_codigo, nombre_prod, marca_id,  medida_id,  prod_preciocompra, 
                      prod_cantminima, prod_precioventa, prod_tipoisv, prod_comentario
              FROM t_productos 
              WHERE prod_id = '$idprod';";

    $consulta = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($consulta)) {
        $showmodal_producto[] = $row[0];
        $showmodal_producto[] = $row[1];
        $showmodal_producto[] = $row[2];
        $showmodal_producto[] = $row[3];
        $showmodal_producto[] = $row[4];
        $showmodal_producto[] = $row[5];
        $showmodal_producto[] = $row[6];
        $showmodal_producto[] = $row[7];
        $showmodal_producto[] = $row[8];
        $showmodal_producto[] = $row[9];
    }
    echo json_encode($showmodal_producto);
}

if ($_POST["Actividad"] == 'update_producto') {

    $idprod = $_POST['idprod'];
    $codigo = $_POST['codigo'];
    $producto = $_POST['producto'];
    $marca = $_POST['marca'];
    $medida = $_POST['medida'];
    $precioCompra = $_POST['precioCompra'];
    $cantMinima = $_POST['cantMinima'];
    $precioVenta = $_POST['precioVenta'];
    $tipoImpuesto = $_POST['tipoImpuesto'];
    $comentario = $_POST['comentario'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';
    $validar = validar_producto(
        $producto,
        $marca,
        $medida,
        $precioCompra,
        $cantMinima,
        $precioVenta,
        $tipoImpuesto
    );
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        //Limpiar contenido
        $codigo = trim($codigo);
        $producto = trim($producto);
        $precioCompra = trim($precioCompra);
        $cantMinima = trim($cantMinima);
        $precioVenta = trim($precioVenta);
        $comentario = trim($comentario);

        $codigo = filter_var($codigo, FILTER_SANITIZE_STRING);
        $producto = filter_var($producto, FILTER_SANITIZE_STRING);
        $precioCompra = filter_var($precioCompra, FILTER_SANITIZE_STRING);
        $cantMinima = filter_var($cantMinima, FILTER_SANITIZE_STRING);
        $precioVenta = filter_var($precioVenta, FILTER_SANITIZE_STRING);
        $comentario = filter_var($comentario, FILTER_SANITIZE_STRING);

        $query1 = "UPDATE t_productos SET nombre_prod = '$producto', marca_id = '$marca', medida_id = '$medida', prod_preciocompra = '$precioCompra', 
                          prod_cantminima = '$cantMinima', prod_precioventa = '$precioVenta', prod_tipoisv = '$tipoImpuesto', 
                          prod_comentario = '$comentario', prod_codigo = '$codigo'   
            WHERE prod_id = '$idprod'; ";

        $consulta2 = mysqli_query($conn, $query1);

        if (!$consulta2) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

if ($_POST["Actividad"] == 'cargarProductos') {
    $query = " SELECT a.prod_id, concat(a.nombre_prod,' ( ',b.medida_nombre, ' )') as Producto, a.prod_codigo 
                FROM t_productos a INNER JOIN t_unidadmedida b on a.medida_id = b.medida_id; ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
    }
}

// Cierre de conexion 
mysqli_close($conn);
