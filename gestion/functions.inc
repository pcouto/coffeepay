<?php
function recalarm ($incidence, $gravity)
// Guarda notificaciones de seguridad o de atencion acerca de los terminales
// Se les debe de prestar atencion
{
  global $conexion,$Terminal;
  $sql_alarm ="insert into alarmas (Fecha,Terminal,Incidencia, Gravedad) values ('".date('Y-m-d H:i:s')."','$Terminal','$incidence','$gravity')";
  //echo $sql_alarm;
  $result = mysqli_query($conexion,$sql_alarm);

}


 ?>
