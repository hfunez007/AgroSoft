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
                Actividad: "listamedidas"
            };
            var tbl = "tbl_medidas";
            var model = "tree_medidas";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de SKU </h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_medidas()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addMedida">
                        <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>
                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_medidas">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Medida</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left" id="addMedida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar SKU</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtMedidaNombre">Nombre</label>
                                    <input type="text" class="form-control round text-uppercase" id="txtMedidaNombre">
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_medida()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left" id="updMedida" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar SKU</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtMedidaNombre1">Nombre</label>
                                    <input type="text" hidden="none" class="form-control" id="txtidmedida">
                                    <input type="text" class="form-control round text-uppercase" id="txtMedidaNombre1">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>
                        <button type="button" class="btn btn-primary round ml-1" onclick="update_medida()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_medida.js?v=<?= uniqid(); ?>"></script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addMedida').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addMedida').find('form');
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