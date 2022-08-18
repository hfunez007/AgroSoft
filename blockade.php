<?php
    //inicio la sesión
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    //COMPRUEBA QUE EL USUARIO ESTA AUTENTICADO
    if ($_SESSION['autenticado'] != 'x' && $_SESSION['username'] != 'cuenta' && $_SESSION['psw'] != 'password' ) 
    {
        //si no existe, va a la página de autenticación
        header('Location: index.html');
    
        //salimos de este script
        exit();
    } 
?>