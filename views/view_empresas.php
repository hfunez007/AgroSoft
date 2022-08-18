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
                Actividad: "lst_empresas"
            };
            var tbl = "tbl_empresas";
            var model = "tree_empresas";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Empresas</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_empresas()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addEmpresa">
                        <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_empresas">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">RTN</th>
                                <th style="text-align: center">Telefonos</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;"> </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left" id="addEmpresa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Empresa</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtNombre"><strong>Nombre</strong></label>
                                    <input type="text" maxlength="128" class="form-control round text-uppercase" id="txtNombre">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtRTN" style="text-align: center;"><strong>RTN</strong></label>
                                    <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control round" id="txtRTN">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtTelefono"><strong>Telefonos</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtTelefono">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtEmail"><strong>Email</strong></label>
                                    <input type="text" maxlength="128" class="form-control  round text-uppercase" id="txtEmail">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txtWEB"><strong>Web</strong></label>
                                    <input type="text" maxlength="32" class="form-control  round text-uppercase" id="txtWEB">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtDireccion"><strong>Direccion</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtDireccion" rows="3" maxlength="128"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_empresa()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left" id="updEmpresa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Empresa</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtNombre1"><strong>Nombre</strong></label>
                                    <input type="text" hidden="none" class="form-control" id="txtEmpId">
                                    <input type="text" maxlength="128" class="form-control round text-uppercase" id="txtNombre1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtRTN1" style="text-align: center;"><strong>RTN</strong></label>
                                    <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control round" id="txtRTN1">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtTelefono1"><strong>Telefonos</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtTelefono1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtEmail1"><strong>Email</strong></label>
                                    <input type="text" maxlength="128" class="form-control  round text-uppercase" id="txtEmail1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="txtWEB1"><strong>Web</strong></label>
                                    <input type="text" maxlength="32" class="form-control  round text-uppercase" id="txtWEB1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtDireccion1"><strong>Direccion</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtDireccion1" rows="3" maxlength="128"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="update_empresa()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>


    <!-- Script -->
    <script src="negocios/js/fcn_empresas.js?v=<?= uniqid(); ?>"></script>

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
            $('#addCliente').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addCliente').find('form');
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