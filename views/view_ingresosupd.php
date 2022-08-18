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
            <h3 class="card-header text-center">Actualizar Ingreso de Productos</h3>
            <div class="card-body">
                <form class="form">
                    <!-- Paciente y Fecha -->
                    <div class="row">

                        <div class="form-group col-md-2 text-center">
                            <label for="dtfechaingreso1">Fecha</label>
                            <input type="date" maxlength="32" class="form-control round text-center" id="dtfechaingreso1" value="<?php date_default_timezone_set('America/Tegucigalpa');
                                                                                                                                    echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txtcodigo1"><strong>Codigo</strong></label>
                            <input type="text" hidden="none" class="form-control" id="txtingresoid">
                            <input type="text" class="form-control round text-uppercase" id="txtcodigo1">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="cbotipo1" style="text-align: center;"><strong>Tipo</strong></label>
                            <select class="form-select round" id="cbotipo1">
                                <option value="1">CONTADO</option>
                                <option value="2">CREDITO</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6 text-center">
                            <label for="cbocliente1">Proveedor</label>
                            <select class="form-select round" id="cbocliente1" name="cbocliente1">
                                <?php
                                include "../datos/conexion_mysql.php";
                                $query = "SELECT cli_id, cli_nombre, cli_rtn FROM t_clientes WHERE cli_tipo = 2;";
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
                        <div class="divider-text"><button type="button" id="btngenerar1" class="btn btn-primary me-1 mb-1 round" onclick="insertar_ingreso()">Generar Ingreso</button></div>
                    </div>

                    <!-- Detalle de cotizacion -->
                    <div class="row" id="datos_ingreso1">
                        <div class="form-group col-md-6">
                            <label for="cboproducto">Producto</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control round" id="cboproducto" list="lstproducto" placeholder="Ingrese Nombre de Producto..." autocomplete="off" onchange="ObtenerPrecio();">
                                <datalist id="lstproducto">
                                    <?php
                                    include "../datos/conexion_mysql.php";
                                    $query = " SELECT a.prod_id, concat(a.nombre_prod,' ( ',b.medida_nombre, ' )') as Producto, a.prod_codigo 
                                               FROM t_productos a INNER JOIN t_unidadmedida b on a.medida_id = b.medida_id; ";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo " <option value='" . $row[1] . "' data-value=" . $row[0] . ">" . $row[2] . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </datalist>
                                <div class="invalid-feedback">Ingrese Nombre de Producto</div>

                                <button type="button" class="btn btn-primary round ml-1" onclick="focus_producto1()" data-bs-toggle="modal" data-bs-target="#addProducto1">
                                    <i class="fas fa-folder-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group col-md-2 text-center">
                            <label for="txtPrecio">Precio</label>
                            <input type="text" maxlength="15" class="form-control round text-uppercase" id="txtPrecio" pattern="^\d*(\.\d{0,2})?$">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="txtcantidad">Cantidad</label>
                            <div class="input-group mb-3">
                                <input type="text" maxlength="10" class="form-control round text-uppercase" id="txtcantidad" pattern="^\d*(\.\d{0,2})?$">

                                <button type="button" class="btn btn-primary round ml-1" onclick="instertar_detingreso()">
                                    Insertar <i class="fas fa-angle-double-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- tabla de detalle -->
                    <div class="table-responsive" id="tabla_ingreso1">
                        <table class="table table-sm table-striped" id="tbl_detalleingreso">
                            <thead>
                                <tr>
                                    <!-- <th class="text-center" style="width: 10px;">No</th> -->
                                    <th style="text-align: center">Producto</th>
                                    <th style="text-align: center">Precio</th>
                                    <th style="text-align: center">Cantidad</th>
                                    <th style="text-align: center">Total</th>
                                    <th style="text-align: center">Opcion</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-light-secondary me-1 mb-1 round" onclick="listado_ingresos()">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Agregar Productos-->
        <div class="modal fade text-left" id="addProducto1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="txtCodigo1">Codigo del Producto</label>
                                    <input type="text" class="form-control round" id="txtCodigo1" maxlength="32" autocomplete="on">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="txtProducto1">Nombre</label>
                                    <input type="text" class="form-control round text-uppercase" id="txtProducto1" placeholder="" maxlength="128">
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="cboMarca1" style="text-align: center;"><strong>Marca</strong></label>
                                    <select class="form-select round" id="cboMarca1">
                                        <option value="99">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $query = "SELECT marca_id, marca_nombre FROM t_marca;";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="cboMedida1">SKU</label>
                                    <select class="form-select round" id="cboMedida1">
                                        <option value="99">SELECCIONAR</option>
                                        <?php
                                        include "../datos/conexion_mysql.php";
                                        $cod_rol = $_SESSION['codusu'];
                                        $query = "SELECT * FROM  t_unidadmedida";

                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo " <option value=" . $row[0] . ">" . $row[1] . "</option>";
                                        }
                                        mysqli_close($conn);
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="txtPrecioCompra1">Precio Compra </label>
                                    <input type="text" class="form-control round" id="txtPrecioCompra1" maxlength="15" pattern="^\d*(\.\d{0,2})?$">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="txtCantMinima1">Cantidad Minima</label>
                                    <input type="text" class="form-control round" id="txtCantMinima1" onKeyPress="return soloNumeros(event)" maxlength="13">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="txtPrecioVenta1">Precio Venta</label>
                                    <input type="text" class="form-control round" id="txtPrecioVenta1" maxlength="15" pattern="^\d*(\.\d{0,2})?$">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cboImpuesto1" style="text-align: center;"><strong>Tipo de Impuesto</strong></label>
                                    <select class="form-select round" id="cboImpuesto1">
                                        <option value="99">SELECCIONAR</option>
                                        <option value="0">EXENTO</option>
                                        <option value="15">15%</option>
                                        <option value="18">18%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtComentario1"><strong>Comentario</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtComentario1" rows="3" maxlength="256"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_producto1()" data-bs-dismiss="modal"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_ingresos.js?v=<?= uniqid(); ?>"></script>

    <!-- Deshabilitar combo de paciente -->
    <script>
        $(document).ready(function() {
            document.getElementById("dtfechaingreso1").disabled = true;
            document.getElementById("txtcodigo1").disabled = true;
            document.getElementById("cbotipo1").disabled = true;
            document.getElementById("cbocliente1").disabled = true;
            document.getElementById("btngenerar1").disabled = true;
            showmodal_ingreso(<?php echo $_GET["id"] ?>);
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