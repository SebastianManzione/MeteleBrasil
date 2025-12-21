<?php 
function getUrlGeoUser(){
if ( !is_bot($_SERVER['HTTP_USER_AGENT']) ) {
$theip = $_SERVER["REMOTE_ADDR"];
if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {

    $theip = $_SERVER["HTTP_X_FORWARDED_FOR"];

}
if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
    $theip = $_SERVER["HTTP_CLIENT_IP"];
}
$realip = substr($theip, 0, 250);
$url='http://www.geoplugin.net/php.gp?ip=' . $realip;
$_SESSION["realIP"]=$realip;
return($url);
  }
}

function geoLocalizacionIp($url, $option, $cCode)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    $data= unserialize($data);
    //print_r( $data);
  $countryCode=$data["geoplugin_countryCode"];
if ($option==(-5)) {
  $countryCode=$cCode;
}
    
        $geoLocalizacionIp=Array();
    $geoLocalizacionIp["longitud"]=$data["geoplugin_longitude"];
    $geoLocalizacionIp["latitud"]=$data["geoplugin_latitude"];
      $geoLocalizacionIp["ciudad"]=$data["geoplugin_city"];
    
    switch ($countryCode) {
      case 'AR':
      $geoLocalizacionIp['nombre_pais'] ="Argentina";
      $geoLocalizacionIp['idPais'] = 270;
      $geoLocalizacionIp['sym'] = "AR$"; 
      $geoLocalizacionIp["countryCode"] =$countryCode; 
      $geoLocalizacionIp["lang"] ="ES"; 
      $geoLocalizacionIp["langFunny"] ="ESPAﾃ前L"; 
        break; 

        case 'BR':
      $geoLocalizacionIp['nombre_pais'] ="Brasil";
      $geoLocalizacionIp['idPais'] = 283;
      $geoLocalizacionIp['sym'] = "R$"; 
   
      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] = "PT";
         $geoLocalizacionIp["langFunny"] ="PORTUGUES";
        break;
      
       /* case 'PY':
      $geoLocalizacionIp['nombre_pais'] ="Paraguay";
      $geoLocalizacionIp['idPais'] = 225;
      $geoLocalizacionIp['sym'] = "G$"; 
 
      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] ="GU";
         $geoLocalizacionIp["langFunny"] ="GUARANI";
        break; */

        case 'CL':
      $geoLocalizacionIp['nombre_pais'] ="Chile";
      $geoLocalizacionIp['idPais'] = 271;
      $geoLocalizacionIp['sym'] = "CL$"; 

      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] ="ES";
         $geoLocalizacionIp["langFunny"] ="ESPAﾃ前L";
        break;

      default:
        $geoLocalizacionIp[0] =$data["geoplugin_countryCode"];
      $geoLocalizacionIp['nombre_pais'] ="Desconocido";
      $geoLocalizacionIp['idPais'] = 188;
      $geoLocalizacionIp['sym'] = 'U$S'; 
       $geoLocalizacionIp["countryCode"] = $countryCode; 
      $geoLocalizacionIp["lang"] ="EN";
       $geoLocalizacionIp["langFunny"] ="INGLES";
        break;


    } return $geoLocalizacionIp;
}

function getGeolocalizacionData()
{
    // Verificar si ya tenemos los datos en la sesión
   // if (isset($_SESSION['geoFinal']) && !empty($_SESSION['geoFinal'])) {
//        return $_SESSION['geoFinal'];
 //   }

    // Detectar IP
    $ip = $_SERVER['REMOTE_ADDR'];
    if ($ip == '127.0.0.1' || $ip == '::1') {
        $ip = '186.22.17.78'; // IP de prueba
    }

    // Geolocalización real con ip-api
    try {
        // Configurar contexto con timeout para evitar que se cuelgue
        $context = stream_context_create([
            'http' => [
                'timeout' => 5, // 5 segundos de timeout
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ]);

        $geoJson = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city,lat,lon,timezone,currency", false, $context);

        if ($geoJson === false) {
            throw new Exception("No se pudo conectar a la API de geolocalización");
        }

        $geo = json_decode($geoJson, true);

        if ($geo === null) {
            throw new Exception("Error al decodificar respuesta JSON");
        }

        if (!isset($geo['status']) || $geo['status'] !== 'success') {
            throw new Exception("API retornó status: " . ($geo['status'] ?? 'desconocido'));
        }

    } catch (Exception $e) {
        // Log del error (opcional)
        $log_message = "[" . date('Y-m-d H:i:s') . "] GEOLOCALIZACION FALLIDA - IP: {$ip} - Error: " . $e->getMessage() . "\n";
        @file_put_contents(__DIR__ . '/../../error_log', $log_message, FILE_APPEND);

        // Valores por defecto para Argentina
        $geo = [
            'status' => 'success',
            'country' => 'Argentina',
            'countryCode' => 'AR',
            'city' => 'Buenos Aires',
            'lat' => -34.6037,
            'lon' => -58.3816,
            'timezone' => 'America/Argentina/Buenos_Aires',
            'currency' => 'ARS'
        ];
    }

    $currencyISO = $geo['currency'] ?? 'USD';
    $countryCode = $geo['countryCode'] ?? 'US';

    require_once("moneda.php");
    $monedas = getMonedas();

    $idMoneda = 188;
    $symbolMoneda = 'U$D';
    $currencyISOFinal = 'USD';

    foreach ($monedas as $moneda) {
        if ($moneda['CurrencyISO'] === $currencyISO) {
            $idMoneda = $moneda['idMoneda'];
            $symbolMoneda = $moneda['Symbol'];
            $currencyISOFinal = $moneda['CurrencyISO'];
            break;
        }
    }

    $paisesIdioma = [
        'AR' => 'ES', 'ES' => 'ES', 'MX' => 'ES', 'CO' => 'ES', 'CL' => 'ES',
        'PE' => 'ES', 'VE' => 'ES', 'EC' => 'ES', 'GT' => 'ES', 'CU' => 'ES',
        'BO' => 'ES', 'DO' => 'ES', 'HN' => 'ES', 'PY' => 'ES', 'SV' => 'ES',
        'NI' => 'ES', 'CR' => 'ES', 'PA' => 'ES', 'UY' => 'ES',
        'BR' => 'PT', 'PT' => 'PT',
        'IT' => 'IT',
    ];

    $langCodigo = $paisesIdioma[$countryCode] ?? 'EN';

    $langNombres = [
        'ES' => 'ESPAÑOL',
        'PT' => 'PORTUGUES',
        'IT' => 'ITALIANO',
        'EN' => 'INGLES'
    ];

    // Armar array final
    $geoFinal = [
        'longitud' => $geo['lon'] ?? null,
        'latitud' => $geo['lat'] ?? null,
        'ciudad' => $geo['city'] ?? null,
        '0' => null,
        'nombre_pais' => $geo['country'] ?? 'Desconocido',
        'idPais' => 270, // AR por defecto
        'idMoneda' => $idMoneda,
        'sym' => $symbolMoneda,
        'currencyISO' => $currencyISOFinal,
        'countryCode' => $countryCode,
        'lang' => $langCodigo,
        'langFunny' => $langNombres[$langCodigo]
    ];

    // Guardar en sesión
    $_SESSION['geoFinal'] = $geoFinal;

    return $geoFinal;
}
 ?>