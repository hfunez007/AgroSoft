<?php
include "../../datos/conexion_mysql.php";
global $conn;

session_start();
if ($_POST["Actividad"] == "Login") {
    $mensaje = "Usuario/Contraseña son incorrectos";
    $login = 0;

    $_SESSION['username'] = $_POST['userName'];
    $_SESSION['psw'] = $_POST['pass'];

    $params = $_POST['userName'];
    $sp = ("call P_LoginUsuarios_ss('$params')");
    $result = mysqli_query($conn, $sp);

    while ($row = mysqli_fetch_array($result)) {
        if ($_POST["pass"] == $row[1]) {
            $login = 1;
            $_SESSION['autenticado'] = 'x';
            $_SESSION['username'] = $_POST['userName'];
            $_SESSION['psw'] = $_POST['pass'];
            $_SESSION['expire'] = time();
            $_SESSION['codigo_rol'] = $row[3];
            $_SESSION['codusu'] = $row[2];
            $_SESSION['estadoUsuario'] = $row[4];

            if ($_SESSION['estadoUsuario'] == 2) {
                $login = 2;
            }
            break;
        } else {
            session_destroy();
        }
    }
    $resultado = array("codigoRol" => $_SESSION['codigo_rol'], "mensaje" => $mensaje, "log" => $login, "rol" => openssl_encrypt($_SESSION['codigo_rol'], "aes-256-cbc", "P@ssw0rd"));
    // $resultado = array("codigoRol" => $_SESSION['codigo_rol'], "mensaje" => $mensaje, "log" => $login, 'rol' => $_SESSION['codigo_rol']);

    echo json_encode($resultado);
}

/////////////////////////////////////////////////////cambio de pass////////////////////////////////////////

elseif ($_POST["Actividad"] == "CambiarPass") {


    $pass1 = $_POST['pass1'];
    $Coduser = $_SESSION['codusu'];



    $query = "UPDATE t_usuarios SET usu_password='$pass1',usu_estado = 1 WHERE usu_id = $Coduser";
    $result = mysqli_query($conn, $query);

    $exito = 'exito';

    echo json_encode($exito);
}
mysqli_close($conn);
