<?php
    if(!isset($_SESSION))
    {
        session_start();
    }
    
    // include ("../blockade.php");
    $user=$_SESSION['username'];
?>

<!doctype html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>Citas Medicas</title>
        <link rel="shortcut icon" href="../assets/images/medicina.jpeg" type="image/x-icon">

        <!-- <script src="../../codebase/dhtmlxscheduler.js?v=5.3.12" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="../../codebase/dhtmlxscheduler_material.css?v=5.3.12" type="text/css" charset="utf-8"> -->

        <script src="data/dhtmlxscheduler.js?v=5.3.12" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="data/dhtmlxscheduler_material.css?v=5.3.12" type="text/css" charset="utf-8">
        <script src="data/locale/locale_es.js?v=5.3.12" type="text/javascript" charset="utf-8"></script>

        <style>
            html, body{
                margin:0px;
                padding:0px;
                height:100%;
                overflow:hidden;
            }	
        </style>
    </head>

    <body>
        <div id="scheduler_here" class="dhx_cal_container" style='width:100%; height:100%'>
            <div class="dhx_cal_navline">
                <div class="dhx_cal_prev_button">&nbsp;</div>
                <div class="dhx_cal_next_button">&nbsp;</div>
                <div class="dhx_cal_today_button"></div>
                <div class="dhx_cal_date"></div>
                <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
                <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
                <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
            </div>
            <div class="dhx_cal_header">
            </div>
            <div class="dhx_cal_data">
            </div>
        </div>

        <script>
            // Mostrar de Lunes a Sabado
            scheduler.ignore_week = function(date){
                if (date.getDay() == 7 || date.getDay() == 0) //hides Saturdays and Sundays
                    return true;
            };

            scheduler.config.responsive_lightbox = true;

            // Hora Inicial y final
            scheduler.config.first_hour = 8;
            scheduler.config.last_hour = 20;

            // Tiempo de los eventos
            scheduler.config.time_step = 60;

            scheduler.setLoadMode("day");
            scheduler.init("scheduler_here", new Date(),"week");
            scheduler.load("data/api.php");

            var dp = new dataProcessor("data/api.php");
            dp.init(scheduler);
            dp.setTransactionMode("JSON"); // use to transfer data with JSON
        </script>
        <!-- CALENDARIO -->
    </body>
</html>

