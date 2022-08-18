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
                Actividad: "lst_clientes"
            };
            var tbl = "tbl_clientes";
            var model = "tree_clientes";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Contactos</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_pclientes()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addCliente">
                        <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_clientes">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">RTN</th>
                                <th style="text-align: center">Telefonos</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Contacto</th>
                                <th style="text-align: center">Tipo</th>
                                <th style="text-align: center">Direccion</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;"> </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left" id="addCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Contacto</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtnombre"><strong>Nombre</strong></label>
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtnombre">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtrtn" style="text-align: center;"><strong>RTN</strong></label>
                                    <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control round" id="txtrtn">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txttelefono"><strong>Telefonos</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txttelefono">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtemail"><strong>Email</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtemail">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtcontacto"><strong>Contacto</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtcontacto">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="cbotipo" style="text-align: center;"><strong>Tipo</strong></label>
                                    <select class="form-select round" id="cbotipo">
                                        <option value="1">CLIENTE</option>
                                        <option value="2">PROVEEDOR</option>
                                        <option value="3">Empleado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtdireccion"><strong>Direccion</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtdireccion" rows="3" maxlength="256"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_cliente()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left" id="updCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Contacto</h5>
                    </div>
                    <div class="modal-body">

                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtnombre1"><strong>Nombre</strong></label>
                                    <input type="text" hidden="none" class="form-control" id="txtcliid">
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtnombre1">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtrtn1" style="text-align: center;"><strong>RTN</strong></label>
                                    <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control round" id="txtrtn1">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txttelefono1"><strong>Telefonos</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txttelefono1">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="txtemail1"><strong>Email</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtemail1">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="txtcontacto1"><strong>Contacto</strong></label>
                                    <input type="text" maxlength="64" class="form-control  round text-uppercase" id="txtcontacto1">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="cbotipo1" style="text-align: center;"><strong>Tipo</strong></label>
                                    <select class="form-select round" id="cbotipo1">
                                        <option value="1">CLIENTE</option>
                                        <option value="2">PROVEEDOR</option>
                                        <option value="3">Empleado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtdireccion1"><strong>Direccion</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtdireccion1" rows="3" maxlength="256"></textarea>
                                </div>
                            </div>
                        </form>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="update_cliente()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Modificar -->
    </section>


    <!-- Script -->
    <script src="negocios/js/fcn_clientes.js?v=<?= uniqid(); ?>"></script>

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