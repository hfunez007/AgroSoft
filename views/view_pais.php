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
    <script src="negocios/js/fcn_initDataTable.js?v=<?= uniqid(); ?>"></script>
    <script>
        $(document).ready(function() {
            var param = {
                Actividad: "listapais"
            };
            var tbl = "tbl_pais";
            var model = "tree_pais";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Paises </h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_pais()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addpais"> <i class="fas fa-folder-plus"></i> </button>
                </div>
                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_pais">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Codigo</th>
                                <th style="text-align: center">Pais</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left modal-borderless" id="addpais" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Un Nuevo Pais</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="idpais">Codigo de Pais</label>
                                    <input type="text" class="form-control round" id="idpais" onKeyPress="return soloNumeros(event)" maxlength="13" autocomplete="on">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="nom_pais">Nombre</label>
                                    <input type="text" class="form-control round text-uppercase" id="nom_pais" onkeypress="return soloLetras(event)" placeholder="">
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_pais()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left modal-borderless" id="updpais" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Pais</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="idpais1">Codigo de Pais</label>
                                    <input type="text" hidden="none" class="form-control" id="txtidpais">
                                    <input type="text" class="form-control round" id="idpais1" onKeyPress="return soloNumeros(event)" maxlength="13" autocomplete="on">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="nom_pais1">Nombre</label>
                                    <input type="text" class="form-control round text-uppercase" id="nom_pais1" onkeypress="return soloLetras(event)" placeholder="">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>
                        <button type="button" class="btn btn-primary round ml-1" onclick="update_pais()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_pais.js?v=<?= uniqid(); ?>"></script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addpais').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addpais').find('form');
                $formulario[0].reset();
            });
        })
    </script>

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