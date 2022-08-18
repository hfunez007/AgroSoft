<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    $user=$_SESSION['username'];
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
        <!-- <script src="negocios/js/fcn_initDataTable.js?v=<?=uniqid();?>"></script>
        <script>
            $(document).ready( function (){
                var param = { Actividad:"lst_pacientes" };
                var tbl = "tbl_pacientes";
                var model = "tree_pacientes";
                _initDataTable(tbl,model,param);
            });
        </script> -->

        <section class="section">
                <div class="card">
                    <h3 class="card-header text-center">Detalles del Usuario</h3>
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-4 col-xl-3">
                                <div class="card mb-3">
                                    <!-- <div class="card-header text-center">
                                        <h5 class="card-title mb-0">Detalles del Usuario</h5>
                                    </div> -->

                                    <div class="card-body text-center">
                                        <img src="assets/images/avatar/avatar_hombre2.png" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128" />
                                        <h5 class="card-title mb-0">Ileana Sabillon</h5>
                                        <div class="text-muted mb-2">Cirujano Dentista</div>

                                        <div>
                                            <!-- <a class="btn btn-primary btn-sm" href="#">Follow</a>
                                            <a class="btn btn-primary btn-sm" href="#"><span data-feather="message-square"></span> Message</a> -->
                                            <!-- <button type="button" onclick="focus_pacientes()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addPaciente"> 
                                            <i class="fas fa-folder-plus"></i> Aplicar Cambios
                                            </button> -->
                        
                                        </div>
                                    </div>

                                    <!-- <hr class="my-0" />
                                    <div class="card-body">
                                        <h5 class="h6 card-title">Skills</h5>
                                        <a href="#" class="badge bg-primary me-1 my-1">HTML</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">JavaScript</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">Sass</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">Angular</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">Vue</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">React</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">Redux</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">UI</a>
                                        <a href="#" class="badge bg-primary me-1 my-1">UX</a>
                                    </div> -->

                                    <!-- <hr class="my-0" />
                                    <div class="card-body">
                                        <h5 class="h6 card-title">About</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-1"><span data-feather="home" class="feather-sm me-1"></span> Lives in <a href="#">San Francisco, SA</a></li>

                                            <li class="mb-1"><span data-feather="briefcase" class="feather-sm me-1"></span> Works at <a href="#">GitHub</a></li>
                                            <li class="mb-1"><span data-feather="map-pin" class="feather-sm me-1"></span> From <a href="#">Boston</a></li>
                                        </ul>
                                    </div> -->

                                    <!-- <hr class="my-0" />
                                    <div class="card-body">
                                        <h5 class="h6 card-title">Elsewhere</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-1"><a href="#">staciehall.co</a></li>
                                            <li class="mb-1"><a href="#">Twitter</a></li>
                                            <li class="mb-1"><a href="#">Facebook</a></li>
                                            <li class="mb-1"><a href="#">Instagram</a></li>
                                            <li class="mb-1"><a href="#">LinkedIn</a></li>
                                        </ul>
                                    </div> -->

                                </div>
                            </div>

                            <div class="col-md-8 col-xl-9">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"> </h5>
                                    </div>
                                    <div class="card-body h-100">

                                        
                                        


                                        

                                        

                                        

                                        
                                        <div class="d-grid">
                                            <button type="button" onclick="focus_pacientes()" class="btn btn-outline-primary round block mb-2" data-bs-toggle="modal" data-bs-target="#addPaciente"> 
                                            <i class="fas fa-folder-plus"></i> Aplicar Cambios
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
                
            </section>

        <!-- Script -->
        <script src="negocios/js/fcn_pacientes.js?v=<?=uniqid();?>"></script>

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