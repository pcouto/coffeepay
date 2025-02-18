<?php




// si se pasa un perametro de terminal, se ve solo el terminal seleccionado



// -------------------------------------------------
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>



        <link rel="stylesheet" href="./lib/js/themes/custom/jquery-ui.custom.css">
        </link>
        <link rel="stylesheet" href="./lib/js/jqgrid/css/ui.jqgrid.css">
        </link>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <!--
        alguna de estas librerias es la quye se carga los bordes de la pagina

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        -->


        <link rel="stylesheet" href="../../gestion/datos/css/estilos.css">


        <!-- Bootstrap CSS -->
        <meta charset="utf-8">
        <title>Estadísticas</title>

</head>

<body>

        <footer id="pageFooter">Pie</footer>
        <header id="pageHeader">
                <p>Comparativa de venta 3 utimos años.</p>
        </header>
        <logo id="pageLogo" >
                CAFE DUETAZZE
                <div class="logoc">
                        <hr>EMPRESA ANDALUZA
                </div>
        </logo>

        <article id="mainArticle">
                <?php
                require_once "./koolreport/core/autoload.php";
                require_once "Report.php";

                $report = new Report;
                $report->run()->render(); ?>

        </article>
        <nav id="mainNav">

                <p>Menú</p>
                <div id="menulinks">
                        <a class="text-white" href="../../gestion/datos/index2.php">Movimientos</a>
                        <a class="text-white" href="../../gestion/datos/terminales.php">Terminales</a>
                        <a class="text-white" href="estadisticas.php">Estadísticas</a>
                        <a class="text-white" href="../../gestion/datos/usuarios.php">Usuarios</a>
                </div>

        </nav>
        <footer id="pageFooter">
                <h6>By Knessen Korps S.L.</h6>
        </footer>
</body>

</html>