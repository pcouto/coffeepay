<?php

header('Content-type: text/plain; charset=utf-8');
$data = file_get_contents("peticion.json");
//$json = json_decode($data, true);

$data = preg_replace('/[ ]{2,}|[\t]/', '', trim($data));
$data= str_replace (array("\r\n", "\n", "\r"), '', $data);
echo $data;
echo "\r\n";echo "\r\n";
$jsb64=trim($data);

$jsb64 = base64_encode($data);

echo $jsb64;
echo "\r\n";echo "\r\n";





//echo $jsb64;

//{"DS_MERCHANT_AMOUNT": "145","DS_MERCHANT_CURRENCY": "978","DS_MERCHANT_MERCHANTCODE": "999008881","DS_MERCHANT_MERCHANTURL": "http://www.prueba.com/urlNotificacion.php","DS_MERCHANT_ORDER": "1446068581","DS_MERCHANT_TERMINAL": "1","DS_MERCHANT_TRANSACTIONTYPE": "0","DS_MERCHANT_URLKO": "http://www.prueba.com/urlKO.php","DS_MERCHANT_URLOK": "http://www.prueba.com/urlOK.php"}

$expected= "eyJEU19NRVJDSEFOVF9BTU9VTlQiOiAiMTQ1IiwiRFNfTUVSQ0hBTlRfQ1VSUkVOQ1kiOiAiOTc4IiwiRFNfTUVSQ0hBTlRfTUVSQ0hBTlRDT0RFIjogIjk5OTAwODg4MSIsIkRTX01FUkNIQU5UX01FUkNIQU5UVVJMIjogImh0dHA6Ly93d3cucHJ1ZWJhLmNvbS91cmxOb3RpZmljYWNpb24ucGhwIiwiRFNfTUVSQ0hBTlRfT1JERVIiOiAiMTQ0NjA2ODU4MSIsIkRTX01FUkNIQU5UX1RFUk1JTkFMIjogIjEiLCJEU19NRVJDSEFOVF9UUkFOU0FDVElPTlRZUEUiOiAiMCIsIkRTX01FUkNIQU5UX1VSTEtPIjogImh0dHA6Ly93d3cucHJ1ZWJhLmNvbS91cmxLTy5waHAiLCJEU19NRVJDSEFOVF9VUkxPSyI6ICJodHRwOi8vd3d3LnBydWViYS5jb20vdXJsT0sucGhwIn0=";

$res=base64_decode($expected);

echo $res;
echo "\r\n";
echo $expected;
echo "\r\n";

//echo $expected;

if ($jsb64<>$expected){
    echo "Las cadenas SON DISTINTAS";
}
else
{
    echo "Son identicas";
}


?>