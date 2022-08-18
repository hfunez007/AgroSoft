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
    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Cotización</h3>
            <div class="card-body">
                <form class="form">
                    <!-- Fecha, codigo y cliente -->
                    <div class="row">
                        <div class="form-group col-md-2 text-center">
                            <label for="dtfechacot">Fecha</label>
                            <input type="text" hidden="none" class="form-control" id="txtcotid">
                            <input type="date" maxlength="32" class="form-control round text-center" id="dtfechacot" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                            echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txtcodigo"><strong>Codigo</strong></label>
                            <input type="text" class="form-control  round text-uppercase" id="txtcodigo" readonly>
                        </div>

                        <div class="form-group col-md-8">
                            <label for="cbocategoria">Cliente</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control round" id="cbocliente" list="lstcliente" placeholder="Ingrese Nombre de Cliente..." autocomplete="off">
                                <datalist id="lstcliente">
                                    <?php
                                    include "../datos/conexion_mysql.php";
                                    $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 0;";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </datalist>
                                <div class="invalid-feedback">Ingrese Nombre de Cliente</div>

                                <button type="button" class="btn btn-primary round ml-1" id="btncliente" data-bs-toggle="modal" data-bs-target="#addCliente2" onclick="focus_pclientes1();">
                                    <i class="fas fa-user-plus"> </i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Datos nuevos -->
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="txtProceso">Proceso</label>
                            <input type="text" class="form-control round" id="txtProceso" maxlength="128">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dtFechaApertura">Apertura Fecha</label>
                            <input type="datetime-local" maxlength="32" class="form-control round text-center" id="dtFechaApertura" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                            ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txtValidez">¿Oferta Valida?</label>
                            <input type="text" class="form-control round" id="txtValidez" maxlength="3" onKeyPress="return soloNumeros(event)">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txtEntrega">Tiempo de Entrega</label>
                            <input type="text" class="form-control round" id="txtEntrega" maxlength="3" onKeyPress="return soloNumeros(event)">
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-text"><button type="button" id="btngenerar" class="btn btn-primary me-1 mb-1 round" onclick="insertar_cotizacion()">Generar Cotizacion</button></div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-light-secondary me-1 mb-1 round" onclick="listado_presupuesto()">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left" id="addCliente2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Cliente</h5>
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
                                        <option value="0">CLIENTE</option>
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

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_cliente2()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_cotizacion.js?v=<?= uniqid(); ?>"></script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addCliente2').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addCliente2').find('form');
                $formulario[0].reset();
            });
        })
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