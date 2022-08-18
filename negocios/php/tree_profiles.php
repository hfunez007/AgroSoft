<?php
include "../../datos/conexion_mysql.php";

session_start();

//////////////////////////////cargar todos los perfiles o roles///////////////////////////////
if ($_POST["Actividad"] =='showprofiles')
{
	$cod_user=$_SESSION['codusu'];

	$query = "SELECT a.rol_id, a.rol_nombre, count(b.rol_id_rol) from t_roles a
	left join v_profiles b on a.rol_id = b.rol_id_rol 
	group by a.rol_id order by a.rol_id";


	$result1=mysqli_query($conn,$query);

	$sumanumeros=1; 
     
	while($row = mysqli_fetch_array($result1))
	{

		$numeros+=$sumanumeros;
		$html.='<tr>';
		$html.="<td class='align-middle' align='center'>".$numeros."</td>";
		$html.="<td class='align-middle' align='left'>".$row[1]."</td>";
		$html.="<td class='align-middle' align='center'>".$row[2]."</td>";
		$idrm=$row[0];
		$html.='<td><center><button type="button" onclick="infop('.$idrm.'),showoption1('.$idrm.');" value="" class="btn btn-outline-primary round" data-bs-toggle="modal" data-bs-target="#editarperfil" ><i class="fas fa-file-signature"></i></button></td>';
		$html.='</tr>';

	}
	// echo json_encode($html);
	echo $html;
}

/////////////////////Insertar Nuevo Rol/////////////////////////////

elseif ($_POST["Actividad"] =='newrol')
{
	$nuevoperfil = $_POST['nuevoperfil'];
	$cod_user=$_SESSION['codusu'];
	
	$query="SELECT rol_nombre FROM t_roles where rol_nombre='$nuevoperfil'";

	$consulta1=mysqli_query($conn,$query);
 
	while($row = mysqli_fetch_array($consulta1))
	{
		$existe=$row[0];
	}

	if ($existe!='')
	{
		$mensaje= "existe";
	}
	else
	{
		$query="INSERT t_roles (rol_nombre, usu_id, usufechacreacion) 
		VALUES ('$nuevoperfil',$cod_user,NOW())";
		
		$consulta2=mysqli_query($conn,$query);
		if(!$consulta2){$mensaje= "error";}
		else
		{
			$mensaje= "exito";
		}
}
	echo json_encode($mensaje);		
}

//////////////////////////////cargar todas las opciones para nuevos perfiles////////////////////////////

else if ($_POST["Actividad"] =='showoption')
{

	$cod_user=$_SESSION['codusu'];

	$query = "SELECT desc_act, url_act, cod_opc FROM t_menu_act where cod_rol=1 and estado =1 order by url_act";

	$result1=mysqli_query($conn,$query);

	$sumanumeros=1; 
     
	while($row = mysqli_fetch_array($result1))
	{

		$numeros+=$sumanumeros;
		$html.="<tr>";
		$html.="<td align='center'>".$numeros."</td>";
		$html.="<td align='left'>".$row[0]."</td>";
		$html.="<td align='center'><input type='checkbox'  name='options[]'></td>";
		$desc=$row[0];
		$html.="<td align='center' hidden='none'><input type='text'  name='options1[]' value='".$desc."'></td>";
		$url=$row[1];
		$html.="<td align='center' hidden='none'><input type='text'  name='options2[]' value=".$url."></td>";
		$opt=$row[2];
		$html.="<td align='center' hidden='none'><input type='text'  name='options3[]' value=".$opt."></td>";
		$html.="</tr>";
	}

	echo json_encode($html);
}

/////////////////////Insertar Opciones de Nuevo Rol/////////////////////////////
elseif ($_POST["Actividad"] =='inserprofile')
{
	$estado1 = $_POST['estado1'];
	$desc1 = $_POST['desc1'];
	$url1 = $_POST['url1'];
	$opt1 = $_POST['opt1'];
	$cod_user=$_SESSION['codusu'];

	/////max id rol//////////////////////////////////

	$query = "SELECT max(rol_id) FROM t_roles";

	$result=mysqli_query($conn,$query);

	while($row = mysqli_fetch_array($result))
	{
		$idrol=$row[0];
	}

	$query1="INSERT INTO t_menu_act (desc_act,url_act,cod_opc,cod_rol,estado,usu_id,usufechacreacion)
			 VALUES ('$desc1','$url1',$opt1,$idrol,$estado1,$cod_user,NOW())";
	
	$consulta1=mysqli_query($conn,$query1);
 
	echo json_encode($mensaje);		
}

//////////////////////////////cargar todas las opciones para editar perfiles////////////////////////////

