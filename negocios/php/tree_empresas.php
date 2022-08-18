<?php
include "../../datos/conexion_mysql.php";

session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);


if ($_POST["Actividad"] == 'lst_empresas') {
    $cod_user = $_SESSION['codusu'];
    $query = "SELECT ROW_NUMBER() OVER(ORDER BY emp_id ASC) AS 'fila', emp_id, emp_nombre, emp_rtn, empre_telefonos, emp_email
              FROM t_empresas; ";

    $result = mysqli_query($conn, $query);
    $html = "";
    while ($row = mysqli_fetch_array($result)) {
        $html .= "<tr>";
        $html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
        $html .= "<td class='align-middle' align='center'>" . $row[5] . "</td>";
        $html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" data-bs-target="#updEmpresa" 
                        onclick="showmodal_empresa(' . $row[1] . ');"><i class="fas fa-file-signature"></i></button></td>';
        $html .= "</tr>";
    }
    echo $html;
}

if ($_POST["Actividad"] == 'insertar_empresa') {
    $nombre = $_POST['nombre'];
    $rtn = $_POST['rtn'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $web = $_POST['web'];
    $direccion = $_POST['direccion'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_cliente($nombre, $rtn, $telefono);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $nombre = trim($nombre);
        $rtn = trim($rtn);
        $email = trim($email);
        $contacto = trim($contacto);
        $telefono = trim($telefono);
        $direccion = trim($direccion);

        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $contacto = filter_var($contacto, FILTER_SANITIZE_STRING);
        $direccion = filter_var($direccion, FILTER_SANITIZE_STRING);


        // Insertar 

        $query = " INSERT INTO t_empresas (emp_nombre, emp_rtn, empre_telefonos, emp_email, emp_web, emp_direccion, usu_codigo)
                   VALUES ( '$nombre','$rtn','$telefono','$email', '$web', '$direccion','$cod_user' ); ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

function validar_cliente($nombre, $rtn, $telefono)
{
    // Quitar espacios en blanco
    $validacioncuerpo = '';
    $nombre = trim($nombre);
    $rtn = trim($rtn);
    $telefono = trim($telefono);

    //Validar si no vienene vacios
    if (empty($nombre)) {
        $validacioncuerpo .= '<tr><td>Ingrese Nombre</td> </tr>';
    }
    if (empty($rtn)) {
        $validacioncuerpo .= '<tr><td>Ingrese RTN</td> </tr>';
    }
    if (empty($telefono)) {
        $validacioncuerpo .= '<tr><td>Ingrese Telefono</td> </tr>';
    }

    return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'showmodal_empresa') {
    $idEmpresa = $_POST['idEmpresa'];

    $query = "SELECT emp_id, emp_nombre, emp_rtn, empre_telefonos, emp_email, emp_web, emp_direccion 
              FROM t_empresas 
              WHERE emp_id = '$idEmpresa'; ";

    $consulta1 = mysqli_query($conn, $query);
    while ($row1 = mysqli_fetch_array($consulta1)) {
        $showmodal_empresas[] = $row1[0];
        $showmodal_empresas[] = $row1[1];
        $showmodal_empresas[] = $row1[2];
        $showmodal_empresas[] = $row1[3];
        $showmodal_empresas[] = $row1[4];
        $showmodal_empresas[] = $row1[5];
        $showmodal_empresas[] = $row1[6];
    }
    echo json_encode($showmodal_empresas);
}

if ($_POST["Actividad"] == 'update_empresa') {
    $nombre = $_POST['nombre'];
    $rtn = $_POST['rtn'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $web = $_POST['web'];
    $direccion = $_POST['direccion'];
    $idEmpresa = $_POST['idEmpresa'];
    $cod_user = $_SESSION['codusu'];

    $validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

    $validar = validar_cliente($nombre, $rtn, $telefono);
    if ($validar  != '') {
        $validacion .= $validacion . $validar;
    } else {

        //Limpiar contenido
        $nombre = trim($nombre);
        $rtn = trim($rtn);
        $email = trim($email);
        $contacto = trim($contacto);
        $telefono = trim($telefono);
        $direccion = trim($direccion);

        $nombre = filter_var($nombre, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $contacto = filter_var($contacto, FILTER_SANITIZE_STRING);
        $direccion = filter_var($direccion, FILTER_SANITIZE_STRING);


        // Insertar 

        $query = " UPDATE t_empresas SET emp_nombre = '$nombre', emp_rtn = '$rtn', empre_telefonos = '$telefono', emp_email = '$email',
                                         emp_web = '$web', emp_direccion = '$direccion' WHERE emp_id = '$idEmpresa'; ";
        $consulta = mysqli_query($conn, $query);

        if (!$consulta) {
            $mensaje = "error";
        } else {
            $mensaje = "exito";
        }
    }

    $validacion .= '</tbody> </table> </div>';
    $result = array("validacion" => $validacion, "mensaje" => $mensaje);
    echo json_encode($result);
}

// Cierre de conexion 
mysqli_close($conn);
