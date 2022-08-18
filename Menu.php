<?php

include "datos/conexion_mysql.php";


$array = array();
$query = "SELECT DISTINCT cod_opc, desc_opc FROM v_menu  WHERE cod_rol = " . $_SESSION['codigo_rol'] . " and estado = 1  order by cod_opc";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
	$id = $row[0];
	$nombre = $row[1];
	array_push($array, $nombre);
}

$longitud = count($array);

for ($i = 0; $i < $longitud; $i++) {
	echo '<li class="sidebar-item  has-sub">';
	$num1 = 1;
	$num1++;

	echo '<a href="#link' . $i . '" class="sidebar-link">' . $array[$i];

	$query  = "SELECT DISTINCT desc_act, url_act, cod_opc, usufechacreacion
					   FROM v_menu 
					   WHERE desc_opc = '$array[$i]' AND cod_rol = " . $_SESSION['codigo_rol'] . " and estado = 1  order by usufechacreacion";
	$result =  mysqli_query($conn, $query);

	while ($row = mysqli_fetch_array($result)) {
		echo '<li> <a href="#" id=' . $row[1] . ' > ' . $row[0] . '</a> </li>';
	}
	echo '</ul></li>';
}


mysqli_close($conn);
