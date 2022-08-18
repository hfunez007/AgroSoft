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
                Actividad: "listaproductos"
            };
            var tbl = "tbl_productos";
            var model = "tree_productos";
            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class="card-header text-center">Listado de Productos</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="focus_producto()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addProducto"> <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>
                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_productos">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Codigo</th>
                                <th style="text-align: center">Producto</th>
                                <th style="text-align: center">SKU</th>
                                <th style="text-align: center">Marca</th>
                                <th style="text-align: center">Compra</th>
                                <th style="text-align: center">Venta</th>
                                <th style="text-align: center">Disp.</th>
                                <th style="text-align: center">Min.</th>
                                <th style="text-align: center">Opcion</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Agregar-->
        <div class="modal fade text-left" id="addProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="txtCodigo">Codigo del Producto</label>
                                    <input type="text" class="form-control round" id="txtCodigo" maxlength="32" autocomplete="on">
                                </div>

                                <div class="form-group col-md-8">
                                    <label for="txtProducto">Nombre</label>
                                    <input type="text" class="form-control round text-uppercase" id="txtProducto" placeholder="" maxlength="128">
                                </div>
                            </div>

                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="cboMarca" style="text-align: center;"><strong>Marca</strong></label>
                                    <div class="input-group mb-3">
                                        <select class="form-select round" id="cboMarca">
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

                                        <button type="button" class="btn btn-primary round ml-1" onclick="focus_marca1()" data-bs-toggle="modal" data-bs-target="#addMarca1">
                                            <i class="fas fa-folder-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="cboMedida">SKU</label>
                                    <div class="input-group mb-3">
                                        <select class="form-select round" id="cboMedida">
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

                                        <button type="button" class="btn btn-primary round ml-1" onclick="focus_medida1()" data-bs-toggle="modal" data-bs-target="#addMedida1">
                                            <i class="fas fa-folder-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="txtPrecioCompra">Precio Compra </label>
                                    <input type="text" class="form-control round" id="txtPrecioCompra" maxlength="15" pattern="^\d*(\.\d{0,2})?$">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="txtCantMinima">Cantidad Minima</label>
                                    <input type="text" class="form-control round" id="txtCantMinima" onKeyPress="return soloNumeros(event)" maxlength="13">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="txtPrecioVenta">Precio Venta</label>
                                    <input type="text" class="form-control round" id="txtPrecioVenta" maxlength="15" pattern="^\d*(\.\d{0,2})?$">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="cboImpuesto" style="text-align: center;"><strong>Tipo de Impuesto</strong></label>
                                    <select class="form-select round" id="cboImpuesto">
                                        <option value="99">SELECCIONAR</option>
                                        <option value="0">EXENTO</option>
                                        <option value="15">15%</option>
                                        <option value="18">18%</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtComentario"><strong>Comentario</strong></label>
                                    <textarea class="form-control round text-uppercase" id="txtComentario" rows="3" maxlength="256"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_producto()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Modificar-->
        <div class="modal fade text-left" id="updProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Producto</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="txtCodigo1">Codigo del Producto</label>
                                    <input type="text" hidden="none" class="form-control  round" id="idprod">
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
                                    <label for="cboMedida1">MEDIDA</label>
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
                        <button type="button" class="btn btn-primary round ml-1" onclick="update_producto()">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Modificar -->

        <!-- Modal Agregar Marca-->
        <div class="modal fade text-left" id="addMarca1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Marca</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtnombreMarca300"><strong>Nombre</strong></label>
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtnombreMarca300">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_marca1()" data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->

        <!-- Modal Agregar Medida-->
        <div class="modal fade text-left" id="addMedida1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Medida</h5>
                    </div>
                    <div class="modal-body">
                        <form class="form">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="txtMedida"><strong>Nombre</strong></label>
                                    <input type="text" maxlength="64" class="form-control round text-uppercase" id="txtMedida">
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>
                        </button>

                        <button type="button" class="btn btn-primary round ml-1" onclick="insertar_medida1()" data-bs-dismiss="modal">
                            <i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal Agregar -->
    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_productos.js?v=<?= uniqid(); ?>"></script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#addproducto').on('hidden.bs.modal', function(e) {
                const $formulario = $('#addproducto').find('form');
                $formulario[0].reset();
            });
        })
    </script>

    <!-- Limpiar inputs de fomulario -->
    <script>
        $(function() {
            $('#updproducto').on('hidden.bs.modal', function(e) {
                const $formulario = $('#updproducto').find('form');
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