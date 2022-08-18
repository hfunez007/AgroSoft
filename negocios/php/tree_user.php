<?php
include "../../datos/conexion_mysql.php";
session_start();

///////////////////////////////////////////informacion del usuario//////////////////////////////////////////

if ($_POST["actividad"] == 'Get_IdPer') {
	$Identidad = $_POST["Identidad"];

	$query = "SELECT ID_PERSONAS,NUMERO_IDENTIDAD,PRIMER_NOMBRE,SEGUNDO_NOMBRE,PRIMER_APELLIDO,SEGUNDO_APELLIDO,CODIGO_SEXO,
		DATE_FORMAT(FECHA_NACIMIENTO, '%Y-%m-%d') FROM t_gloper where NUMERO_IDENTIDAD='$Identidad'";

	$consulta1 = mysqli_query($conn, $query);

	while ($row1 = mysqli_fetch_array($consulta1)) {
		$InfPer[] = $row1[0];
		$InfPer[] = $row1[1];
		$InfPer[] = $row1[2];
		$InfPer[] = $row1[3];
		$InfPer[] = $row1[4];
		$InfPer[] = $row1[5];
		$InfPer[] = $row1[6];
		$InfPer[] = $row1[7];
	}

	echo json_encode($InfPer);
}

/////////////////////INSERT USUARIOS NUEVO/////////////////////////////

