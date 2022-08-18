<?php
include "../../datos/conexion_mysql.php";
session_start();

    // escribir query
    // $fp = fopen('selecthist.txt', 'w');
    //  	  fwrite($fp, $query);	 
    //       fclose($fp);

    if ($_POST["Actividad"] =='listapais')
    {
        $cod_user=$_SESSION['codusu'];
        $query = "SELECT ROW_NUMBER() OVER(ORDER BY pais_id ASC) AS 'fila', pais_id, pais_codigo, pais_nombre FROM t_paises;";

        $result = mysqli_query($conn,$query);
        $html="";
        while($row = mysqli_fetch_array($result))
        {
            $html.="<tr>";
            $html.="<td class='align-middle' align='center'>".$row[0]."</td>";
            $html.="<td class='align-middle' align='center'>".$row[2]."</td>";
            $html.="<td class='align-middle' align='center'>".$row[3]."</td>";
            $html.='<td align="center"><button class="btn btn-outline-primary round" type="button" data-bs-toggle="modal" 
                                               data-bs-target="#updpais" onclick="showmodal_pais('.$row[1].');"><i class="fas fa-file-signature"></i></button></td>';
            $html.="</tr>";
        }
        echo $html;
    }
 
    if ($_POST["Actividad"] =='insertar_medida')
    {
        $codigop = $_POST['idpais'];
        $nombrep = $_POST['nom_pais'];
        $cod_user=$_SESSION['codusu'];
        
        $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';

        $validar = validar_pais($codigop, $nombrep);
        if ($validar  != '')
        {
            $validacion.= $validacion . $validar;
        }
        else
        {
            //Limpiar contenido
            $nombrep = trim($nombrep);
            $codigop = trim($codigop);
            
            $nombrep = filter_var($nombrep, FILTER_SANITIZE_STRING);
            //$codigop = filter_var($codigop, FILTER_SANITIZE_NUMBER_INT);
            
            $query1 = "INSERT INTO t_paises (pais_codigo,pais_nombre,usu_id,usu_fechacreacion)
            VALUES ('$codigop', '$nombrep','$cod_user',NOW())";	
            $consulta2 = mysqli_query($conn,$query1);
      
            if(!$consulta2) { $mensaje= "error"; }
            else { $mensaje= "exito"; }
	    }

        $validacion .= '</tbody> </table> </div>';
        $result = array("validacion"=> $validacion , "mensaje"=>$mensaje);
        echo json_encode($result);		
    }

    function validar_pais( $codigop, $nombrep)
    {
        // Quitar espacios en blanco
        $validacioncuerpo = '';
        $codigop = trim($codigop);
        $nombrep = trim($nombrep);
        //Validar si no vienene vacios
        if(empty($codigop)){ $validacioncuerpo .= '<tr><td>Ingrese el codigo del Pais</td> </tr>' ; }
        if(empty($nombrep)){ $validacioncuerpo .= '<tr><td>Ingrese El Nombre del Pais</td> </tr>' ; }
    
        
        return $validacioncuerpo;
    }

    if ($_POST["Actividad"] =='modal_pais')
    {
        $idpais = $_POST['idpais'];
        
        $query = "SELECT * FROM t_paises WHERE pais_id = '$idpais';";
        
        $consulta = mysqli_query($conn,$query);
        while($row = mysqli_fetch_array($consulta))
        {
            $showmodal_pais[]=$row[0];
            $showmodal_pais[]=$row[1];
            $showmodal_pais[]=$row[2];
        }
        echo json_encode($showmodal_pais);
    }

    if ($_POST["Actividad"] =='update_pais')
    {
        $idpais = $_POST['iddpais'];
        $codigop = $_POST['idpais'];
        $nombrep = $_POST['nom_pais'];
        $cod_user=$_SESSION['codusu'];
        
        $validacion = ' <div class="table-responsive">
                        <table class="table text-center">
                        <thead>
                        <tr>
                        </tr>
                        </thead>
                        <tbody>';

        $validar = validar_pais($codigop, $nombrep);
        if ($validar  != '')
        {
            $validacion.= $validacion . $validar;
        }
        else
        {
            //Limpiar contenido
            $nombrep = trim($nombrep);
            $codigop = trim($codigop);
            
            $nombrep = filter_var($nombrep, FILTER_SANITIZE_STRING);
            //$codigop = filter_var($codigop, FILTER_SANITIZE_NUMBER_INT);
            
            $query1 = "UPDATE t_paises SET pais_codigo = '$codigop', pais_nombre = '$nombrep'
                       WHERE pais_id = '$idpais'; ";	
            $consulta2 = mysqli_query($conn,$query1);
      
            if(!$consulta2) { $mensaje= "error"; }
            else { $mensaje= "exito"; }
	    }

        $validacion .= '</tbody> </table> </div>';
        $result = array("validacion"=> $validacion , "mensaje"=>$mensaje);
        echo json_encode($result);		
    }
 
// Cierre de conexion 
mysqli_close($conn);

?>