<?php 

$string='{"update_time":"2021-05-04T19:54:16Z","create_time":"2021-05-04T19:54:01Z","purchase_units":[{"reference_id":"652","amount":{"currency_code":"USD","value":"52.84"},"payee":{"email_address":"sb-jbayo1494692@personal.example.com","merchant_id":"S4EGACE2XU6L2"},"description":"Reserva en metelebrasil.com","custom_id":"EOT359","soft_descriptor":"PAYPAL *SBJBAYO1494","shipping":{"name":{"full_name":"John Doe"},"address":{"address_line_1":"1 Main St","admin_area_2":"San Jose","admin_area_1":"CA","postal_code":"95131","country_code":"US"}},"payments":{"captures":[{"id":"8UW46633K9182040G","status":"PENDING","status_details":{"reason":"RECEIVING_PREFERENCE_MANDATES_MANUAL_ACTION"},"amount":{"currency_code":"USD","value":"52.84"},"final_capture":true,"seller_protection":{"status":"ELIGIBLE","dispute_categories":["ITEM_NOT_RECEIVED","UNAUTHORIZED_TRANSACTION"]},"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v2\/payments\/captures\/8UW46633K9182040G","rel":"self","method":"GET"},{"href":"https:\/\/api.sandbox.paypal.com\/v2\/payments\/captures\/8UW46633K9182040G\/refund","rel":"refund","method":"POST"},{"href":"https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/94E83796AH3100904","rel":"up","method":"GET"}],"create_time":"2021-05-04T19:54:16Z","update_time":"2021-05-04T19:54:16Z"}]}}],"links":[{"href":"https:\/\/api.sandbox.paypal.com\/v2\/checkout\/orders\/94E83796AH3100904","rel":"self","method":"GET"}],"id":"94E83796AH3100904","intent":"CAPTURE","payer":{"name":{"given_name":"John","surname":"Doe"},"email_address":"sb-oce6l1500772@personal.example.com","payer_id":"3PEZDBABAYT68","address":{"country_code":"US"}},"status":"COMPLETED"}
';

$string=json_decode($string, true);
$outer_arr = $string["resource"]["purchase_units"][0]["custom_id"];
echo $outer_arr;print_r($string);
/*foreach($outer_arr as $key => $val) {
    echo "Clave: "; print($key);  echo "<br>";// "kanye"
    echo "val: "; print_r($val);
    echo "<br>"; // Array ( [0] => Kanya [1] => Janaye [2] => Kayne [3] => Kane )
}*/
 ?>