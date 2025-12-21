<?php
include('config/mercadopago.php');
include('config/db.php');

$title = $_POST['title'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$currency_id = $_POST['currency_id'] ?? '';
$unit_price = $_POST['unit_price'] ?? '';
$reserva = $_POST['reserva'] ?? '';
$verifica_pagamento = $_POST['verifica_pagamento'] ?? '';
$url_atual = $_POST['url_atual'] ?? '';


if ($currency_id === 'ARS') {
    $accessToken = MercadoPagoConfig::getCredentials('AR')['access_token'];
    $returnUrl = "https://metelebrasil.com/mercadopago_webhook_ar.php";
} else {
    $accessToken = MercadoPagoConfig::getCredentials('BR')['access_token'];
    $returnUrl = "https://metelebrasil.com/mercadopago_webhook_br.php";
}

// === VERIFICAR PAGAMENTO EXISTENTE ===
if ($verifica_pagamento) {
    $reservaCode = $_POST['reservaCode'] ?? '';
    $reservaQuery = "SELECT * FROM reservas WHERE codigoAmigable = '$reservaCode'";
    $reserva = $mysqli->query($reservaQuery)->fetch_assoc();
    // Verificar estado
    if ($reserva['idEstado'] === 3) {
        $status = true;
        $retorno = $reservaCode;
    } else {
        $status = false;
        $retorno = "Pagamento pendente ou recusado: {$reservaCode}";
    }

    echo json_encode([
        'status' => $status,
        'retorno' => $retorno,
        'url_atual' => $url_atual,
    ]);
    exit;
}

// === CRIAR NOVA PREFERÊNCIA ===
$preferenceData = [
    'items' => [
        [
            'title' => "$title - [{$reserva}]",
            'description' => "Reserva {$reserva}",
            'quantity' => (int)$quantity,
            'currency_id' => $currency_id,
            'unit_price' => (float)$unit_price
        ]
    ],
    'back_urls' => [
        'success' => $url_atual,
        'failure' => $url_atual,
        'pending' => $url_atual
    ],
    'auto_return' => 'approved',

    // 🔹 NUEVO: vincula la reserva y activa webhook automático
    'external_reference' => $reserva,
    'notification_url' => $returnUrl
];

// === CHAMADA API PARA CRIAR PREFERÊNCIA ===
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken
    ],
    CURLOPT_POSTFIELDS => json_encode($preferenceData)
]);

$response = curl_exec($curl);
curl_close($curl);

$preference = json_decode($response, true);

if (!empty($preference['id'])) {
    $status = true;
    $retorno = $preference;
} else {
    $status = false;
    $retorno = 'Erro ao criar a preferência: ' . $response;
}

echo json_encode([
    'status' => $status,
    'retorno' => $retorno,
    'url_atual' => $url_atual,
]);
