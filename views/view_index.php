<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    $user=$_SESSION['username'];

    include "../datos/conexion_mysql.php";

    $query = "SELECT year(cot_fecha), cli_nombre FROM t_cotizacion b
    join t_clientes a on a.cli_id  =b.cli_id
    group by cot_fecha
    order by cot_fecha 
    ;";
    $result = mysqli_query($conn,$query);

    $query2 = "SELECT year(cot_fecha), cli_nombre FROM t_cotizacion b
    join t_clientes a on a.cli_id  =b.cli_id
    group by cot_fecha
    order by cot_fecha 
    ;";
    $result2 = mysqli_query($conn,$query2);

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
            var param = { Actividad:"lst_citasmedicas" };
            var tbl = "tbl_citasmedicas";
            var model = "tree_index";
            _initDataTable(tbl,model,param);
        });
    </script>


    <section class="section">
      <div class="card">
        <div class="card-header">  </div>
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-12 col-md-6">
              <div class="card flex-fill w-100">
                <div class="card-header">
                  <h5 class="card-title">Total Cotizaciones Por año</h5>
                  <!-- <h6 class="card-subtitle text-muted">A line chart is a way of plotting data points on a line.</h6> -->
                </div>
                <div class="card-body">
                  <div class="chart">
                    <canvas id="bar"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="card flex-fill w-100">
                <div class="card-header">
                  <h5 class="card-title">Cotizaciones del: <?php date_default_timezone_set('America/Tegucigalpa'); echo date('Y-m-d');?></h5>
                  <!-- <h6 class="card-subtitle text-muted">A line chart is a way of plotting data points on a line.</h6> -->
                </div>
                <div class="card-body">
                  <table class='table table-sm table-striped' id="tbl_citasmedicas">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10px;">No</th>
                            <th style="text-align: center">Fecha</th>
                            <th style="text-align: center">Nombre</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px;"> </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- <div class="row mb-2">
            <div class="col-12 col-md-6">
              <div class="card flex-fill w-100">
                <div class="card-header">
                  <h5 class="card-title">Line Chart</h5>
                  <h6 class="card-subtitle text-muted">A line chart is a way of plotting data points on a line.</h6>
                </div>
                <div class="card-body">
                  <div class="chart">
                    <canvas id="bar"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="card flex-fill w-100">
                <div class="card-header">
                  <h5 class="card-title">Line Chart</h5>
                  <h6 class="card-subtitle text-muted">A line chart is a way of plotting data points on a line.</h6>
                </div>
                <div class="card-body">
                  <div class="chart">
                    <canvas id="bar"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

        </div>
      </div>
    </section>

    <!-- <script src="assets/js/pages/bar.js"></script> -->

    <!-- Script -->
    <!-- <script src="negocios/js/fcn_historialmedico.js?v=<?=uniqid();?>"></script> -->

     <script>
      $(document).ready( function () 
      {
        var chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        info: '#41B1F9',
        blue: '#3245D1',
        purple: 'rgb(153, 102, 255)',
        grey: '#EBEFF6' };

        var ctxBar = document.getElementById("bar").getContext("2d");
        var myBar = new Chart(ctxBar, {
          type: 'line',
          data: {
            labels: [
                      <?php 
                        while($row = mysqli_fetch_array($result))
                        {
                            echo " '".$row[0]."',";
                        }
                      ?>
                    ],
          datasets: [{
          label: 'Pacientes',
          // backgroundColor: [chartColors.grey, chartColors.grey, chartColors.grey, chartColors.grey, chartColors.info, chartColors.blue, chartColors.grey],
          // backgroundColor: chartColors.info,
          // backgroundColor: 'rgb(100, 149, 237)',
          backgroundColor: "transparent",
						borderColor: '#41B1F9',
          data: [
                  <?php 
                    while($row1 = mysqli_fetch_array($result2))
                    {
                        echo " '".$row1[1]."',";
                    }
                  ?>
              ]
            }]
          },
          options: {
            responsive: true,
            barRoundness: 1,
            title: {
              display: false,
              text: ""
            },
            legend: {
              display:false
            },
            scales: {
              yAxes: [{
                ticks: {
                  // beginAtZero: true,
                  stepSize: 50,
                  suggestedMax: 40 + 20,
                  padding: 10,
                },
                gridLines: {
                  drawBorder: false,
                }
              }],
              xAxes: [{
                    gridLines: {
                        display:false,
                        drawBorder: false
                    }
                }]
            }
          }
        });
      } );
    </script> 




    <!-- Lineal -->
  
    <!-- <script>
		 $(document).ready( function () 
      {
			// Line chart
			new Chart(document.getElementById("chartjs-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales ($)",
						fill: true,
						backgroundColor: "transparent",
						borderColor: '#41B1F9',
						data: [2115, 1562, 1584, 1892, 1487, 2223, 2966, 2448, 2905, 3838, 2917, 3327]
					}, 
					]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.05)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 500
							},
							display: true,
							borderDash: [5, 5],
							gridLines: {
								color: "rgba(0,0,0,0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
	</script> -->



  </body>
</html>