if ($_POST["Actividad"] == 'insertuser') {
	$identidad = $_POST['identidad'];
	$nombres = $_POST['nombres'];
	$apellidos = $_POST['apellidos'];
	$genero = $_POST['genero'];
	$fechan = $_POST['fechan'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$rol = $_POST['rol'];
	$cod_user = $_SESSION['codusu'];


	$query0 = "SELECT per_identidad FROM t_personas2 where per_identidad='$identidad'";

	$consulta1 = mysqli_query($conn, $query0);

	while ($row = mysqli_fetch_array($consulta1)) {
		$existe = $row[0];
	}

	if ($existe != '') {
		$mensaje = "existe";
	} else {
		$query = "INSERT t_personas2 (per_nombres, per_apellidos, per_identidad, per_telefono,per_fechanac ,per_email, per_sexo, usu_id, usu_fechacreacion) 
		VALUES ('$nombres', '$apellidos','$identidad','$telefono','$fechan','$email',$genero,$cod_user,NOW())";

		$consulta2 = mysqli_query($conn, $query);
		if (!$consulta2) {
			$mensaje = "error";
		} else {
			$query1 = "SELECT CONCAT(LEFT(per_nombres,1),per_apellidos) AS apellido, per_id FROM t_personas2 where per_identidad='$identidad'";

			$consulta3 = mysqli_query($conn, $query1);
			while ($row = mysqli_fetch_array($consulta3)) {
				$alias = $row[0];
				$per_id = $row[1];
			}

			$query2 = "SELECT usu_alias FROM t_usuarios where usu_alias='$alias'";

			$consulta4 = mysqli_query($conn, $query2);

			while ($row = mysqli_fetch_array($consulta4)) {
				$alias2 = $row[0];
			}

			if ($alias2 != "") {

				$query4 = "SELECT CONCAT(LEFT(per_nombres,2),per_apellidos) AS apellido,per_id FROM t_personas2 where per_identidad='$identidad'";
				$consulta6 = mysqli_query($conn, $query4);

				while ($row = mysqli_fetch_array($consulta6)) {
					$alias3 = $row[0];
					$per_id2 = $row[1];
				}

				// Contar cuantos hay con el alias tres
				$query2 = "SELECT count(*) FROM t_usuarios where usu_alias like '$alias3%'";

				$consulta4 = mysqli_query($conn, $query2);

				while ($row = mysqli_fetch_array($consulta4)) {
					$total = $row[0];
				}
				$totales = $total + 1;

				$ali = $alias3 . $totales;

				$query5 = "INSERT INTO t_usuarios(usu_alias,usu_password,rol_id, usu_estado,per_id,usu_idcreo,usu_fechacreacion) VALUES('$ali','123',$rol,2,$per_id2,$cod_user,NOW())";

				$consulta7 = mysqli_query($conn, $query5);

				$query11 = "SELECT usu_alias,usu_password,nombre,estatus FROM v_detalleuser where per_identidad='$identidad'";

				$consulta11 = mysqli_query($conn, $query11);

				while ($row = mysqli_fetch_array($consulta11)) {
					$alias11 = $row[0];
					$pass11 = $row[1];
					$nombre11 = $row[2];
					$estatus10 = $row[3];
				}
				require 'PHPMailer/Exception.php';
				require 'PHPMailer/PHPMailer.php';
				require 'PHPMailer/SMTP.php';

				$mail = new PHPMailer\PHPMailer\PHPMailer();
				//$mail = new PHPMailer(true); 
				$mail->isSMTP();
				$mail->Host = 'mail.portal-asihn.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'hfunez@portal-asihn.com'; //Correo de donde enviaremos los correos
				$mail->Password = 'Homero@123!'; // Password de la cuenta de envío
				$mail->SMTPSecure = 'ssl';

				$mail->Port = 465;
				$mail->setFrom('hfunez@portal-asihn.com', 'Inicio de Sesion');

				$mail->addAddress($email, $nombre11); //Correo receptor	
				$mail->IsHTML(true);
				$mail->Subject = 'Sistema de Facturacion - Papeleria Honduras';
				$mail->Body    = '<b> Bienvenidos al Sistema de Facturacion de Papeleria Honduras </b><br>
					
								<p>Estimado(a): ' . $nombre11 . ', a continuacion sus datos de acceso al sistema</p>
								<hr>														
								<b>Nombre para Ingresar:&nbsp;</b>' . $alias11 . '<br>
								<b>Clave Temporal:&nbsp;</b>' . $pass11 . '<br>							
								<b>Dirección del Sitio:&nbsp;</b><a href="#https://portal-asihn.com/demos/ph/index.html">Entrar al Sistema.</a><br>	
								<hr>							
								<b><strong>NOTA:</strong> Se le solicitará para su primer acceso, realizar cambio de clave temporal.<br>
								<br>
								<b><div align="left">
								<img src="https://portal-asihn.com/demos/ph/assets/images/logoPH.png" style="width:300px;height:200px;opacity:50%;"></div>';

				$mail->CharSet = 'UTF-8';
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);

				if ($mail->send()) {
					$mensaje = "exito";
				} else {
					$mensaje = "error";
				}
			} else {
				$query3 = "INSERT INTO t_usuarios(usu_alias,usu_password,rol_id, usu_estado,per_id,usu_idcreo,usu_fechacreacion) VALUES('$alias','123',$rol,2,$per_id,$cod_user,NOW())";

				$consulta5 = mysqli_query($conn, $query3);

				$query10 = "SELECT usu_alias,usu_password,nombre,estatus FROM v_detalleuser where per_identidad='$identidad'";

				$consulta10 = mysqli_query($conn, $query10);

				while ($row = mysqli_fetch_array($consulta10)) {
					$alias10 = $row[0];
					$pass10 = $row[1];
					$nombre10 = $row[2];
					$estatus10 = $row[3];
				}

				require 'PHPMailer/Exception.php';
				require 'PHPMailer/PHPMailer.php';
				require 'PHPMailer/SMTP.php';

				$mail = new PHPMailer\PHPMailer\PHPMailer();
				//$mail = new PHPMailer(true); 
				$mail->isSMTP();
				$mail->Host = 'mail.portal-asihn.com';
				$mail->SMTPAuth = true;
				$mail->Username = 'hfunez@portal-asihn.com'; //Correo de donde enviaremos los correos
				$mail->Password = 'Homero@123!'; // Password de la cuenta de envío
				$mail->SMTPSecure = 'ssl';

				$mail->Port = 465;
				$mail->setFrom('hfunez@portal-asihn.com', 'Inicio de Sesion');
				$mail->addAddress($email, $nombre10); //Correo receptor	
				$mail->IsHTML(true);
				$mail->Subject = 'Sistema Contable Valencia School';
				$mail->Subject = 'Sistema de Facturacion - Papeleria Honduras';
				$mail->Body    = '<b> Bienvenidos al Sistema de Facturacion de Papeleria Honduras </b><br>
					
								<p>Estimado(a): ' . $nombre10 . ', a continuacion sus datos de acceso al sistema</p>
								<hr>														
								<b>Nombre para Ingresar:&nbsp;</b>' . $alias10 . '<br>
								<b>Clave Temporal:&nbsp;</b>' . $pass10 . '<br>							
								<b>Dirección del Sitio:&nbsp;</b><a href="#https://portal-asihn.com/demos/ph/index.html">Entrar al Sistema.</a><br>	
								<hr>							
								<b><strong>NOTA:</strong> Se le solicitará para su primer acceso, realizar cambio de clave temporal.<br>
								<br>
								<b><div align="left">
								<img src="https://portal-asihn.com/demos/ph/assets/images/logoPH.png" style="width:300px;height:200px;opacity:50%;"></div>';

				$mail->CharSet = 'UTF-8';
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);

				if ($mail->send()) {
					$mensaje = "exito";
				} else {
					$mensaje = "error";
				}
			}

			$mensaje = "exito";
		}
	}

	echo json_encode($mensaje);
}

//////////////////////////////cargar generos///////////////////////////////

