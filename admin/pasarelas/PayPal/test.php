<?php 

$string='{"id":"WH-0P278929J6773033B-7AG62714UP6094215","event_version":"1.0","create_time":"2020-04-19T03:51:00.377Z","resource_type":"checkout-order","resource_version":"2.0","event_type":"CHECKOUT.ORDER.APPROVED","summary":"An order has been approved by buyer","resource":{"create_time":"2020-04-19T03:48:06Z","purchase_units":[{"reference_id":"394","amount":{"currency_code":"USD","value":"127.53"},"payee":{"email_address":"sb-jbayo1494692@personal.example.com","merchant_id":"S4EGACE2XU6L2"},"description":"Reserva en metelebrasil.com","custom_id":"394","shipping":{"name":{"full_name":"adsd asd"},"address":{"address_line_1":"chorroarin 30","address_line_2":"dsa","admin_area_2":"GBA Oeste","admin_area_1":"CIUDAD AUT\u00d3NOMA DE BUENOS AIRES","postal_code":"1766","country_code":"AR"}}}],"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/2TB29565DT436623J","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/2TB29565DT436623J","rel":"update","method":"PATCH"},{"href":"https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/2TB29565DT436623J\/capture","rel":"capture","method":"POST"}],"id":"2TB29565DT436623J","intent":"CAPTURE","payer":{"name":{"given_name":"adsd","surname":"asd"},"email_address":"elcheby@gmail.com","payer_id":"5MH2CHSZZPU4G","address":{"country_code":"AR"}},"status":"APPROVED"},"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v1\/notifications\/webhooks-events\/WH-0P278929J6773033B-7AG62714UP6094215","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v1\/notifications\/webhooks-events\/WH-0P278929J6773033B-7AG62714UP6094215\/resend","rel":"resend","method":"POST"}]}

';

$string=json_decode($string, true);
$outer_arr = $string["resource"]["purchase_units"][0]["custom_id"];
echo $outer_arr;
foreach($outer_arr as $key => $val) {
    echo "Clave: "; print($key);  echo "<br>";// "kanye"
    echo "val: "; print_r($val);
    echo "<br>"; // Array ( [0] => Kanya [1] => Janaye [2] => Kayne [3] => Kane )
}
 ?>