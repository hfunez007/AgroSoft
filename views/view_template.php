<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    $user=$_SESSION['username'];
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
        <script src="negocios/js/fcn_initDataTable.js?v=<?=uniqid();?>"></script>
        <script>
            $(document).ready( function (){
                var param = { Actividad:"lst_recetas" };
                var tbl = "tbl_recetas";
                var model = "tree_recetas";
                _initDataTable(tbl,model,param);
            });
        </script>

        <section class="section">
                <div class="card">
                    <h3 class="card-header text-center">Listado de Recetas Medicas </h3>
                    <div class="card-body">
                         <div>
                            <button type="button" onclick="focus_recetas()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addReceta"> <i class="fas fa-folder-plus"></i> </button>
                        </div>
                        <table class='table table-sm table-striped' id="tbl_recetas">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10px;">No</th>
                                    <th style="text-align: center">Nombre</th>
                                    <th style="text-align: center">Fecha</th>
                                    <th style="text-align: center">Edad</th>
                                    <th style="text-align: center">Opcion</th>
                                    <th style="text-align: center">Reporte</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 15px;"> </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Modal Agregar-->
                <div class="modal fade text-left modal-borderless" id="addReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Receta</h5>
                            </div>
                            <div class="modal-body">
                                
                                <form class="form">

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="txtnombres20"><strong>Nombre</strong></label>
                                            <input type="text"  hidden="none" class="form-control" id="txtid20">
                                            <input type="text" onKeyPress="return soloLetras(event)" maxlength="64" class="form-control  round text-uppercase" id="txtnombres20">  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6" >
                                            <label for="txtedad20" style="text-align: center;"><strong>Edad</strong></label>
                                            <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control  round" id="txtedad20"> 
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="dtfecha20"><strong>Fecha</strong></label>
                                            <input type="date" maxlength="32" class="form-control  round" id="dtfecha20" value="<?php date_default_timezone_set('America/Tegucigalpa'); echo date('Y-m-d');?>">
                                        </div>      
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="txtreceta20"><strong>Receta</strong></label>
                                            <textarea class="form-control  round text-uppercase" id="txtreceta20" rows="3" maxlength="256"></textarea>                                  
                                        </div>
                                    </div>

                                </form>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                                </button> <button type="button" class="btn btn-primary round ml-1" onclick="insertar_receta()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Modal Agregar -->

                <!-- Modal Modificar-->
                <div class="modal fade text-left modal-borderless" id="updReceta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Agregar Receta</h5>
                            </div>
                            <div class="modal-body">
                                
                                <form class="form">

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="txtnombres21"><strong>Nombre</strong></label>
                                            <input type="text"  hidden="none" class="form-control  round" id="txtid21">
                                            <input type="text" onKeyPress="return soloLetras(event)" maxlength="64" class="form-control text-uppercase" id="txtnombres21">  
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6" >
                                            <label for="txtedad21" style="text-align: center;"><strong>Edad</strong></label>
                                            <input type="text" onKeyPress="return soloNumeros(event)" maxlength="32" class="form-control  round" id="txtedad21"> 
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="dtfecha21"><strong>Fecha</strong></label>
                                            <input type="date" maxlength="32" class="form-control  round" id="dtfecha21" value="<?php date_default_timezone_set('America/Tegucigalpa'); echo date('Y-m-d');?>">
                                        </div>      
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="txtreceta21"><strong>Receta</strong></label>
                                            <textarea class="form-control round text-uppercase" id="txtreceta21" rows="3" maxlength="256"></textarea>                                  
                                        </div>
                                    </div>

                                </form>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-primary round" data-bs-dismiss="modal"><i class="bx bx-x d-block d-sm-none"></i> <span class="d-none d-sm-block">Cerrar</span>

                                </button> <button type="button" class="btn btn-primary round ml-1" onclick="update_receta()"><i class="bx bx-check d-block d-sm-none"></i><span class="d-none d-sm-block">Guardar</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Modal Modificar -->
            </section>

        <!-- Script -->
        <script src="negocios/js/fcn_recetas.js?v=<?=uniqid();?>"></script>

        <!-- Funcion solo de Numeros -->
        <script>
            function soloNumeros(e)
            {
                var key = window.Event ? e.which : e.keyCode
                return (key >= 48 && key <= 57)
            }
        </script>

        <!-- Funcion solo de Letras -->
        <script>
            function soloLetras(e)
            {
                key = e.keyCode || e.which;
                tecla = String.fromCharCode(key).toLowerCase();
                letras = " áéíóúabcdefghijklmnñopqrstuvwxyz";
                especiales = "8-37-39-46";

                tecla_especial = false
                for(var i in especiales)
                {
                    if(key == especiales[i])
                    {
                        tecla_especial = true;
                        break;
                    }
                }
            if(letras.indexOf(tecla)==-1 && !tecla_especial)
            {
                return false;
            }
        }
        </script>
    </body>
</html>