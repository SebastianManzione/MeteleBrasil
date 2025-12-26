<?php
// MercadoPago Brasil: usa SDK si disponible, REST si no
include("config.php");

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
    MercadoPago\SDK::setAccessToken($accessTokenML);

    $amountBr = round((float)$totalMercadopagoBrasil, 2);
    if ($amountBr <= 0) {
        throw new Exception('Monto a pagar inválido para MP Brasil (<= 0). Verifica total y comprobantes.');
    }

    $preferenceBr = new MercadoPago\Preference();
    $item = new MercadoPago\Item();
    $item->title = "Reserva " . $codigoAmigable . " en metelebrasil.com";
    $item->quantity = 1;
    $item->unit_price = $amountBr;
    $preferenceBr->external_reference = $idReserva;

    $bkurls = $parametros[0]["site"] . "/consultaReserva?reserva=" . $codigoAmigable;
    $preferenceBr->back_urls = [
        "success" => $bkurls,
        "failure" => $bkurls,
        "pending" => $bkurls
    ];
    $preferenceBr->auto_return = "approved";
    $preferenceBr->items = [$item];
    $preferenceBr->save();
} else {
    // Fallback REST
    $bkurls = $parametros[0]["site"] . "/consultaReserva?reserva=" . $codigoAmigable;
    $amountBr = round((float)$totalMercadopagoBrasil, 2);
    if ($amountBr <= 0) {
        throw new Exception('Monto a pagar inválido para MP Brasil (<= 0). Verifica total y comprobantes.');
    }

    $payload = [
        'items' => [[
            'title' => "Reserva " . $codigoAmigable . " en metelebrasil.com",
            'quantity' => 1,
            'currency_id' => 'BRL',
            'unit_price' => $amountBr,
        ]],
        'external_reference' => (string)$idReserva,
        'auto_return' => 'approved',
        'back_urls' => [
            'success' => $bkurls,
            'failure' => $bkurls,
            'pending' => $bkurls,
        ],
    ];

    $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessTokenML,
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Dev env
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        error_log("MP Brasil REST error: " . $err);
        throw new Exception('Error creando preferencia MP Brasil (REST): ' . $err);
    }
    curl_close($ch);
    
    // Debug: registra respuesta para entender qué regresa MP
    error_log("MP Brasil respuesta HTTP " . $httpCode . ": " . substr($resp, 0, 1000));
    
    // Si el código HTTP no es 2xx, es un error
    if ($httpCode < 200 || $httpCode >= 300) {
        error_log("MP Brasil: Error HTTP " . $httpCode . " en respuesta. Body: " . $resp);
        throw new Exception('Error HTTP ' . $httpCode . ' de MP Brasil: ' . substr($resp, 0, 200));
    }
    
    $response = json_decode($resp);
    if (!$response) {
        error_log("MP Brasil JSON decode failed: " . json_last_error_msg() . " en: " . $resp);
        throw new Exception('Respuesta inválida JSON de MP Brasil.');
    }
    
    // Valida init_point en múltiples rutas (raíz, preference, result)
    $initPoint = null;
    if (isset($response->init_point)) {
        $initPoint = $response->init_point;
    } elseif (isset($response->preference->init_point)) {
        $initPoint = $response->preference->init_point;
    } elseif (isset($response->result->init_point)) {
        $initPoint = $response->result->init_point;
    }
    
    if (!$initPoint) {
        error_log("MP Brasil: init_point no encontrado. Respuesta completa: " . json_encode($response));
        throw new Exception('init_point no encontrado en respuesta de MP Brasil. Ver logs.');
    }
    
    // Simula objeto del SDK con propiedad init_point
    $preferenceBr = (object)['init_point' => $initPoint];
}
?>