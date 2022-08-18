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
    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Detalle de Cuenta Por Cobrar</h3>
            <div class="card-body">
                <!-- Encabezado con informacion -->
                <div class="row">
                    <div class="col-md-6 col-xl-6">
                        <!-- style="height: 185px; overflow-y: scroll" -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class='table table-sm table-striped'>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="far fa-calendar-alt"></i> Fecha</th>
                                            <td> <label id="lblFecha">---</label> </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="far fa-file-code"></i> Codigo</th>
                                            <td> <label id="lblCodigo">---</label> </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="far fa-user"></i> Cliente</th>
                                            <td> <label id="lblCliente">---</label> </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6 col-xl-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class='table table-sm table-striped'>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="fas fa-layer-group"></i> Tipo Factura</th>
                                            <td> <label id="lblTipoFactura">---</label> </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="far fa-file-code"></i> Tipo Codigo</th>
                                            <td> <label id="lblTipoCodigo">---</label> </td>
                                        </tr>
                                        <tr>
                                            <th class=" text-muted pr-4" scope="row"><i class="far fa-user"></i> Empresa</th>
                                            <td> <label id="lblEmpresa">---</label> </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 text-center">
                        <label for="txtTotal">Total</label>
                        <input type="text" hidden="none" class="form-control" id="txtFactId">
                        <input type="text" maxlength="32" class="form-control round text-uppercase" id="txtTotal" pattern="^\d*(\.\d{0,2})?$" readonly>
                    </div>
                    <div class="form-group col-md-4 text-center">
                        <label for="txtPagado">Acumulado</label>
                        <input type="text" maxlength="32" class="form-control round text-uppercase" id="txtPagado" pattern="^\d*(\.\d{0,2})?$" readonly>
                    </div>
                    <div class="form-group col-md-4 text-center">
                        <label for="txtPendiente">Pendiente</label>
                        <input type="text" hidden="none" class="form-control" id="txtPendiente1">
                        <input type="text" maxlength="32" class="form-control round text-uppercase" id="txtPendiente" pattern="^\d*(\.\d{0,2})?$" readonly>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text"><b>Pagos</b></div>
                </div>

                <div class="row">
                    <div class="form-group col-md-2 text-center">
                        <label for="dtFechapago">Fecha</label>
                        <input type="date" maxlength="32" class="form-control round text-center text-uppercase" id="dtFechapago" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                        echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group col-md-3 text-center">
                        <label for="cboTipoTago">Tipo</label>
                        <select class="form-select round" id="cboTipoTago">
                            <option value="1">EFECTIVO</option>
                            <option value="2">TARJETA</option>
                            <option value="3">TRANSFERENCIA</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 text-center">
                        <label for="txtRecibo">Recibo</label>
                        <input type="text" maxlength="16" class="form-control round text-uppercase" id="txtRecibo">
                    </div>
                    <div class="form-group col-md-3 text-center">
                        <label for="txtValorPago">Valor</label>
                        <div class="input-group mb-3">
                            <input type="text" maxlength="10" class="form-control round text-uppercase" id="txtValorPago" pattern="^\d*(\.\d{0,2})?$">
                            <button type="button" class="btn btn-primary round ml-1" onclick="insertar_pago()" id="btnInsertarPago">
                                Insertar <i class="fas fa-angle-double-down"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="t_pagos">
                        <thead>
                            <tr>
                                <th style="text-align: center">Fecha</th>
                                <th style="text-align: center">Tipo</th>
                                <th style="text-align: center">Recibo</th>
                                <th style="text-align: center">Valor</th>
                            </tr>
                        </thead>
                    </table>
                    <!-- <div class="d-grid gap-2">
                        <button type="button" id="btnrecibo" class="btn btn-primary me-1 mb-1 round" onclick="rpt_recibo()">Recibo</button>
                    </div> -->
                </div>

            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {
            fcn_datosgenerales(<?php echo $_GET["id"] ?>);
            fcn_listaPagosFacturas(<?php echo $_GET["id"] ?>);
        });
    </script>

    <!-- Script -->
    <script src="negocios/js/fcn_facturas.js?v=<?= uniqid(); ?>"></script>

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