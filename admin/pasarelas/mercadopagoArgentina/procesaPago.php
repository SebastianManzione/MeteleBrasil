<?php
// MercadoPago preferencia: usa SDK si está disponible, REST si no
include("config.php");

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require $autoload;
    MercadoPago\SDK::setAccessToken($accessTokenML);

    $preference = new MercadoPago\Preference();
    $item = new MercadoPago\Item();
    $item->title = "Reserva " . $codigoAmigable . " en meteleargentina.com";
    $item->quantity = 1;
    $item->unit_price = (float)$totalMercadopagoArgentina;
    $preference->external_reference = $idReserva;

    $bkurls = $parametros[0]["site"] . "/consultaReserva?reserva=" . $codigoAmigable;
    $preference->back_urls = [
        "success" => $bkurls,
        "failure" => $bkurls,
        "pending" => $bkurls
    ];
    $preference->auto_return = "approved";
    $preference->items = [$item];
    $preference->save();
} else {
    // Fallback sin SDK: usar REST API para crear la preferencia
    $bkurls = $parametros[0]["site"] . "/consultaReserva?reserva=" . $codigoAmigable;
    $payload = [
        'items' => [[
            'title' => "Reserva " . $codigoAmigable . " en meteleargentina.com",
            'quantity' => 1,
            'currency_id' => 'ARS',
            'unit_price' => (float)$totalMercadopagoArgentina,
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
    $resp = curl_exec($ch);
    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception('Error creando preferencia MercadoPago (REST): ' . $err);
    }
    curl_close($ch);
    $preference = json_decode($resp);
    if (!$preference || !isset($preference->init_point)) {
        throw new Exception('Respuesta inválida de MercadoPago al crear preferencia.');
    }
}
?>