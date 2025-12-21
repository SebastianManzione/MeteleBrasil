<?php
include('config/mercadopago.php');
include('admin/classes/generador_aleatorio.php');
include('admin/classes/comprobantes.php');
include('config/db.php');
include('admin/classes/convierte_monedas.php');

/**
 * === MAIN WEBHOOK HANDLER ===
 */
function processWebhook($country): void
{


    $accessToken = MercadoPagoConfig::getCredentials($country)['access_token'];
    file_put_contents(__DIR__ . "/logs/wh/webhook_log.txt", date('Y-m-d H:i:s') . " TOKEN: " . $accessToken . "\n", FILE_APPEND);

    $data = readWebhookBody();
    if (!isset($data['type'])) {
        sendJson(['error' => 'Missing type'], 400);
    }

    switch ($data['type']) {
        case 'payment':
            handlePaymentEvent($data, $accessToken, $country);
            break;
        default:
            sendJson(['message' => 'Unhandled event type'], 200);
    }
}

/**
 * === FUNCIONES COMUNES ===
 */
function readWebhookBody(): array
{
    $raw = file_get_contents("php://input");
    // file_put_contents(__DIR__ . "/../logs/wh/webhook_log.txt", date('Y-m-d H:i:s') . " RAW: " . $raw . "\n", FILE_APPEND);
    return json_decode($raw, true) ?? [];
}

function handlePaymentEvent(array $data, string $accessToken, string $country): void
{
    global $mysqli;

    $paymentId = $data['data']['id'] ?? null;
    if (!$paymentId)  sendJson(['error' => 'Missing payment ID'], 400);

    $payment = getPaymentInfo($paymentId, $accessToken);
    if (!$payment)  sendJson(['error' => 'Invalid payment data'], 400);

    if ($payment['status'] === 'approved') {
        $externalReference = $payment['external_reference'] ?? null;
        if (!$externalReference)  sendJson(['error' => 'Missing external_reference'], 400);

        confirmReservation($externalReference, $mysqli);
        $idReserva = getReservationId($externalReference, $mysqli);
        if (!$idReserva)  sendJson(['error' => 'Reservation not found'], 404);

        $total = $payment['transaction_amount'];
        $totalDolares = ConvierteMoneda($country == "AR" ? 270:283, 188, $total);
        insertaComprobante($idReserva, $total, 1, 270, $paymentId, $totalDolares, 1);
        sendJson(['status' => 'approved', 'country' => $country], 200);
    } else {
        sendJson(['status' => $payment['status'], 'country' => $country], 200);
    }
}

function getPaymentInfo(string $paymentId, string $accessToken): ?array
{
    $url = "https://api.mercadopago.com/v1/payments/{$paymentId}";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer {$accessToken}"]
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response ? json_decode($response, true) : null;
}

function confirmReservation(string $externalReference, mysqli $mysqli): void
{
    $stmt = $mysqli->prepare("UPDATE reservas SET idEstado = 3 WHERE codigoAmigable = ?");
    $stmt->bind_param('s', $externalReference);
    $stmt->execute();
}

function getReservationId(string $externalReference, mysqli $mysqli): ?int
{
    $stmt = $mysqli->prepare("SELECT idReserva FROM reservas WHERE codigoAmigable = ?");
    $stmt->bind_param('s', $externalReference);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_assoc()['idReserva'] ?? null;
}

function sendJson(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
