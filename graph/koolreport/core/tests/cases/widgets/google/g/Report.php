<?php
    class Report extends koolreport\koolreport
    {
        use \koolreport\clients\Bootstrap; // for adding style to report

        // Create Settings
        protected function settings()
        {
            return array(
                "dataSources"=>(array(
                    "automaker"=>array(
                        "connectionString"=>"mysql:host=localhost;dbname=CafeDueTazze",
                        "username"=>"CafeDueTazze",
                        "password"=>"@CafeDueTazze",
                        "charset"=>"utf8"
                        )
                    )
                )
            );
        }

        //Setup Report
                    /*    SELECT terminal , SUM(importe) as total_ventas   FROM journal 
                where operacion = 'Venta' and establecimiento not like 'Master%' 
                GROUP BY terminal order by total_ventas desc  limit 25*/
        protected function setup(){
            $this->src("automaker")
            ->query("
                SELECT DATE_FORMAT(fecha, '%b-%y') AS month, SUM(importe) as total_ventas
                FROM journal
                WHERE fecha <= NOW()
                and fecha >= Date_add(Now(),interval - 12 month) and Operacion='Venta' 
                GROUP BY DATE_FORMAT(fecha, '%Y-%m')
            ")
            ->pipe($this->dataStore("result"));
        }

    }


?>