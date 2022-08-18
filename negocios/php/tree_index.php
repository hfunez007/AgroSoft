<?php
    include "../../datos/conexion_mysql.php";

    session_start();

    if ($_POST["Actividad"] =='lst_citasmedicas')
    {
        $cod_user=$_SESSION['codusu'];
        $query = "SELECT ROW_NUMBER() OVER(ORDER BY DATE_FORMAT(start_date, '%Y-%m-%d') ASC) AS 'fila', start_date, text  
                FROM events 
                where DATE_FORMAT(start_date, '%Y-%m-%d')>= DATE_FORMAT(NOW(), '%Y-%m-%d') and 
                end_date <= DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d'), INTERVAL 7 DAY);";

        $result = mysqli_query($conn,$query);
        $html="";
        while($row = mysqli_fetch_array($result))
        {
            $html.="<tr>";
            $html.="<td class='align-middle' align='center'>".$row[0]."</td>";
            $html.="<td class='align-middle' align='center'>".$row[1]."</td>";
            $html.="<td class='align-middle' align='center'>".$row[2]."</td>";
            $html.="</tr>";
        }
        echo $html;
    }

    // Cierre de conexion 
    mysqli_close($conn);

?>