<?php
$host= gethostname();


  
if ($host=="server") {
  $_SESSION["parametros"]["pathSv"]="reservate/";
$_SESSION["parametros"]["pathAdmin"]="admin/";
}
else{
$_SESSION["parametros"]["pathSv"]="";
$_SESSION["parametros"]["pathAdmin"]="admin/";

  
}








function is_bot($user_agent) {
 
    $botRegexPattern = "(googlebot\/|Googlebot\-Mobile|Googlebot\-Image|Google favicon|Mediapartners\-Google|bingbot|slurp|java|wget|curl|Commons\-HttpClient|Python\-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST\-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub\.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum\.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips\-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail\.RU_Bot|discobot|heritrix|findthatfile|europarchive\.org|NerdByNature\.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb\-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web\-archive\-net\.com\.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks\-robot|it2media\-domain\-crawler|ip\-web\-crawler\.com|siteexplorer\.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki\-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e\.net|GrapeshotCrawler|urlappendbot|brainobot|fr\-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf\.fr_bot|A6\-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive\.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j\-asr|Domain Re\-Animator Bot|AddThis|YisouSpider|BLEXBot|YandexBot|SurdotlyBot|AwarioRssBot|FeedlyBot|Barkrowler|Gluten Free Crawler|Cliqzbot)";
 
     return preg_match("/{$botRegexPattern}/", $user_agent);
 
}



function alertar($mensaje, $tipo) {
    ?>
    <script>
        Swal.fire({
            title: "<?= $mensaje ?>",
            icon: "<?= $tipo ?>"
        }).then(() => {
            window.location.replace("<?= $_SERVER['PHP_SELF'] ?>");
        });
    </script>
    <?php
}
  
  
function alertar2($mensaje, $tipo) {
    ?>
    <script>
        Swal.fire({
            title: "<?= $mensaje ?>",
            icon: "<?= $tipo ?>"
        }).then(() => {
            window.location.reload();
        });
    </script>
    <?php
}

function alertar_redirect($mensaje, $tipo, $url) {
  ?>
  <script>
    Swal.fire({
      title: "<?= $mensaje ?>",
      icon: "<?= $tipo ?>"
    }).then(() => {
      window.location.replace("<?= $url ?>");
    });
  </script>
  <?php
}
  
  
  function redireccionar($url){
      
  
  echo ("<script>
  
  
      location.href='$url'</script>");
  }
  
  function redireccionarLento($url){
      
  
  echo ('<script>
  
  setTimeout(function(){location.href="'.$url.'";} , 2500);   
  
  
      </script>');
  }
  
  function preguntar(){
      echo "<script>Swal.fire({
    title: 'Are you sure?',
    text: 'You wont be able to revert this!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.value) {
      Swal.fire(
        'Deleted!',
        'Your file has been deleted.',
        'success'
      )
    }
  })</script>";
    
  }
  function retornaDiaSemana($fecha){


$i = strtotime($fecha);

return jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 );

}

function CalculaDias($fecha_inicial,$fecha_final)
{

$dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
$dias = abs($dias); 
$dias = floor($dias);
return $dias+1;
}
function SumaFecha($fecha, $dias){
  
  $fechanew=date("Y-m-d",strtotime($fecha."+ ".$dias." days"));
  return $fechanew ;

}


/* Función que elimina los acantos y letras ñ*/
function quitar_acentos($cadena){

    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    return utf8_encode($cadena);
}


?>