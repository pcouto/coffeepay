<?php

$passwd64 = "Qn21Yb2+WoxZ0QkdLCr25qZzG+SqL7zORzz+pTw75UA=";
$key64 = "7824DBC8328A92E62EDFB2319AD1D61D22B248C959605BCA2E0ED5D490D66628";
$iv64 = "22B248C959605BCA2E0ED5D490D66628";

$key = hex2bin($key64);
$iv = hex2bin($iv64);

 $decrypted = openssl_decrypt($passwd64, 'aes-256-cbc', $key, 0, $iv);

echo $decrypted;
 ?>
