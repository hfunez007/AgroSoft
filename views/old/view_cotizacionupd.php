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

    <script>
        $('#tbl_detallecotizacion1').on('blur', 'td[contenteditable]', update1);

        function update1() {
            debugger;
            var cantModify = $(this).attr("id1");
            var precioModify = $(this).attr("id2");
            var valor = $(this).text();

            // Si se esta modificando cantidad
            if (precioModify == undefined) {
                update_cantidadCotizacionDetalle(cantModify + ":" + valor);
            }
            // Si se esta modificando cantidad
            if (cantModify == undefined) {
                update_precioCotizacionDetalle(precioModify + ":" + valor);
            }
        }
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Cotización</h3>
            <div class="card-body">
                <form class="form">
                    <!-- Paciente y Fecha -->
                    <div class="row">
                        <div class="form-group col-md-2 text-center">
                            <label for="dtfechacot1">Fecha</label>
                            <input type="text" hidden="none" class="form-control" id="txtcotid1">
                            <input type="date" maxlength="32" class="form-control round text-center" id="dtfechacot1" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="txtcodigo1"><strong>Codigo</strong></label>
                            <input type="text" class="form-control  round text-uppercase" id="txtcodigo1" readonly>
                        </div>
                        <div class="form-group col-md-8 text-center">
                            <label for="cbocliente1">Cliente</label>
                            <select class="form-select round" id="cbocliente1" name="cbocliente1">
                                <?php
                                include "../datos/conexion_mysql.php";
                                $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 0;";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Datos nuevos -->
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="txtProceso1">Proceso</label>
                            <input type="text" class="form-control round" id="txtProceso1" maxlength="128">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="dtFechaApertura1">Apertura Fecha</label>
                            <input type="datetime-local" maxlength="32" class="form-control round text-center" id="dtFechaApertura1" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                            ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txtValidez1">¿Oferta Valida?</label>
                            <input type="text" class="form-control round" id="txtValidez1" maxlength="3" onKeyPress="return soloNumeros(event)">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="txtEntrega1">Tiempo de Entrega</label>
                            <input type="text" class="form-control round" id="txtEntrega1" maxlength="3" onKeyPress="return soloNumeros(event)">
                        </div>
                    </div>

                    <div class="divider">
                        <div class="divider-text"><button type="button" id="btngenerar1" class="btn btn-primary me-1 mb-1 round" onclick="insertar_cotizacion()">Generar Cotizacion</button></div>
                    </div>

                    <!-- Detalle de cotizacion -->
                    <div class="row" id="datos_cotizacion1">
                        <div class="form-group col-md-8">
                            <label for="cboproducto1">Producto</label>
                            <input type="text" class="form-control round" id="cboproducto1" list="lstproducto1" placeholder="Ingrese Nombre de Producto..." autocomplete="off" onchange="fcn_buscarPrecio1();">
                            <datalist id="lstproducto1">
                                <?php
                                include "../datos/conexion_mysql.php";
                                $query = "SELECT prod_id, concat(prod_codigo,' - ',nombre_prod) as Producto, format(prod_precioventa,2) as Precio FROM t_productos;";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
                                }
                                mysqli_close($conn);
                                ?>
                            </datalist>
                            <div class="invalid-feedback">Ingrese Nombre de Producto</div>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txtPrecio1">precio</label>
                            <input type="text" maxlength="18" class="form-control round text-uppercase" id="txtPrecio1" pattern="^\d*(\.\d{0,2})?$">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txtcantidad1">Cantidad</label>
                            <div class="input-group mb-2">
                                <input type="text" maxlength="5" class="form-control round text-uppercase" id="txtcantidad1" pattern="^\d*(\.\d{0,2})?$">

                                <button type="button" class="btn btn-primary round ml-1" onclick="instertar_detcotizacion1();">
                                    <i class="fas fa-angle-double-down"></i> Insertar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- tabla de detalle -->
                    <div class="table-responsive" id="tabla_cotizacion1">
                        <table class="table table-sm table-striped" id="tbl_detallecotizacion1">
                            <thead>
                                <tr>
                                    <th style="text-align: center">Producto</th>
                                    <th style="text-align: center">Cantidad</th>
                                    <th style="text-align: center">Precio</th>
                                    <th style="text-align: center">ISV %</th>
                                    <th style="text-align: center">SubTotal</th>
                                    <th style="text-align: center">ISV</th>
                                    <th style="text-align: center">Total</th>
                                    <th style="text-align: center">Opcion</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-light-secondary me-1 mb-1 round" onclick="listado_cotizacion()">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>

    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_cotizacion.js?v=<?= uniqid(); ?>"></script>

    <!-- Deshabilitar combo de paciente -->
    <script>
        $(document).ready(function() {
            document.getElementById("dtfechacot1").disabled = true;
            document.getElementById("txtcodigo1").disabled = true;
            document.getElementById("cbocliente1").disabled = true;
            document.getElementById("btngenerar1").disabled = true;

            document.getElementById("txtProceso1").disabled = true;
            document.getElementById("dtFechaApertura1").disabled = true;
            document.getElementById("txtValidez1").disabled = true;
            document.getElementById("txtEntrega1").disabled = true;

            showmodal_cotizacion(<?php echo $_GET["id"] ?>);
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