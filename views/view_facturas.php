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
                Actividad: "lst_facturas"
            };
            var tbl = "tbl_facturas";
            var model = "tree_facturas";

            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <h3 class='card-header text-center'>Listado de Facturas</h3>
            <div class="card-body">
                <div>
                    <button type="button" onclick="agregar_facturas()" id="btnAgregarFactura" class="btn btn-outline-primary round block mb-2">
                        <i class="fas fa-folder-plus"></i> Nuevo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_facturas">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Codigo</th>
                                <th style="text-align: center">Fecha</th>
                                <th style="text-align: center">Cliente</th>
                                <th style="text-align: center">Tipo</th>
                                <th style="text-align: center">Total</th>
                                <th style="text-align: center">Pagado</th>
                                <th style="text-align: center">Pendiente</th>
                                <th style="text-align: center">Opcion</th>
                                <!-- <th style="text-align: center">Reporte</th> -->
                            </tr>
                        </thead>
                        <tbody style="font-size: 13px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

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
</body>

</html>