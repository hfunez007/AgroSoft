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
            <h3 class="card-header text-center">Agregar Factura</h3>
            <div class="card-body">
                <form class="form">
                    <!-- Paciente y Fecha -->
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="txtCodigo"><strong>Codigo</strong></label>
                            <input type="text" class="form-control  round text-uppercase" id="txtCodigo" readonly>
                        </div>
                        <div class="form-group col-md-3 text-center">
                            <label for="dtFechaFactura">Fecha</label>
                            <input type="date" maxlength="32" class="form-control round text-center" id="dtFechaFactura" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cbocliente">Cliente</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control round" id="cbocliente" list="lstcliente" placeholder="Ingrese Nombre de Cliente..." autocomplete="off">
                                <datalist id="lstcliente">
                                    <?php
                                    include "../datos/conexion_mysql.php";
                                    $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 1;";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </datalist>
                                <div class="invalid-feedback">Ingrese Nombre de Cliente</div>

                                <button type="button" class="btn btn-primary round ml-1" onclick="focus_pclientes2()" data-bs-toggle="modal" data-bs-target="#addCliente3" id="btncliente">
                                    <i class="fas fa-folder-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cboTipoFactura" style="text-align: center;"><strong>Tipo Factura</strong></label>
                            <select class="form-select round" id="cboTipoFactura">
                                <option value="1">CONTADO</option>
                                <option value="2">CREDITO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cboTipoRecibo" style="text-align: center;"><strong>Tipo Codigo</strong></label>
                            <select class="form-select round" id="cboTipoRecibo">
                                <option value="1">RECIBO</option>
                                <option value="2">CAI</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cboEmpresa" style="text-align: center;"><strong>Empresa</strong></label>
                            <select class="form-select round" id="cboEmpresa">
                                <option value="99">SELECCIONAR</option>
                                <?php
                                include "../datos/conexion_mysql.php";
                                $cod_rol = $_SESSION['codusu'];
                                $query = "SELECT emp_id, concat(emp_nombre, ' - ', emp_rtn) as Nombre FROM t_empresas;";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-text">
                            <button type="button" id="btngenerar" class="btn btn-primary me-1 mb-1 round" onclick="insertar_factura()">
                                Generar Factura
                            </button>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-light-secondary me-1 mb-1 round" onclick="listado_facturas()">
                            Cerrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar cliente-->
        <div class="modal fade text-left" id="addCliente3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
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
                                        <option value="1">CLIENTE</option>
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

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_cliente3()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->


    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_facturas.js?v=<?= uniqid(); ?>"></script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addCliente3').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addCliente3').find('form');
                $formulario[0].reset();
            });
        })
    </script>

    <!-- Focus -->
    <script>
        $(document).ready(function() {
            $('#cbocliente').trigger('focus');
        });
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