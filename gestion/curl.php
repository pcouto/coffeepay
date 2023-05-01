<?php
// abrimos la sesión cURL
  $ch = curl_init();

  // definimos la URL a la que hacemos la petición
  curl_setopt($ch, CURLOPT_URL,"http://oxxom.com/ipecho.php");
  // indicamos el tipo de petición: POST
  curl_setopt($ch, CURLOPT_POST, TRUE);
  // definimos cada uno de los parámetros
  curl_setopt($ch, CURLOPT_POSTFIELDS, "postvar1=value1&postvar2=value2&postvar3=value3");

  // recibimos la respuesta y la guardamos en una variable
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $remote_server_output = curl_exec ($ch);

  // cerramos la sesión cURL
  curl_close ($ch);

  // hacemos lo que queramos con los datos recibidos
  // por ejemplo, los mostramos
  echo($remote_server_output);
?>
