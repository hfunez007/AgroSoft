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
            <h3 class="card-header text-center">Actualizar Factura</h3>
            <div class="card-body">
                <form class="form">
                    <!-- Paciente y Fecha -->
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="txtCodigo1"><strong>Codigo</strong></label>
                            <input type="text" hidden="none" class="form-control" id="txtFacturaId">
                            <input type="text" class="form-control  round text-uppercase" id="txtCodigo1" readonly>
                        </div>
                        <div class="form-group col-md-3 text-center">
                            <label for="dtFechaFactura1">Fecha</label>
                            <input type="date" maxlength="32" class="form-control round text-center" id="dtFechaFactura1" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                    echo date('Y-m-d'); ?>" readonly>
                        </div>
                        <div class="form-group col-md-6 text-center">
                            <label for="cbocliente1">Cliente</label>
                            <select class="form-select round" id="cbocliente1" name="cbocliente1">
                                <?php
                                include "../datos/conexion_mysql.php";
                                $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 1;";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="cboTipoFactura1" style="text-align: center;"><strong>Tipo Factura</strong></label>
                            <select class="form-select round" id="cboTipoFactura1">
                                <option value="1">CONTADO</option>
                                <option value="2">CREDITO</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="cboTipoRecibo1" style="text-align: center;"><strong>Tipo Codigo</strong></label>
                            <select class="form-select round" id="cboTipoRecibo1">
                                <option value="1">RECIBO</option>
                                <option value="2">CAI</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cboEmpresa1" style="text-align: center;"><strong>Empresa</strong></label>
                            <select class="form-select round" id="cboEmpresa1">
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
                            <button type="button" id="btngenerar1" class="btn btn-primary me-1 mb-1 round" onclick="InsertarPagosFactura()">
                                Ingresar Pagos
                            </button>
                        </div>
                    </div>

                    <!-- Detalle de Factura -->
                    <div class="row" id="datos_ingreso1">
                        <div class="form-group col-md-5">
                            <label for="cboproducto">Producto</label>
                            <input type="text" class="form-control round" id="cboproducto" list="lstproducto" placeholder="Ingrese Nombre de Producto..." autocomplete="off" onchange="ObtenerPrecio();">
                            <datalist id="lstproducto">
                                <?php
                                include "../datos/conexion_mysql.php";
                                $query = "SELECT a.prod_id, concat(a.nombre_prod,' ( ',b.medida_nombre, ' )') as Producto, a.prod_codigo 
                                              FROM t_productos a INNER JOIN t_unidadmedida b on a.medida_id = b.medida_id;";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </datalist>
                            <div class="invalid-feedback">Ingrese Nombre de Producto</div>
                        </div>

                        <div class="form-group col-md-2 text-center">
                            <label for="txtDisponible">Disponible</label>
                            <input type="text" maxlength="15" class="form-control round text-uppercase" id="txtDisponible">
                        </div>

                        <div class="form-group col-md-2 text-center">
                            <label for="txtPrecio">Precio</label>
                            <input type="text" maxlength="15" class="form-control round text-uppercase" id="txtPrecio" pattern="^\d*(\.\d{0,2})?$">
                        </div>

                        <div class="form-group col-md-3">
                            <label for="txtCantidad">Cantidad</label>
                            <div class="input-group mb-3">
                                <input type="text" maxlength="10" class="form-control round text-uppercase" id="txtCantidad" pattern="^\d*(\.\d{0,2})?$">

                                <button type="button" class="btn btn-primary round ml-1" onclick="instertar_detfactura()">
                                    Insertar <i class="fas fa-angle-double-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- tabla de detalle -->
                    <div class="table-responsive" id="tabla_ingreso1">
                        <table class="table table-sm table-striped" id="tbl_detalleFactura">
                            <thead>
                                <tr>
                                    <!-- <th class="text-center" style="width: 10px;">No</th> -->
                                    <th style="text-align: center">Producto</th>
                                    <th style="text-align: center">Tipo ISV</th>
                                    <th style="text-align: center">Precio</th>
                                    <th style="text-align: center">Cantidad</th>
                                    <th style="text-align: center">ISV</th>
                                    <th style="text-align: center">Total</th>
                                    <th style="text-align: center">Opcion</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-light-secondary me-1 mb-1 round" onclick="listado_ingresos()">Cerrar</button>
                        <!-- <button type="button" class="btn btn-primary me-1 mb-1 round" onclick="insertar_historialmedico()">Guardar</button> -->
                    </div>
                </form>
            </div>
        </div>

    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_facturas.js?v=<?= uniqid(); ?>"></script>

    <!-- Deshabilitar combo de paciente -->
    <script>
        $(document).ready(function() {
            document.getElementById("cbocliente1").disabled = true;
            document.getElementById("cboTipoFactura1").disabled = true;
            document.getElementById("cboTipoRecibo1").disabled = true;
            document.getElementById("cboEmpresa1").disabled = true;
            // document.getElementById("btngenerar1").disabled = true;
            document.getElementById("txtDisponible").disabled = true;
            $('#cboproducto').trigger('focus');
            showmodal_factura(<?php echo $_GET["id"] ?>);
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