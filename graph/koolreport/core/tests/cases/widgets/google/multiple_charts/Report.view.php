<?php
    use \koolreport\widgets\google\ColumnChart;
    use \koolreport\widgets\google\BarChart;
    use \koolreport\widgets\google\Gauge;
    use \koolreport\widgets\google\Timeline;
?>
<html>

    <head>
        <title>top 10 custormers</title>
        <style>
            text-aling:center;
        </style>
    </head>

    <body>
       <center> <h1>Ventas Totales de cada Terminal</h1></center>

        <table>
            <tr>
            <th>
        <?php
            \koolreport\widgets\koolphp\Table::create(array(
                "dataSource"=>$this->dataStore("result"),
                "columns"=>array(
                    "month"=>array(
                        "label"=>"Mes   ",
                        "cssStyle"=>"text-align:center"
                        ),
                    "total_ventas"=>array(
                        "type"=>"number",
                        "label"=>"Ventas Totales",
                        "suffix"=>" €","cssStyle"=>"text-align:center"
                        )
                    ),
                    "cssClass"=>array(
                        "table"=>"table table-hover table-bordered"),

            ));

        ?>
        </th>
            <th>
        <?php
            // Agregamos un grafico de barras
            \koolreport\widgets\google\ColumnChart::create(array(
                "colorScheme"=>array(
                    "beige",
                    "#c2cad0",
                    "#c2b9b0",
                    "#7e685a",
                    "#afd275"
                ),
                "dataSource"=>$this->dataStore("result") , 
                "width"=>"800px",
                "height"=>"500px",
                "columns"=>array(
                    "month"=>array(
                        "label"=>"month"
                        ),
                    "total_ventas"=>array(
                        "type"=>"number",
                        "label"=>"Ventas Totales",
                        "suffix"=>" €"
                        
                        )
                    )
            ));
        ?>

        </th>

        </tr>
    </table>

    </body>

</html>