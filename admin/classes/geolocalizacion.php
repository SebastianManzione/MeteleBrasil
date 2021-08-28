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
      $geoLocalizacionIp["langFunny"] ="ESPAÑOL"; 
        break; 

        case 'BR':
      $geoLocalizacionIp['nombre_pais'] ="Brasil";
      $geoLocalizacionIp['idPais'] = 283;
      $geoLocalizacionIp['sym'] = "R$"; 
   
      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] = "PT";
         $geoLocalizacionIp["langFunny"] ="PORTUGUES";
        break;
      
        case 'PY':
      $geoLocalizacionIp['nombre_pais'] ="Paraguay";
      $geoLocalizacionIp['idPais'] = 225;
      $geoLocalizacionIp['sym'] = "G$"; 
 
      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] ="GU";
         $geoLocalizacionIp["langFunny"] ="GUARANI";
        break;

        case 'CL':
      $geoLocalizacionIp['nombre_pais'] ="Chile";
      $geoLocalizacionIp['idPais'] = 271;
      $geoLocalizacionIp['sym'] = "CL$"; 

      $geoLocalizacionIp["countryCode"] = $countryCode; 
        $geoLocalizacionIp["lang"] ="ES";
         $geoLocalizacionIp["langFunny"] ="ESPAÑOL";
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

 ?>