if ($_POST["Actividad"] == 'CargarGenero1') {

	$query = "SELECT * FROM  t_sexo";

	$result = mysqli_query($conn, $query);


	echo " <option value='0'>SELECCIONAR</option>";

	while ($row = mysqli_fetch_array($result)) {
		echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
	}
}

//////////////////////////////cargar roles///////////////////////////////

if ($_POST["Actividad"] == 'CargarRol1') {

	$cod_rol = $_SESSION['codigo_rol'];

	$query = "SELECT * FROM  t_roles";

	$result = mysqli_query($conn, $query);

	echo " <option value='0'>SELECCIONAR</option>";

	while ($row = mysqli_fetch_array($result)) {
		echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
	}
}

///////////////////////cargar datos generales de los usuarios ///////////
if ($_POST["Actividad"] == 'showduser') {
	$cargar = $_POST['cargar'];
	$cod_rol = $_SESSION['codigo_rol'];
	$query = "SELECT fila, per_identidad, nombre, rol_nombre, estatus, id_usuario FROM v_detalleuser where usu_id<>1;";

	$result1 = mysqli_query($conn, $query);
	$sumanumeros = 1;

	while ($row = mysqli_fetch_array($result1)) {
		$html .= "<tr>";
		$html .= "<td class='align-middle' align='center'>" . $row[0] . "</td>";
		$html .= "<td class='align-middle' align='center'>" . $row[1] . "</td>";
		$html .= "<td class='align-middle' align='center'>" . $row[2] . "</td>";
		$html .= "<td class='align-middle' align='center'>" . $row[3] . "</td>";
		$html .= "<td class='align-middle' align='center'>" . $row[4] . "</td>";
		$idrm = $row[5];
		$html .= '<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" data-bs-target="#infouser" onclick="infouser(' . $idrm . ');"><i class="fas fa-file-signature"></i></button></td>';
		$html .= "</tr>";
	}
	echo $html;
}

