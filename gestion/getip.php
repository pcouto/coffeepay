<?php

  // Captura la ip de ña conexion entrante
      $IP = '';
        if (getenv('HTTP_CLIENT_IP')) {
          $IP =getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
          $IP =getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
          $IP =getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_X_CLUSTER_CLIENT_IP')) {
          $IP =getenv('HTTP_X_CLUSTER_CLIENT_IP');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
          $IP =getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
          $IP = getenv('HTTP_FORWARDED');
        } else {
          $IP = $_SERVER['REMOTE_ADDR'];
        }

    

 ?>
