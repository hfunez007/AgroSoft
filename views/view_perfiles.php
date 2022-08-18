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
    <script src="negocios/js/funciones_profiles.js?v=<?= uniqid(); ?>"></script>
    <script src="negocios/js/fcn_initDataTable.js?v=<?= uniqid(); ?>"></script>
    <script>
        $(document).ready(function() {
            var param = {
                Actividad: "showprofiles"
            };
            var tbl = "t_profiles";
            var model = "tree_profiles";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Perfiles</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_nuevoperfil()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addperfil"> <i class="fas fa-folder-plus"></i> </button>
                </div>
                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="t_profiles">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th class="text-center">Descripción Perfil</th>
                                <th class="text-center">Número de Usuarios</th>
                                <th class="text-center">Editar</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left modal-borderless" id="addperfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Perfil</h5>
                    </div>
                    <div class="modal-body">

                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtnuevoperfil"><strong>Nuevo Perfil</strong></label>
                                    <input type="text" onKeyPress="return soloLetras(event)" maxlength="64" class="form-control  round text-uppercase" id="txtnuevoperfil">
                                </div>
                            </div>
                            <br>
                            <div class="container">
                                <table class='table table-sm table-striped' id="add_profiles">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10px;">No</th>
                                            <th class="text-center">Opciones de Menú</th>
                                            <th class="text-center">Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <label>Marcar/Desmarcar Todos <input type="checkbox" id="checkTodos" /></label>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                        </button> <button type="button" class="btn btn-primary round ml-1" onclick="insernprofile();inserprofile();"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left modal-borderless" id="editarperfil" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Perfil</h5>
                    </div>
                    <div class="modal-body">

                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txteditperfil"><strong>Perfil</strong></label>
                                    <input type="text" onKeyPress="return soloLetras(event)" maxlength="64" class="form-control text-uppercase" id="txteditperfil">
                                </div>
                            </div>

                            <div class="container">
                                <table class="table table-bordered table-striped" id="edit_profiles">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 10px;">No</th>
                                            <th class="text-center">Opciones de Menú</th>
                                            <th class="text-center">Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>

                                <label>Marcar/Desmarcar Todos <input type="checkbox" id="checkTodos1" /></label>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                        </button> <button type="button" class="btn btn-primary round ml-1" onclick="updateprofile()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
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