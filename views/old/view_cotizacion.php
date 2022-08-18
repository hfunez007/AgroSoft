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

            var tipocot = <?php echo $_GET["tipo"] ?>;
            var param = {
                Actividad: "lst_cotizacionescreadas",
                "tipocot": tipocot
            };
            var tbl = "tbl_cotizacionescreadas";
            var model = "tree_cotizacion";

            _initDataTable(tbl, model, param);
        });
    </script>

    <section class="section">
        <div class="card">
            <?php if ($_GET["tipo"] == 0) {
                echo "<h3 class='card-header text-center'>Cotizaciones Creadas</h3>";
            }
            if ($_GET["tipo"] == 1) {
                echo '<h3 class="card-header text-center">Cotizaciones Para Revision</h3>';
                echo "<script> $('#btnagregarcot').hide(); </script>";
            }
            if ($_GET["tipo"] == 2) {
                echo '<h3 class="card-header text-center">Cotizaciones Aprobadas</h3>';
                echo "<script> $('#btnagregarcot').hide(); </script>";
            }
            ?>

            <div class="card-body">
                <div>
                    <button type="button" onclick="agregar_cotizacion()" id="btnagregarcot" class="btn btn-outline-primary round block mb-2">
                        <i class="fas fa-folder-plus"></i> Crear Nuevo
                    </button>
                </div>

                <div class="table-responsive">
                    <table class='table table-sm table-striped' id="tbl_cotizacionescreadas">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 10px;">No</th>
                                <th style="text-align: center">Fecha</th>
                                <th style="text-align: center">Codigo</th>
                                <th style="text-align: center">Cliente</th>
                                <th style="text-align: center">RTN</th>
                                <th style="text-align: center">Telefono</th>
                                <th style="text-align: center">Email</th>
                                <th style="text-align: center">Opcion</th>
                                <th style="text-align: center">Reporte</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;"> </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Script -->
    <script src="negocios/js/fcn_cotizacion.js?v=<?= uniqid(); ?>"></script>

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