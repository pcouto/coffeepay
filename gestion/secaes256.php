<?php
   $password = "Qn21Yb2+WoxZ0QkdLCr25qZzG+SqL7zORzz+pTw75UA=";
   $key = "7824DBC8328A92E62EDFB2319AD1D61D22B248C959605BCA2E0ED5D490D66628";
   $iv = "22B248C959605BCA2E0ED5D490D66628";

   define('FIRSTKEY','Lk5Uz3slx3BrAghS1aaW5AYgWZRV0tIX5eI0yPchFz4=');
   define('SECONDKEY','EZ44mFi3TlAey1b2w4Y7lVDuqO+SRxGXsa7nctnr/JmMrA2vN6EJhrvdVZbxaQs5jpSe34X3ejFK/o9+Y5c83w==');

   $e = secured_encrypt("Hola Compañeiro");
   echo $e;
   $d = secured_decrypt($e);
   echo "\n".$d;

  /* $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'','cbc','');

   mcrypt_generic_init($cipher, $key, $iv);
   $decrypted = mdecrypt_generic($cipher,base64_decode($password));
   mcrypt_generic_deinit($cipher);
   echo "decrypted: " . substr($decrypted, 0, 100);*/
 ?>
 <?php
 function secured_encrypt($data)
 {
           $first_key = base64_decode(FIRSTKEY);
           $second_key = base64_decode(SECONDKEY);

           $method = "aes-256-cbc";
           $iv_length = openssl_cipher_iv_length($method);
           $iv = openssl_random_pseudo_bytes($iv_length);

           $first_encrypted = openssl_encrypt($data,$method,$first_key, OPENSSL_RAW_DATA ,$iv);
           $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

           $output = base64_encode($iv.$second_encrypted.$first_encrypted);
           return $output;
 }

 function secured_decrypt($input)
 {
           $first_key = base64_decode(FIRSTKEY);
           $second_key = base64_decode(SECONDKEY);
           $mix = base64_decode($input);

           $method = "aes-256-cbc";
           $iv_length = openssl_cipher_iv_length($method);

           $iv = substr($mix,0,$iv_length);
           $second_encrypted = substr($mix,$iv_length,64);
           $first_encrypted = substr($mix,$iv_length+64);

           $data = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
           $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

           if (hash_equals($second_encrypted,$second_encrypted_new))
           return $data;

           return false;
 }
