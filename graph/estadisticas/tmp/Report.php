<?php

class Report extends \koolreport\KoolReport
{
	use \koolreport\clients\Bootstrap; // for adding style to report
	
	 // Create Settings
        protected function settings()
        {
            return array(
                "dataSources"=>(array(
                    "conn1"=>array(
                        "connectionString"=>"mysql:host=localhost;dbname=CafeDueTazze",
                        "username"=>"CafeDueTazze",
                        "password"=>"@CafeDueTazze",
                        "charset"=>"utf8"
                        )
                    )
                )
            );
        }
	
	
	 protected function setup(){

            // Suma las ventas totales por terminal de los 3 ultimos años por meses
            // Cuenta los terminales activos por meses
            // y mira la media de ventas por terminal por meses
            // SY0,SY1,SY2 => Sumas de ventas para los años actual(0), actual -1(1) y actual -2 (2)
            // TY0,TY1,TY2 => Numero de terminales activos cada mes para los 3 ultimos años
            // MY0,MY1,MY2 => Medias de venta por terminal por mes para los 3 ultimos años 

            $this->src("conn1")
            ->query("
            
            select dateyear, 
            SY0,TY0, ifnull(SY0/TY0,0) as MY0, 
            SY1, TY1, ifnull(SY1/TY1,0) as MY1,
            SY2, TY2, ifnull(SY2/TY2,0) as MY2 
            from ( SELECT  DATE_FORMAT(fecha, '%M') as dateyear,
             sum(CASE WHEN year(fecha) = year(curdate())-2 THEN importe ELSE 0 end) as SY0,
             COUNT(DISTINCT CASE WHEN year(fecha) = year(curdate())-2 THEN Terminal END) AS TY0,
             sum(CASE WHEN year(fecha) = year(curdate())-1 THEN importe ELSE 0 end) as SY1,
             COUNT(DISTINCT CASE WHEN year(fecha) = year(curdate())-1 THEN Terminal END) AS TY1,
             sum(CASE WHEN year(fecha) = year(curdate()) THEN importe ELSE 0 END ) as SY2,
             COUNT(DISTINCT CASE WHEN year(fecha) = year(curdate()) THEN Terminal END) AS TY2
             FROM journal where operacion = 'venta' group by DATE_FORMAT(fecha, '%m')   order by month(fecha)) as Request
            ")
            ->pipe($this->dataStore("VentasPorAno"));


            // Número de Terminales instaslados por mes de los 3 ultimos años    
            $this->src("conn1")
            ->query("
            SELECT  DATE_FORMAT(fecha, '%M') as dateyear,  
            COUNT(DISTINCT CASE WHEN year(fecha) = '2022' THEN Terminal END) AS Y0,
            COUNT(DISTINCT CASE WHEN year(fecha) = '2023' THEN Terminal END) AS Y1,
            COUNT(DISTINCT CASE WHEN year(fecha) = '2024' THEN Terminal END) AS Y2
	        From journal where operacion = 'venta' group by DATE_FORMAT(fecha, '%m') order by month(fecha)
             ")
            ->pipe($this->dataStore("TerminalesInstalados"));


            // Ventas Brutas por terminal sin tener en cuenta el tiempo activo.
            $this->src("conn1")
            ->query("
            SELECT Terminal as term, sum(importe) as VB0 from journal   
            where operacion = 'venta' group by Terminal order by sum(importe) desc 
             ")
            ->pipe($this->dataStore("VentasBrutas"));


            // Ventas Medias mensuales por terminal con independencia de cuanto tiempo lleva activo
            $this->src("conn1")
            ->query("
            select  term, total, ROUND(total/ PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM CURRENT_DATE),EXTRACT(YEAR_MONTH FROM finicial)),2) AS media
            from
            (SELECT CONCAT(datos.establecimiento,' (',datos.terminal,')') as term, sum(importe) as total, fecha as finicial FROM `datos` left join journal 
            on datos.terminal = journal.terminal 
            where datos.Terminal<> '' 
            group by datos.terminal order by datos.terminal, fecha asc)
            as tt order by media desc
             ")
            ->pipe($this->dataStore("VentasMedias"));



        }

	
	
}