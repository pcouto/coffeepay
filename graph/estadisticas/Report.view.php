<?php
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\Gauge;
    use \koolreport\widgets\google\Timeline;
?>

<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>graficos coffeepay</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu-sidebar.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/SIdebar-Responsive-2-ResponsiveSideBar-2.css">
    <link rel="stylesheet" href="assets/css/SIdebar-Responsive-2.css">
    <link rel="stylesheet" href="assets/css/sidebar-style4.css">
    <link rel="stylesheet" href="assets/css/sidebar.css">
    <link rel="stylesheet" href="assets/css/Ultimate-Sidebar-Menu.css">

    <style>
        .Grafico,
        .Tabla {
            margin-top: 10px;
            margin-bottom: 10px;
            margin-left: 40px;
            margin-right: 10px;
        }

        .Color0 {
            background-color: #c2cad0;
            width: 94.6%;
            height: 400px;
            padding: 10px;
        }

        .Color1 {
            background-color: #d3c4a2;

        }

        .Color2 {
            background-color: #a2bdd3;
        }

        .Color3 {
            background-color: #c1a2d3;
        }

        .Color4 {
            background-color: #e0a63a;
            width: 94.6%;
            height: 400px;
            padding: 10px;
        }

        .Color5 {
            background-color: #d3a2a2;
        }
        .Gr1 {
            background-color: blue;
            color: green;
        }

        thead {
            background-color: red;
            color: white;
        }
    </style>
</head>

