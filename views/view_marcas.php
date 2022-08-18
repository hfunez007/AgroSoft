<?php
if (!isset($_SESSION)) {
    session_start();
}
$user = $_SESSION['username'];
// define('MAX_IDLE_TIME', 1);  
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
                Actividad: "lst_marcas"
            };
            var tbl = "tbl_marcas";
            var model = "tree_marcas";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Marcas</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_marca()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addMarca">
                        <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_marcas">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody> </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Modal Agregar Producto-->
        <div class="modal fade text-left" id="addMarca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Marca</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtnombreMarca"><strong>Nombre</strong></label>
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtnombreMarca">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_marca()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar Producto-->

        <!-- Modal Modificar-->
        <div class="modal fade text-left" id="updMarca" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Marca</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtnombreMarca1"><strong>Nombre</strong></label>
                                    <input type="text" hidden="none" class="form-control" id="txtmarcaId">
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtnombreMarca1">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="update_marca()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>


    <!-- Script -->
    <script src="negocios/js/fcn_marcas.js?v=<?= uniqid(); ?>"></script>

    <!-- Funcion solo de Numeros -->
    <script>
        function soloNumeros(e) {
            var key = window.Event ? e.which : e.keyCode
            return (key >= 48 && key <= 57)
        }
    </script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addMarca').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addMarca').find('form');
                $formulario[0].reset();
            });
        })
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
    <!-- Funcion decimales -->
    <script>
        $(document).on('keydown', 'input[pattern]', function(e) {
            var input = $(this);
            var oldVal = input.val();
            var regex = new RegExp(input.attr('pattern'), 'g');

            setTimeout(function() {
                var newVal = input.val();
                if (!regex.test(newVal)) {
                    input.val(oldVal);
                }
            }, 0);
        });
    </script>
</body>

</html>