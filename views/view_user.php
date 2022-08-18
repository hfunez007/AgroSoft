<?php
if (!isset($_SESSION)) {
    session_start();
}
$user = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <script src="negocios/js/funciones_user.js?v=<?= uniqid(); ?>"></script>
    <script src="negocios/js/fcn_initDataTable.js?v=<?= uniqid(); ?>"></script>
    <script>
        $(document).ready(function() {
            var param = {
                Actividad: "showduser"
            };
            var tbl = "datosuserb";
            var model = "tree_user";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Usuarios</h3>
            <div class="card-body">
                <div>
                    <button type="button" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#nuevosregistrosusu"> <i class="fas fa-folder-plus"></i> </button>
                </div>
                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="datosuserb">
                        <thead>
                            <tr>
                                <th style="text-align: center">N°</th>
                                <th style="text-align: center">Identidad</th>
                                <th style="text-align: center">Nombre Completo</th>
                                <th style="text-align: center">Perfil</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Editar</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left modal-borderless" id="nuevosregistrosusu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Usuario</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="idper">Identidad</label>
                                    <input type="text" hidden="none" class="form-control round" id="codper">
                                    <input type="text" class="form-control round" id="idper" name="idper" placeholder="0000000000000" onKeyPress="return soloNumeros(event)" maxlength="13" autocomplete="on">
                                </div>

                                <div class="form-group col-md-6 my-3">
                                    <button type="button" class="btn btn-outline-primary round block  my-1" id="BuscarUsuario" name="BuscarUsuario" onclick="Get_IdPer();"> <i class="fas fa-search"></i> Buscar</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nomper">Nombres</label>
                                    <input type="text" class="form-control round text-uppercase" id="nomper" onkeypress="return soloLetras(event)" placeholder="">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="apeper">Apellidos</label>
                                    <input type="text" class="form-control round text-uppercase" id="apeper" onkeypress="return soloLetras(event)" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="selectgenero1">Genero</label>
                                    <select class="form-select round" id="selectgenero1">
                                        <option value="0">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $query = "SELECT * FROM t_sexo";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fechaper">Fecha Nacimiento</label>
                                    <input type="date" id="fechaper" class="form-control round">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="telper">Telefono</label>
                                    <input type="text" class="form-control round" id="telper" onKeyPress="return soloNumeros(event)" maxlength="8" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="telper">E-Mail</label>
                                    <input type="email" class="form-control round text-uppercase" id="emailper" placeholder="">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="selectrol1">Rol de Usuario</label>
                                    <select class="form-select round" id="selectrol1">
                                        <option value="0">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $cod_rol = $_SESSION['codigo_rol'];
                                        $query = "SELECT * FROM  t_roles";

                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                        </button> <button type="button" class="btn btn-primary round ml-1" onclick="insertuser();"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left modal-borderless" id="infouser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Usuario</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="idper1">Identidad</label>
                                    <input type="text" hidden="none" class="form-control round" id="codper2">
                                    <input type="text" class="form-control round" id="idper1" name="idper1" placeholder="0000000000000" onKeyPress="return soloNumeros(event)" maxlength="13" autocomplete="on">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nomper">Nombres</label>
                                    <input type="text" class="form-control round text-uppercase" id="nomper1" onkeypress="return soloLetras(event)" placeholder="">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="apeper">Apellidos</label>
                                    <input type="text" class="form-control round text-uppercase" id="apeper1" onkeypress="return soloLetras(event)" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="selectgenero1">Genero</label>
                                    <select class="form-select round" id="selectgenero2">
                                        <option value="0">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $query = "SELECT * FROM t_sexo";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="fechaper">Fecha Nacimiento</label>
                                    <input type="date" id="fechaper1" class="form-control round">
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="telper">Telefono</label>
                                    <input type="text" class="form-control round" id="telper1" onKeyPress="return soloNumeros(event)" maxlength="8" placeholder="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="telper">E-Mail</label>
                                    <input type="email" class="form-control round text-uppercase" id="emailper1" placeholder="">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="selectrol1">Rol de Usuario</label>
                                    <select class="form-select round" id="selectrol2">
                                        <option value="0">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $cod_rol = $_SESSION['codigo_rol'];
                                        if ($cod_rol == 1) {
                                            $query = "SELECT * FROM  t_roles";

                                            $result = mysqli_query($conn, $query);
                                        } else if ($cod_rol == 2) {
                                            $query = "SELECT * FROM  t_roles where rol_id <> 1";

                                            $result = mysqli_query($conn, $query);
                                        }
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="selectestados2">Estado</label>
                                    <select class="form-select round" id="selectestados2">
                                        <option value="0">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $query = "SELECT * FROM t_estado";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                        </button> <button type="button" class="btn btn-primary round ml-1" onclick="updateusuarios()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>

    <!-- Funcion solo de Numeros -->
    <script>
        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode
            return (key >= 48 && key <= 57)
        }
    </script>

    <!-- Funcion solo de Letras -->
    <script>
        function soloLetras(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toLowerCase();
            letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
            especiales = "8-37-39-46";

            tecla_especial = false
            for (var i in especiales) {
                if (key == especiales[i]) {
                    tecla_especial = true;
                    break;
                }
            }
            if (letras.indexOf(tecla) == -1 && !tecla_especial) {
                return false;
            }
        }
    </script>
</body>

</html>