<body>

    <div class="border">

        <div class="border ">
            <div class="row">

                <div class="Grafico col-10 Color0  shadow border border-dark rounded">

                    Gráfico
                    <?php
                    //**********************************************************************************//
                    //************************** Grafico Ventas Brutas *********************************// 
                    //**********************************************************************************//

                    setlocale(LC_ALL, "es");
                    $Y0 = date("Y");
                    $Y1 = $Y0 - 1;
                    $Y2 = $Y0 - 2;
                    //$data = $this->dataStore('VentasPorAno')->meta(); // extrae los metadatos
                    $data = $this->dataStore('VentasPorAno')->data();   // Extrae los datos
                    //echo '<pre>'; print_r($data); echo '</pre>';
                    //echo "<br>Elementos en \$data : ". count($data);
                    //echo "<br>Elementos en \$data[0] : ". count($data[0]);

                    // echo '<br>'. $data[0]['dateyear'];
                    // Agregamos un grafico de barras
                    \koolreport\widgets\google\ColumnChart::create(
                        array(
                            "title" => "Ventas Brutas Por Terminal",
                            "dataSource" => $this->dataStore("VentasBrutas"),
                            "height" => "350px",
                            "colorScheme" => array(
                                "#3366CC",
                                "#DC3912",
                                "#FF9900",
                                "beige",
                                "#c2cad0",
                                "#c2b9b0",
                                "#7e685a",
                                "#afd275"
                            ),
                            "columns" => array(
                                "term" => array(
                                    "type" => "text",
                                    "label" => "term",
                                    "suffix" => " €"
                                ),
                                "VB0" => array(
                                    "type" => "number",
                                    "label" => "Importe Total",
                                    "suffix" => " €"
                                ),
                            ),
                            "options" => array(
                                "legend" => array(
                                    "position" => "top",

                                ),
                                "chartArea" => array(
                                    "width" => "80%",
                                    "stroke" => '#4322c0',
                                    "strokeWidth" => "3",
                                ),
                                "bar" => array(
                                    "groupWidth" => "80%",
                                ),
                                "annotations" => array(
                                    "alwaysOutside" => true,
                                ),
                                "explorer" => array(
                                    "actions" => array('dragToZoom', 'rightClickToReset'),
                                    "axis" => 'horizontal',
                                    "keepInBounds" => true,
                                    "maxZoomIn" => 8.0
                                ),
                            )
                        )
                    );

                    ?>

                </div>

            </div>


        </div>

        <div class="row">
            <div class="Grafico Color1 col-8 shadow border border-dark rounded ">
                Gráfico

                <?php
                //**********************************************************************************//
                //************************** Grafico Ventas Brutas *********************************// 
                //**********************************************************************************//

                setlocale(LC_ALL, "es");
                $Y0 = date("Y");
                $Y1 = $Y0 - 1;
                $Y2 = $Y0 - 2;
                //$data = $this->dataStore('VentasPorAno')->meta(); // extrae los metadatos
                $data = $this->dataStore('VentasPorAno')->data();   // Extrae los datos
                //echo '<pre>'; print_r($data); echo '</pre>';
                //echo "<br>Elementos en \$data : ". count($data);
                //echo "<br>Elementos en \$data[0] : ". count($data[0]);

                // echo '<br>'. $data[0]['dateyear'];
                // Agregamos un grafico de barras
                \koolreport\widgets\google\ColumnChart::create(
                    array(
                        "title" => "Ventas Mensuales",
                        "dataSource" => $this->dataStore("VentasPorAno"),
                        "height" => "90%",
                        "colorScheme" => array(
                            "#3366CC",
                            "#DC3912",
                            "#FF9900",
                            "beige",
                            "#c2cad0",
                            "#c2b9b0",
                            "#7e685a",
                            "#afd275"
                        ),
                        "columns" => array(
                            "dateyear" => array(
                                "title" => "Ventas comparativa 3 ultimos años",
                                "label" => "dateyear",
                                "formatValue" => function ($value) {
                                    setlocale(LC_TIME, "es_ES");
                                    return strftime("%B", strtotime($value));
                                }
                            ),
                            "SY0" => array(
                                "type" => "number",
                                "label" => "$Y2",
                                "suffix" => " €"
                            ),
                            "SY1" => array(
                                "type" => "number",
                                "label" => "$Y1",
                                "suffix" => " €"
                            ),
                            "SY2" => array(
                                "type" => "number",
                                "label" => "$Y0",
                                "suffix" => " €"
                            ),

                        )
                    )
                );

                ?>

            </div>

            <div class="Tabla Color1 col-3 shadow border border-dark rounded">
                Tabla
                <?php
                //**********************************************************************************//
                //************************ Tabla Datos Ventas Brutas *******************************// 
                //**********************************************************************************//

                date_default_timezone_set('Europe/Madrid');

                \koolreport\widgets\koolphp\Table::create(array(
                    "dataSource" => $this->dataStore("VentasPorAno"),
                    "width" => "90%",
                    "height" => "80%",
                    "columns" => array(
                        "dateyear" => array(
                            "label" => "Mes",
                            "cssStyle" => "text-align:center",
                            "formatValue" => function ($value) {
                                setlocale(LC_TIME, "es_ES");
                                return strftime("%B", strtotime($value));
                            }
                        ),
                        "SY0" => array(
                            "type" => "number",
                            "label" => "2022",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        ),

                        "SY1" => array(
                            "type" => "number",
                            "label" => "2023",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        ),

                        "SY2" => array(
                            "type" => "number",
                            "label" => "2024",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        )

                    ),

                    "cssClass" => array(
                        "table" => "table table-hover table-bordered"
                    ),

                    "options" => array(
                        "legend" => array(
                            "position" => "bottom",
                        ),
                        "chartArea" => array(
                            "width" => "80%",
                        ),
                        "bar" => array(
                            "groupWidth" => "40%",
                        ),
                        "annotations" => array(
                            "alwaysOutside" => true,
                        ),
                    )

                ));

                ?>
            </div>


        </div>


        <div class="row">

            <div class="Tabla Color2 col-3 shadow border border-dark rounded">
                Tabla
                <?php
                //**********************************************************************************//
                //*************************** Tabla Ventas medias **********************************// 
                //**********************************************************************************//
                date_default_timezone_set('Europe/Madrid');

                \koolreport\widgets\koolphp\Table::create(array(
                    "dataSource" => $this->dataStore("VentasPorAno"),
                    "width" => "90%",
                    "height" => "80%",
                    "columns" => array(
                        "dateyear" => array(
                            "label" => "Mes",
                            "cssStyle" => "text-align:center",
                            "formatValue" => function ($value) {
                                setlocale(LC_TIME, "es_ES");
                                return strftime("%B", strtotime($value));
                            }
                        ),
                        "MY0" => array(
                            "type" => "number",
                            "label" => "2022",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        ),

                        "MY1" => array(
                            "type" => "number",
                            "label" => "2023",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        ),

                        "MY2" => array(
                            "type" => "number",
                            "label" => "2024",
                            "suffix" => " €", "cssStyle" => "text-align:center"
                        )

                    ),

                    "cssClass" => array(
                        "table" => "table table-hover table-bordered"
                    ),

                    "options" => array(
                        "legend" => array(
                            "position" => "bottom",
                        ),
                        "chartArea" => array(
                            "width" => "80%",
                        ),
                        "bar" => array(
                            "groupWidth" => "40%",
                        ),
                        "annotations" => array(
                            "alwaysOutside" => true,
                        ),
                    )

                ));

                ?>
            </div>


            <div class="Grafico Color2 col-8 shadow border border-dark rounded ">
                Gráfico

                <?php

                //**********************************************************************************//
                //************************** Grafico Ventas medias *********************************// 
                //**********************************************************************************//


                setlocale(LC_ALL, "es");
                $Y0 = date("Y");
                $Y1 = $Y0 - 1;
                $Y2 = $Y0 - 2;
                //$data = $this->dataStore('VentasPorAno')->meta(); // extrae los metadatos
                $data = $this->dataStore('VentasPorAno')->data();   // Extrae los datos
                //echo '<pre>'; print_r($data); echo '</pre>';
                //echo "<br>Elementos en \$data : ". count($data);
                //echo "<br>Elementos en \$data[0] : ". count($data[0]);

                // echo '<br>'. $data[0]['dateyear'];
                // Agregamos un grafico de barras
                \koolreport\widgets\google\ColumnChart::create(
                    array(
                        "title" => "Recaudación Media Mensual Por Terminal",
                        "dataSource" => $this->dataStore("VentasPorAno"),
                        "height" => "90%",
                        "colorScheme" => array(
                            "#3366CC",
                            "#DC3912",
                            "#FF9900",
                            "beige",
                            "#c2cad0",
                            "#c2b9b0",
                            "#7e685a",
                            "#afd275"
                        ),
                        "columns" => array(
                            "dateyear" => array(
                                "title" => "Ventas comparativa 3 ultimos años",
                                "label" => "dateyear",
                                "formatValue" => function ($value) {
                                    setlocale(LC_TIME, "es_ES");
                                    return strftime("%B", strtotime($value));
                                }
                            ),
                            "MY0" => array(
                                "type" => "number",
                                "label" => "$Y2",
                                "suffix" => " €"
                            ),
                            "MY1" => array(
                                "type" => "number",
                                "label" => "$Y1",
                                "suffix" => " €"
                            ),
                            "MY2" => array(
                                "type" => "number",
                                "label" => "$Y0",
                                "suffix" => " €"
                            ),

                        )
                    )
                );

                ?>

            </div>



        </div>


        <div class="row">

            <div class="Grafico col-10 Color4  shadow border border-dark rounded">

                Gráfico
                <?php
                //**********************************************************************************//
                //***************** Grafico Medias Mensuales por terminal  *************************// 
                //**********************************************************************************//

                setlocale(LC_ALL, "es");
                $Y0 = date("Y");
                $Y1 = $Y0 - 1;
                $Y2 = $Y0 - 2;
                //$data = $this->dataStore('VentasPorAno')->meta(); // extrae los metadatos
                //$data = $this->dataStore('VentasPorAno')->data();   // Extrae los datos
                //echo '<pre>'; print_r($data); echo '</pre>';
                //echo "<br>Elementos en \$data : ". count($data);
                //echo "<br>Elementos en \$data[0] : ". count($data[0]);

                // echo '<br>'. $data[0]['dateyear'];
                // Agregamos un grafico de barras
                \koolreport\widgets\google\columnChart::create(
                    array(
                        "title" => "Ventas Medias Mensuales Por Terminal",
                        "dataSource" => $this->dataStore("VentasMedias"),
                        "height" => "350px",
                        "colorScheme" => array(
                           "beige", "#833b4b", 
                            "#c2cad0",
                            "#3366CC",
                            "#DC3912",
                            "#FF9900",
                            
                            "#c2b9b0",
                            "#7e685a",
                            
                        ),
                        "columns" => array(
                            "term" => array(
                                "type" => "text",
                                "label" => "term",
                                "suffix" => " €"
                            ),
                            "media" => array(
                                "type" => "number",
                                "label" => "Media Mensual",
                                "suffix" => " €"
                            ),
                        ),
                        "options" => array(
                            "legend" => array(
                                "position" => "top",

                            ),
                            "chartArea" => array(
                                "width" => "80%",
                                "stroke" => '#4322c0',
                                "strokeWidth" => "3",
                            ),
                            "bar" => array(
                                "groupWidth" => "80%",
                            ),
                            "annotations" => array(
                                "alwaysOutside" => true,
                            ),
                            "explorer" => array(
                                "actions" => array('dragToZoom', 'rightClickToReset'),
                                "axis" => 'horizontal',
                                "keepInBounds" => true,
                                "maxZoomIn" => 8.0
                            ),
                        )
                    )
                );

                ?>

            </div>

        </div>


        <div class="row">

            <div class="Grafico Color5 col-8 shadow border border-dark rounded ">
                Gráfico

                <?php

                //**********************************************************************************//
                //********************** Gráfico Terminales Instalados *****************************// 
                //**********************************************************************************//


                setlocale(LC_ALL, "es");
                $Y0 = date("Y");
                $Y1 = $Y0 - 1;
                $Y2 = $Y0 - 2;
                //$data = $this->dataStore('VentasPorAno')->meta(); // extrae los metadatos
                $data = $this->dataStore('VentasPorAno')->data();   // Extrae los datos
                //echo '<pre>'; print_r($data); echo '</pre>';
                //echo "<br>Elementos en \$data : ". count($data);
                //echo "<br>Elementos en \$data[0] : ". count($data[0]);

                // echo '<br>'. $data[0]['dateyear'];
                // Agregamos un grafico de barras
                \koolreport\widgets\google\ColumnChart::create(
                    array(
                        "title" => "Terminales Activos",
                        "dataSource" => $this->dataStore("TerminalesInstalados"),
                        "height" => "90%",
                        "colorScheme" => array(
                            "#3366CC",
                            "#DC3912",
                            "#FF9900",
                            "beige",
                            "#c2cad0",
                            "#c2b9b0",
                            "#7e685a",
                            "#afd275"
                        ),
                        "columns" => array(
                            "dateyear" => array(
                                "title" => "Ventas comparativa 3 ultimos años",
                                "label" => "dateyear",
                                "formatValue" => function ($value) {
                                    setlocale(LC_TIME, "es_ES");
                                    return strftime("%B", strtotime($value));
                                }
                            ),
                            "Y0" => array(
                                "type" => "number",
                                "label" => "$Y2",
                            ),
                            "Y1" => array(
                                "type" => "number",
                                "label" => "$Y1",

                            ),
                            "Y2" => array(
                                "type" => "number",
                                "label" => "$Y0",
                            ),

                        )
                    )
                );

                ?>

            </div>

            <div class="Tabla Color5 col-3 shadow border border-dark rounded">
                Tabla
                <?php
                //**********************************************************************************//
                //*********************** Tabla Terminales Instalados ******************************// 
                //**********************************************************************************//
                date_default_timezone_set('Europe/Madrid');

                \koolreport\widgets\koolphp\Table::create(array(
                    "dataSource" => $this->dataStore("TerminalesInstalados"),
                    "width" => "90%",
                    "height" => "80%",
                    "columns" => array(
                        "dateyear" => array(
                            "label" => "Mes",
                            "cssStyle" => "text-align:center",
                            "formatValue" => function ($value) {
                                setlocale(LC_TIME, "es_ES");
                                return strftime("%B", strtotime($value));
                            }
                        ),
                        "Y0" => array(
                            "type" => "number",
                            "label" => "2022",
                            "cssStyle" => "text-align:center"
                        ),

                        "Y1" => array(
                            "type" => "number",
                            "label" => "2023",
                            "cssStyle" => "text-align:center"
                        ),

                        "Y2" => array(
                            "type" => "number",
                            "label" => "2024",
                            "cssStyle" => "text-align:center"
                        )

                    ),

                    "cssClass" => array(
                        "table" => "table table-hover table-bordered"
                    ),

                    "options" => array(
                        "legend" => array(
                            "position" => "bottom",
                        ),
                        "chartArea" => array(
                            "width" => "80%",
                        ),
                        "bar" => array(
                            "groupWidth" => "40%",
                        ),
                        "annotations" => array(
                            "alwaysOutside" => true,
                        ),
                    )

                ));

                ?>
            </div>






        </div>

    </div>
    </div>

    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Sidebar-Menu-sidebar.js"></script>
    <script src="assets/js/Ultimate-Sidebar-Menu.js"></script>
</body>

</html>