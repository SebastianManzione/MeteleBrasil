<?php
header("Content-Type: application/json");

$creds = require __DIR__ . '/config/creds_loader.php';
$dbHost = $creds['db']['host'] ?? 'localhost';
$dbUser = $creds['db']['user'] ?? '';
$dbPass = $creds['db']['pass'] ?? '';
$dbName = $creds['db']['name'] ?? '';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($mysqli->connect_error) {
    die(json_encode(["error" => $mysqli->connect_error]));
}

$result = $mysqli->query("SELECT COUNT(*) as total FROM servicio_img");
$row = $result->fetch_assoc();

die(json_encode(["total" => $row["total"]]));
?>