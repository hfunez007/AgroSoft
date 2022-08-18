<?php
include "../../datos/conexion_mysql.php";
session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);

if ($_POST["Actividad"] == 'listamedidas') {
    $cod_user = $_SESSION['codusu'];
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY medida_id ASC) AS 'fila', medida_id, medida_nombre 
                  FROM t_unidadmedida;";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" 
                                               data-bs-target="#updMedida" onclick="showmodal_medida(' . $row[1] . ');"><i class="fas fa-file-signature"></i></button></td>';
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'insertar_medida') {
    $nom_medida = $_POST['nom_medida'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';

    $validar = validar_medida($nom_medida);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {
        //Limpiar contenido
        $nom_medida = trim($nom_medida);
        $codigom = trim($codigom);

        $nom_medida = filter_var($nom_medida, FILTER_SANITIZE_STRING);
        //$codigom = filter_var($codigom, FILTER_SANITIZE_NUMBER_INT);

        $query1 = "INSERT INTO t_unidadmedida ( medida_nombre, usu_id) VALUES ('$nom_medida', '$cod_user')";
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

function validar_medida($nombrem)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $nombrem = trim($nombrem);
    //Validar si no vienene vacios
    if (empty($nombrem)) {
        $validacioncuerpo .= '<tr><td>Ingrese la unidad de Medida</td> </tr>';
    }
    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'modal_medida') {
    $idmedida = $_POST['idmedida'];

    $query = "SELECT * FROM t_unidadmedida WHERE medida_id = '$idmedida';";

    $consulta = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($consulta)) {
        $showmodal_medidas[] = $row[0];
        $showmodal_medidas[] = $row[1];
    }
    echo json_encode($showmodal_medidas);
}

if ($_POST["Actividad"] == 'update_medida') {
    $id_medida = $_POST['id_medida'];
    $nom_medida = $_POST['nom_medida'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';

    $validar = validar_medida($nom_medida);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {
        //Limpiar contenido
        $nom_medida = trim($nom_medida);
        $codigom = trim($codigom);

        $nom_medida = filter_var($nom_medida, FILTER_SANITIZE_STRING);


        $query1 = "UPDATE t_unidadmedida SET medida_nombre = '$nom_medida' WHERE medida_id = '$id_medida'; ";
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

if ($_POST["Actividad"] == 'cargarMedidas') {
    $query = "SELECT * FROM t_unidadmedida;";
    $result = mysqli_query($conn, $query);
    echo "<option value='0'>SELECCIONAR</option>";
    while ($row = mysqli_fetch_array($result)) {
        echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
    }
}

// Cierre de conexion 
mysqli_close($conn);