else if ($_POST["Actividad"] =='showoption1')
{

	$idrm2 = $_POST['idrm2'];
	$cod_user=$_SESSION['codusu'];

	// $query = "SELECT  distinct desc_act, url_act, cod_opc, estado 
	//           FROM t_menu_act where cod_rol=$idrm2
	//           union
	// 		SELECT  distinct desc_act, url_act, cod_opc, case when estado=1 then estado=0 end as 'estado' FROM t_menu_act where url_act not in 
	// 		(SELECT distinct url_act FROM t_menu_act where cod_rol=$idrm2)
	// 		order by url_act";

	$query = "SELECT distinct desc_act, url_act, cod_opc, estado FROM t_menu_act where cod_rol = $idrm2 and estado = 1
			union
			SELECT  distinct desc_act, url_act, cod_opc, case when estado=1 then estado=0 end as 'estado' 
					FROM t_menu_act where cod_rol = 1 and estado = 1 and url_act not in (SELECT distinct url_act FROM t_menu_act where cod_rol = $idrm2 and estado = 1) 
						order by url_act;";

	$result1=mysqli_query($conn,$query);

	$sumanumeros=1; 
     
	while($row = mysqli_fetch_array($result1))
	{

		$numeros+=$sumanumeros;
		
		$html.="<tr>";
		$html.="<td align='center'>".$numeros."</td>";
		$html.="<td align='left'>".$row[0]."</td>";
		$estado1=$row[3];
		if ($estado1==1)
		{
			$estado2='checked';
		}
		else
		{
			$estado2='';
		}

		$html.="<td align='center'><input type='checkbox'  name='options4[]' $estado2></td>";
		$desc=$row[0];
		$html.="<td align='center' hidden='none'><input type='text' hidden='none' name='options5[]' value='".$desc."'></td>";
		$url=$row[1];
		$html.="<td align='center' hidden='none'><input type='text' hidden='none' name='options6[]' value=".$url."></td>";
		$opt=$row[2];
		$html.="<td align='center' hidden='none'><input type='text' hidden='none' name='options7[]' value=".$opt."></td>";
		$html.="</tr>";
	}

	echo json_encode($html);
}

//////////////////////////INFORMACION GENERAL PARA MODAL DE PROFILES/////////////////////////////////
elseif ($_POST["Actividad"] =='infop')
{

	$idrm1 = $_POST['idrm1'];

	$query="SELECT distinct rol_nombre FROM v_actprofiles where cod_rol=$idrm1";

	$consulta1=mysqli_query($conn,$query);
	
	while($row1 = mysqli_fetch_array($consulta1))
	{
		$Infog[]=$row1[0];
		// $Infog[]=$row1[1];
		// $Infog[]=$row1[2];
		// $Infog[]=$row1[3];
		// $Infog[]=$row1[4];
		// $Infog[]=$row1[5];
		// $Infog[]=$row1[6];
		// $Infog[]=$row1[7];
	}
	echo json_encode($Infog);
}

/////////////////////Insertar Opciones de Nuevo Rol/////////////////////////////
elseif ($_POST["Actividad"] =='updateprofile')
{

	$idperfil = $_POST['idperfil'];
	$est = $_POST['est'];
	$desc1 = $_POST['desc1'];
	$url1 = $_POST['url1'];
	$opt1 = $_POST['opt1'];
	$cod_user=$_SESSION['codusu'];

	$query = "SELECT rol_id FROM t_roles where rol_nombre='$idperfil'";

	$result=mysqli_query($conn,$query);

	while($row = mysqli_fetch_array($result))
	{
			$idrol=$row[0];
	}


 	$query2 = "SELECT distinct url_act FROM t_menu_act where cod_rol=$idrol and url_act='$url1'";
 	$result2=mysqli_query($conn,$query2);

	while($row = mysqli_fetch_array($result2))
	{
		$url2=$row[0];
	}
 	if ($url2!='')
	 {

		$query1="UPDATE t_menu_act SET estado = $est , usu_id =$cod_user ,usufechacreacion = NOW() 
		WHERE desc_act ='$desc1' and url_act='$url1' and cod_opc= $opt1 and cod_rol=$idrol";
		$consulta1=mysqli_query($conn,$query1);
	}
 	else
	{

		$query3="INSERT INTO t_menu_act (desc_act,url_act,cod_opc,cod_rol,estado,usu_id,usufechacreacion)
		VALUES ('$desc1','$url1',$opt1,$idrol,$est,$cod_user,NOW())";
		$consulta3=mysqli_query($conn,$query3);
 	}


	if(!$consulta1 || !$consulta3 ){$mensaje= "error";}
	else
	{
		$mensaje= "exito";
	}
	echo json_encode($mensaje);		
}

	// Cierre de conexion 
	mysqli_close($conn);
?>


