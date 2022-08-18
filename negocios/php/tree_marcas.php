<?php
include "../../datos/conexion_mysql.php";
session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);

if ($_POST["Actividad"] == 'lst_marcas') {
    $cod_user = $_SESSION['codusu'];
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY marca_nombre ASC) AS 'fila', marca_id, marca_nombre FROM t_marca ORDER BY marca_nombre;";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {

        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= '<td align="center">
                    <button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" data-bs-target="#updMarca" onclick="showmodal_marca(' . $row[1] . ');">
                        <i class="fas fa-file-signature"></i>
                    </button>
                 </td>';
        $html .= "</tr>";
    }
    echo $html;
}


if ($_POST["Actividad"] == 'insertar_marca') {
    $nombre = $_POST['nombre'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_marca($nombre);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $nombre = trim($nombre);

        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);

        // Insertar 
        $query1 = "INSERT INTO t_marca (marca_nombre, usucodigo)
                   VALUES ('$nombre','$cod_user'); ";

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

function validar_marca($nombre)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $nombre = trim($nombre);

    //Validar si no vienene vacios
    if (empty($nombre)) {
        $validacioncuerpo .= '<tr><td>Ingrese Nombre</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'showmodal_marca') {
    $idMarca = $_POST['idMarca'];

    $query = "SELECT * FROM t_marca WHERE marca_id = '$idMarca'; ";

    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_marca[] = $row1[0];
        $showmodal_marca[] = $row1[1];
    }
    echo json_encode($showmodal_marca);
}

if ($_POST["Actividad"] == 'update_marca') {
    $nombre = $_POST['nombre'];
    $idMarca = $_POST['idMarca'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_marca($nombre);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $nombre = trim($nombre);

        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);

        // Insertar 
        $query1 = "UPDATE t_marca SET marca_nombre = '$nombre' WHERE marca_id = '$idMarca'; ";

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


if ($_POST["Actividad"] == 'cargarMarcas') {
    $query = "SELECT marca_id, marca_nombre FROM t_marca;";
    $result = mysqli_query($conn, $query);
    echo "<option value='0'>SELECCIONAR</option>";
    while ($row = mysqli_fetch_array($result)) {
        echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
    }
}

if ($_POST["Actividad"] == 'obtenerMarca') {
    $idMarca = $_POST['idMarca'];

    $query = "SELECT marca_nombre FROM t_marca WHERE marca_id = '$idMarca'; ";

    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_marca[] = $row1[0];
    }
    echo json_encode($showmodal_marca);
}


// Cierre de conexion 
mysqli_close($conn);
