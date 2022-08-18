<?php
include "../../datos/conexion_mysql.php";

session_start();

// escribir query
// $fp = fopen('selecthist.txt', 'w');
//  	  fwrite($fp, $query);	 
//       fclose($fp);


if ($_POST["Actividad"] == 'lst_clientes') {
	$cod_user = $_SESSION['codusu'];
	$query = "SELECT ROW_NUMBER() OVER(ORDER BY cli_id ASC) AS 'fila', cli_id, cli_nombre, cli_rtn, 
                         cli_telefonos, cli_email, cli_contacto, 
						 CASE WHEN cli_tipo = 1 THEN 'CLIENTE' WHEN cli_tipo = 2 THEN 'PROVEEDOR' ELSE 'EMPLEADO' END AS Tipo,
						 cli_direccion
                  FROM t_clientes; ";

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
		$html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" data-bs-target="#updCliente" 
                        onclick="showmodal_cliente(' . $row[1] . ');"><i class="fas fa-file-signature"></i></button></td>';
		$html .= "</tr>";
	}
	echo $html;
}

if ($_POST["Actividad"] == 'insertar_cliente') {
	$nombre = $_POST['nombre'];
	$rtn = $_POST['rtn'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$contacto = $_POST['contacto'];
	$tipo = $_POST['tipo'];
	$direccion = $_POST['direccion'];
	$cod_user = $_SESSION['codusu'];

	$validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

	$validar = validar_cliente($nombre, $telefono);
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

		$query1 = "INSERT INTO t_clientes (cli_nombre, cli_rtn, cli_telefonos, cli_email, cli_contacto, cli_tipo, cli_direccion, usu_id, usu_fechacreacion)
                       VALUES ('$nombre','$rtn','$telefono','$email','$contacto','$tipo','$direccion','$cod_user', NOW()); ";

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

function validar_cliente($nombre, $telefono)
{
	// Quitar espacios en blanco
	$validacioncuerpo = '';
	$nombre = trim($nombre);
	$telefono = trim($telefono);

	//Validar si no vienene vacios
	if (empty($nombre)) {
		$validacioncuerpo .= '<tr><td>Ingrese Nombre</td> </tr>';
	}
	if (empty($telefono)) {
		$validacioncuerpo .= '<tr><td>Ingrese Telefono</td> </tr>';
	}

	return $validacioncuerpo;
}

if ($_POST["Actividad"] == 'showmodal_cliente') {
	$idcliente = $_POST['idcliente'];

	$query = "SELECT cli_id, cli_nombre, cli_rtn, cli_telefonos, cli_email, cli_contacto, cli_tipo, cli_direccion 
                  FROM t_clientes WHERE cli_id = '$idcliente'; ";

	$consulta1 = mysqli_query($conn, $query);
	while ($row1 = mysqli_fetch_array($consulta1)) {
		$showmodal_clientes[] = $row1[0];
		$showmodal_clientes[] = $row1[1];
		$showmodal_clientes[] = $row1[2];
		$showmodal_clientes[] = $row1[3];
		$showmodal_clientes[] = $row1[4];
		$showmodal_clientes[] = $row1[5];
		$showmodal_clientes[] = $row1[6];
		$showmodal_clientes[] = $row1[7];
	}
	echo json_encode($showmodal_clientes);
}

if ($_POST["Actividad"] == 'update_cliente') {
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$rtn = $_POST['rtn'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$contacto = $_POST['contacto'];
	$tipo = $_POST['tipo'];
	$direccion = $_POST['direccion'];
	$cod_user = $_SESSION['codusu'];

	$validacion = ' <div class="table-responsive">
				        <table class="table text-center">
	                    <thead>
	                    <tr>
	                    </tr>
	                    </thead>
	                    <tbody>';

	$validar = validar_cliente($nombre, $telefono);
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
		$query1 = " UPDATE t_clientes SET cli_nombre = '$nombre', cli_rtn = '$rtn', cli_telefonos = '$telefono', cli_email = '$email',
                                              cli_contacto = '$contacto', cli_tipo = '$tipo', cli_direccion = '$direccion' 
                        WHERE cli_id = '$id'; ";

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



if ($_POST["Actividad"] == 'cargarProveedores') {
	$query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 2;";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
	}
}

if ($_POST["Actividad"] == 'cargarClientes') {
	$query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 1;";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_array($result)) {
		echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
	}
}


// Cierre de conexion 
mysqli_close($conn);