//////////////////////////UPDATE INFORMACION GENERAL DE LOS BARRIOS/////////////////////////////////
elseif ($_POST["Actividad"] == 'updateusuarios') {
	$codper = $_POST['codper'];
	$identidad = $_POST['identidad'];
	$nombres = $_POST['nombres'];
	$apellidos = $_POST['apellidos'];
	$genero = $_POST['genero'];
	$fechan = $_POST['fechan'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$rol = $_POST['rol'];
	$estado = $_POST['estado'];
	$cod_user = $_SESSION['codusu'];


	$query2 = " UPDATE t_personas2 
				SET per_nombres ='$nombres', per_apellidos='$apellidos',per_identidad='$identidad', per_telefono='$telefono', 
					per_fechanac='$fechan', per_email='$email', per_sexo=$genero,usu_id=$cod_user, usu_fechacreacion=NOW() 
				WHERE per_id= $codper";

	$consulta2 = mysqli_query($conn, $query2);

	$query3 = " UPDATE t_usuarios SET  usu_estado =$estado WHERE per_id= $codper";

	$consulta3 = mysqli_query($conn, $query3);

	$query4 = " UPDATE t_usuarios SET  rol_id =$rol WHERE per_id= $codper";

	$consulta4 = mysqli_query($conn, $query4);

	if (!$consulta2 || !$consulta3 || !$consulta4) {
		$mensaje = "error";
	} else {
		$mensaje = "exito";
	}

	echo json_encode($mensaje);
}

//////////////////////////////////////cargar datos de actualizaciones de usuarios///////////////////////////////
if ($_POST["Actividad"] == 'showdactuser') {

	$cod_user = $_POST['cod_user'];

	$query = "SELECT ROW_NUMBER() OVER(ORDER BY a.cod_perac ASC) AS 'fila', a.perac_identidad,CONCAT (a.perac_nombres,' ',a.perac_apellidos),CASE WHEN c.usu_estado = 1 THEN 'ACTIVO' ELSE 'INACTIVO'END AS usu_estado, c.usu_alias, a.usu_fechacreacion, c.usu_alias,a.perac_fecha FROM t_actuser a 
		inner join t_usuarios c on a.usu_id=c.usu_id and a.perac_user=c.usu_id
		where a.perac_id=$cod_user";

	$result1 = mysqli_query($conn, $query);

	$sumanumeros = 1;

	while ($row = mysqli_fetch_array($result1)) {

		$numeros += $sumanumeros;
		$html .= '<tr style="font-size: 11px;">';
		$html .= '<td align="center">' . $row[0] . '</td>';
		$html .= '<td align="center">' . $row[1] . '</td>';
		$html .= '<td align="left">' . $row[2] . '</td>';
		$html .= '<td align="left">' . $row[3] . '</td>';
		$html .= '<td align="center">' . $row[4] . '</td>';
		$html .= '<td align="center">' . $row[5] . '</td>';
		$html .= '<td align="center">' . $row[6] . '</td>';
		$html .= '<td align="center">' . $row[7] . '</td>';
		$html .= '<td align="center">' . $row[8] . '</td>';
		$html .= '<td align="center">' . $row[9] . '</td>';
		$html .= '</tr>';
	}
	echo json_encode($html);
}

//////////////////////////INFORMACION GENERAL PARA MODAL DE USUARIOS/////////////////////////////////
if ($_POST["Actividad"] == 'infouser') {
	$idrm1 = $_POST['idrm1'];
	$query = "SELECT  a.per_id, a.per_identidad, a.per_nombres, a.per_apellidos, a.per_sexo, 
						DATE_FORMAT(a.per_fechanac, '%Y-%m-%d') , a.per_telefono, a.per_email,b.rol_id, b.usu_estado 
				FROM t_personas2 a inner join t_usuarios b on a.per_id=b.per_id where a.per_id=$idrm1";

	$consulta1 = mysqli_query($conn, $query);

	while ($row1 = mysqli_fetch_array($consulta1)) {
		$Infog[] = $row1[0];
		$Infog[] = $row1[1];
		$Infog[] = $row1[2];
		$Infog[] = $row1[3];
		$Infog[] = $row1[4];
		$Infog[] = $row1[5];
		$Infog[] = $row1[6];
		$Infog[] = $row1[7];
		$Infog[] = $row1[8];
		$Infog[] = $row1[9];
	}

	echo json_encode($Infog);
}


/////////////////////update///////////////////////////

//////////////////////////////cargar generos///////////////////////////////

if ($_POST["Actividad"] == 'CargarGenero2') {

	$query = "SELECT * FROM  t_sexo";

	$result = mysqli_query($conn, $query);

	echo " <option value='0'>SELECCIONAR</option>";

	while ($row = mysqli_fetch_array($result)) {
		echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
	}
}

//////////////////////////////cargar roles///////////////////////////////

if ($_POST["Actividad"] == 'CargarRol2') {
	$cod_rol = $_SESSION['codigo_rol'];

	if ($cod_rol == 1) {
		$query = "SELECT * FROM  t_roles";

		$result = mysqli_query($conn, $query);
	} else if ($cod_rol == 2) {
		$query = "SELECT * FROM  t_roles where rol_id <> 1";

		$result = mysqli_query($conn, $query);
	}

	echo " <option value='0'>SELECCIONAR</option>";

	while ($row = mysqli_fetch_array($result)) {
		echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
	}
}

//////////////////////////////cargar estados registrados///////////////////////////////

if ($_POST["Actividad"] == 'CargarEstados2') {
	$query = "SELECT * FROM  t_estados";

	$result = mysqli_query($conn, $query);

	echo " <option value='0'>SELECCIONAR</option>";

	while ($row = mysqli_fetch_array($result)) {
		echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
	}
}

if ($_POST["Actividad"] == 'showdactuser') {
	$cod_barrio = $_POST['cod_barrio'];

	$query = "SELECT ROW_NUMBER() OVER(ORDER BY cod_barrio ASC) AS 'fila', b_barrio ,a.b_viviendas, 
						CASE WHEN a.b_estado = 1 THEN 'ACTIVO' ELSE 'INACTIVO' END AS b_estado, a.b_ciudad, a.b_pais,b.usu_alias, a.b_fecha,
						b.usu_alias, a.act_fecha 
				FROM portalas_sscc1.t_actbarrio a inner join portalas_sscc1.t_usuarios b on a.b_user=b.usu_id
				WHERE a.cod_barrio = $cod_barrio";
	$result1 = mysqli_query($conn, $query);

	$sumanumeros = 1;

	while ($row = mysqli_fetch_array($result1)) {
		$numeros += $sumanumeros;
		$html .= '<tr style="font-size: 11px;">';
		$html .= '<td align="center">' . $row[0] . '</td>';
		$html .= '<td align="left">' . $row[1] . '</td>';
		$html .= '<td align="center">' . $row[2] . '</td>';
		$html .= '<td align="left">' . $row[3] . '</td>';
		$html .= '<td align="center">' . $row[4] . '</td>';
		$html .= '<td align="center">' . $row[5] . '</td>';
		$html .= '<td align="center">' . $row[6] . '</td>';
		$html .= '<td align="center">' . $row[7] . '</td>';
		$html .= '<td align="center">' . $row[8] . '</td>';
		$html .= '<td align="center">' . $row[9] . '</td>';
		$html .= '</tr>';
	}

	echo json_encode($html);
}

//cerrar conexión///
mysqli_close($conn);
