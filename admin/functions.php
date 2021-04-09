
<?php 

include('conectar.php');
function redireccionar($url){
	

echo ("<script>location.href='$url'</script>");
}


function alertar($mensaje, $tipo){

?>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<?php  echo "<script> Swal.fire('".$mensaje."','','".$tipo."');</script>";
 ob_flush();
        flush();sleep(3);
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


function is_bot($user_agent) {
 
    $botRegexPattern = "(googlebot\/|Googlebot\-Mobile|Googlebot\-Image|Google favicon|Mediapartners\-Google|bingbot|slurp|java|wget|curl|Commons\-HttpClient|Python\-urllib|libwww|httpunit|nutch|phpcrawl|msnbot|jyxobot|FAST\-WebCrawler|FAST Enterprise Crawler|biglotron|teoma|convera|seekbot|gigablast|exabot|ngbot|ia_archiver|GingerCrawler|webmon |httrack|webcrawler|grub\.org|UsineNouvelleCrawler|antibot|netresearchserver|speedy|fluffy|bibnum\.bnf|findlink|msrbot|panscient|yacybot|AISearchBot|IOI|ips\-agent|tagoobot|MJ12bot|dotbot|woriobot|yanga|buzzbot|mlbot|yandexbot|purebot|Linguee Bot|Voyager|CyberPatrol|voilabot|baiduspider|citeseerxbot|spbot|twengabot|postrank|turnitinbot|scribdbot|page2rss|sitebot|linkdex|Adidxbot|blekkobot|ezooms|dotbot|Mail\.RU_Bot|discobot|heritrix|findthatfile|europarchive\.org|NerdByNature\.Bot|sistrix crawler|ahrefsbot|Aboundex|domaincrawler|wbsearchbot|summify|ccbot|edisterbot|seznambot|ec2linkfinder|gslfbot|aihitbot|intelium_bot|facebookexternalhit|yeti|RetrevoPageAnalyzer|lb\-spider|sogou|lssbot|careerbot|wotbox|wocbot|ichiro|DuckDuckBot|lssrocketcrawler|drupact|webcompanycrawler|acoonbot|openindexspider|gnam gnam spider|web\-archive\-net\.com\.bot|backlinkcrawler|coccoc|integromedb|content crawler spider|toplistbot|seokicks\-robot|it2media\-domain\-crawler|ip\-web\-crawler\.com|siteexplorer\.info|elisabot|proximic|changedetection|blexbot|arabot|WeSEE:Search|niki\-bot|CrystalSemanticsBot|rogerbot|360Spider|psbot|InterfaxScanBot|Lipperhey SEO Service|CC Metadata Scaper|g00g1e\.net|GrapeshotCrawler|urlappendbot|brainobot|fr\-crawler|binlar|SimpleCrawler|Livelapbot|Twitterbot|cXensebot|smtbot|bnf\.fr_bot|A6\-Indexer|ADmantX|Facebot|Twitterbot|OrangeBot|memorybot|AdvBot|MegaIndex|SemanticScholarBot|ltx71|nerdybot|xovibot|BUbiNG|Qwantify|archive\.org_bot|Applebot|TweetmemeBot|crawler4j|findxbot|SemrushBot|yoozBot|lipperhey|y!j\-asr|Domain Re\-Animator Bot|AddThis|YisouSpider|BLEXBot|YandexBot|SurdotlyBot|AwarioRssBot|FeedlyBot|Barkrowler|Gluten Free Crawler|Cliqzbot)";
 
     return preg_match("/{$botRegexPattern}/", $user_agent);
 
}



function ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $valor){

include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"SELECT * FROM moneda_cambio sv
	

      WHERE idMonedaCambio = 1");

    $result=mysqli_num_rows($query);
     
     
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

     $USS=$data["dolar"];
     $ARS=$data["pesoArg"];
     $BRL=$data["rs"];
     $GUAR=$data["guarani"];
     $PCH=$data["pesoCh"];
     $EURR=$data["euro"];
        }
        
    }
  




switch ($idMonedaOrigen) {
case 188:
		# dolar americano
	$dolares=$valor;
	$euros=$valor*$EURR;

	$reales=($valor)*$BRL;
	$guaranis=($valor)*$GUAR;
	$pesoArg=($valor)*$ARS;
	$pesoCh=($valor)*$PCH;

		break;
		#fin dolares
case 213:
		# euro
	$dolares=$valor/$EURR;
	$euros=$valor;
	$reales=($valor/$EURR)*$BRL;
	$guaranis=($valor/$EURR)*$GUAR;
	$pesoArg=($valor/$EURR)*$ARS;
	$pesoCh=($valor/$EURR)*$PCH;

		break;
		#fin euro

case 270:
		# Peso argentino
	$reales=($valor/$ARS)*$BRL;
	$guaranis=($valor/$ARS)*$GUAR;
	$dolares=($valor/$ARS);
	$euros=($valor/$ARS)*$EURR;
	$pesoArg=$valor;
	$pesoCh=($valor/$ARS)*$PCH;

		break;
		#fin peso arg
case 283:
		# Real brasilero
	$reales=$valor;
	$guaranis=($valor/$BRL)*$GUAR;
	$dolares=($valor/$BRL)*$USS;
	$euros=($valor/$BRL)*$EURR;
	$pesoArg=($valor/$BRL)*$ARS;
	$pesoCh=($valor/$BRL)*$PCH;
	
		break;
		#fin real
case 225:
		# Guaranis
	$reales=($valor/$GUAR)*$BRL;
	$guaranis=($valor);
	$dolares=($valor/$GUAR)*$USS;
	$euros=($valor/$GUAR)*$EURR;
	$pesoArg=($valor/$GUAR)*$ARS;
		$pesoCh=($valor/$GUAR)*$PCH;
	
		break;
		#fin guaranis
case 271:
		# Peso chileno
	$chilenos=($valor/$PCH)*$PCH;
	$reales=($valor/$PCH)*$BRL;
	$guaranis=($valor/$PCH);
	$dolares=($valor/$PCH)*$USS;
	$euros=($valor/$PCH)*$EURR;
	$pesoArg=($valor/$PCH)*$ARS;
	$pesoCh=($valor);
		break;
	default:
		# code...
		break;
}

	switch ($idMonedaDestino) {
		case 188:
			return round($dolares,2, PHP_ROUND_HALF_UP);

		break;
		
		case 213:
		return round($euros,2, PHP_ROUND_HALF_UP);
		
		break;	
		
		case 225:
		return round($guaranis,2, PHP_ROUND_HALF_UP);
		
		break;
		
		case 270:
		return round($pesoArg,2, PHP_ROUND_HALF_UP);
		
		break;
		
		case 271:
		return round($pesoCh,2, PHP_ROUND_HALF_UP);
			
		break;

		case 283:
		return round($reales,2, PHP_ROUND_HALF_UP);
			
		break;	
	
					}
mysqli_close($conection);
}



/* Función que elimina los acantos y letras ñ*/
function quitar_acentos($cadena){

    $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
    $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    return utf8_encode($cadena);
}
 
function NombreCategoria($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM categoria_servicio  WHERE idCatSrv=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nombre= $data2['nombre_categoria_servicio'];
				return $nombre;
			
		}
	}
	mysqli_close($conection);
}
function nViajeros($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM categoria_servicio  WHERE idCatSrv=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nombre= $data2['nViajeros'];
				return $nombre;
			
		}
	}
	mysqli_close($conection);
}


//**********************Le pasamos el id de servicio y nos devuelve su nombre*********************************************************************************
function NombreServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nombre= $data2['nombre_servicio'];
				return $nombre;
			
		}
	}
	mysqli_close($conection);
}
//**********************FIN           Le pasamos el id de servicio y nos devuelve su nombre*********************************************************************************//**********************Le pasamos el id de servicio y nos devuelve su nombre*********************************************************************************
function DescripcionServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$descripcion= $data2['descripcion_servicio'];
				return $descripcion;
			
		}
	}
	mysqli_close($conection);
}




function PaisesTelefonos(){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM country ORDER BY phonecode");
				$result2=mysqli_num_rows($query2);
				$datos=Array();
				$i=0;
	if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
				$datos[$i]=Array();
		  array_push($datos[$i],  
		  	$data2['id'],
		  	"+".$data2['phonecode'],
$data2['iso3']
		  );
		$i++;
		}return $datos;
	}
	mysqli_close($conection);
}





function DuracionServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				$duracion=Array();
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
				array_push($duracion,
					$data2['duracionMinima'],
					$data2['duracionMaxima']

				);
		
				
			
		}		return $duracion;
	}
	mysqli_close($conection);
}
//******************************************************
function AnticipacionReservaServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$anticipacion= $data2['anticipacionReserva'];
				return $anticipacion;
			
		}
	}
	mysqli_close($conection);
}



//******************************************************************
function IdiomaServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio_idiomas sv
				INNER JOIN idiomas idi on sv.idIdioma=idi.idIdioma WHERE sv.idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				$idiomas=array();
	if ($result2 >= 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			array_push($idiomas, $data2['nombre']);
				
			
		}
	}return $idiomas;
	mysqli_close($conection);
}

//*********************************************************************************************
	function PrecioSugeridoServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$precioSugerido= $data2['precioSugerido'];
				return $precioSugerido;
			
		}
	}
	mysqli_close($conection);
}

$precioFinal=0;
function PrecioSugeridoServicio2($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];

	$precioSugerido= ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido']);


$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;



$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			
				if ($data2['precioSugerido']==0) {
				$precioFinal="GRATIS";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido']== -5) {
					$precioFinal="NO SE PERMITEN";
					return $precioFinal;
			}
			else{
						return $sym." ".round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
			}
				
			
		}
	}
	mysqli_close($conection);
}

function PrecioSugeridoServicioInflado($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];

	$precioSugerido= ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido']);


$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;



$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			
				if ($data2['precioSugerido']==0) {
				$precioFinal="GRATIS";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido']== -5) {
					$precioFinal="NO SE PERMITEN";
					return $precioFinal;
			}
			else{
						return $sym." ".round(($precioFinal*1.136574321), 2, PHP_ROUND_HALF_EVEN);
			}
				
			
		}
	}
	mysqli_close($conection);
}

function PrecioSugeridoServicioBusqueda($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];

	$precioSugerido= ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido']);


$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;



$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			
				if ($data2['precioSugerido']==0) {
				$precioFinal="GRATIS";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido']== -5) {
					$precioFinal="NO SE PERMITEN";
					return $precioFinal;
			}
			else{
						return $sym." ".round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
			}
				
			
		}
	}
	mysqli_close($conection);
}





	function PrecioSugeridoServicio12($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];
			$precioSugerido= ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido12']);
			

$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;

$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			


			
			if ($data2['precioSugerido12'] == 0) {
				$precioFinal="FREE";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido12']== -5) {
					$precioFinal="NO SE PERMITEN";
					return $precioFinal;
			}
			else{
			return $sym." ".round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
			}
				
			
		}
	}
	mysqli_close($conection);
}

	function PrecioSugeridoServicio5($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];
			$precioSugerido= ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido5']);
							

$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;

$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			

			
				if ($data2['precioSugerido5'] == 0) {
				$precioFinal="FREE";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido5']== -5) {
					$precioFinal="NO SE PERMITEN";
					return $precioFinal;
			}
			else{
						return $sym." ".round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
			}
				
		}
	}
mysqli_close($conection);

}

	function PrecioSugeridoServicio3($idServicio, $idMonedaDestino, $impuestosPais, $sym){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$idMonedaOrigen=MonedaServicio($idServicio)[0];
			$precioSugerido = ConvierteMoneda($idMonedaOrigen,$idMonedaDestino, $data2['precioSugerido3']);
							

$impuestosServicio = DevuelveImpuestosServicio($idServicio);

$precioFinal=($precioSugerido*$impuestosServicio)+$precioSugerido;

$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);

$precioFinal=($precioSugerido*$comisionVentaServicio)+$precioFinal;

$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);

$precioFinal=($precioSugerido*$DevuelveComisionReservateServicio)+$precioFinal;

$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);

$precioFinal=($precioSugerido*$ComisionCompensatoria)+$precioFinal;

$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;
			

					if ($data2['precioSugerido3'] == 0) {
				$precioFinal="FREE";
					return $precioFinal;
			}
			elseif ($data2['precioSugerido3'] == -5) {
					$precioFinal="NO SE PERMITEN";
				return $precioFinal;
			}
			else{
						return  $sym." ".round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
			}
				

		
			
		}
	}
	mysqli_close($conection);
}
//*********************************************************************************************



function DescripcionCortaServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$descripcion= $data2['descripcion_corta'];
				return $descripcion;
			
		}
	}
	mysqli_close($conection);
}
//**********************FIN           Le pasamos el id de servicio y nos devuelve su nombre*********************************************************************************
function ObservacionesServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result=mysqli_num_rows($query);
				
	if ($result >0) {
			while ($data = mysqli_fetch_array($query)) {

			$observaciones= $data['observaciones'];
			
	
				return $observaciones;
			
		}
	}
	mysqli_close($conection);
}


function DocumentacionViajeroServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio  WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$documentacionViajero= $data2['documentacionViajero'];
				return $documentacionViajero;
			
		}
	}
	mysqli_close($conection);
}

//*************************************************************************************

function CategoriaServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio sv
				INNER JOIN categoria_servicio cs ON sv.idCategoria_servicio=cs.idCatSrv
					WHERE idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nomcatsrv= $data2['nombre_categoria_servicio'];
				return $nomcatsrv;
			
		}
	}
	mysqli_close($conection);
}


function DevuelveCategorias(){

	include($GLOBALS['path'].'/conectar.php');
   $query=mysqli_query($conection,"SELECT * FROM categoria_servicio WHERE idCatSrv>0");
				$result=mysqli_num_rows($query);
				$datos=Array();
				$i=0;
	if ($result > 1) {
			while ($data = mysqli_fetch_array($query)) {
				$datos[$i]=Array();
				array_push($datos[$i], 
				$data['idCatSrv'], //0
				$data['nombre_categoria_servicio'], //1
				$data['img_categoria_servicio'], //2
				$data['nViajeros'] //3
			);
				$i++;
			
			
			
		}
	}	return $datos;
	mysqli_close($conection);
}

function idCategoriaServicio($idServicio){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio sv
				INNER JOIN categoria_servicio cs ON sv.idCategoria_servicio=cs.idCatSrv
					WHERE sv.idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nomcatsrv= $data2['idCategoria_servicio'];
				return $nomcatsrv;
			
		}
	}
	mysqli_close($conection);
}
//***************************************************************************
function PaisServicio($id){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM servicio sv
				INNER JOIN servicio_ubicacion su ON sv.idServicio=su.idServicio

				 WHERE sv.idServicio=".$id);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
		$direccion=explode(",", $data2['direccion']);
			
				return $direccion[2];
			
		}
	}
	mysqli_close($conection);
}


//************************************tiramos precio, descuento %, descuento por monetario, y impuestos lo mismo, y nos devuelve un restultado***************************
function PrecioImpuestoDescuento($precio, $descuentoPorcentual, $descuentoMonetario , $impuestoPorcentual ,$impuestoMonetario){
	

switch ($precio) {
	case (-1):
		return "no admitido";
		break;
	case 0:
		return "gratis";
		break;
	case ($precio>0):
		$precio=($precio-($precio*$descuentoPorcentual)/100+$descuentoMonetario)+($precio*$impuestoPorcentual)/100+$impuestoMonetario;	
		return $precio;

	default:
		# code...
		break;
}
}
//************************************fin     tiramos precio, descuento %, descuento por monetario, y impuestos lo mismo, y nos devuelve un restultado***************************



function DevuelveMoneda($idMoneda){

	include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"SELECT * FROM moneda
	

      WHERE idMoneda = ".$idMoneda);

    $result=mysqli_num_rows($query);
     
     $datos=Array();
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

        array_push($datos, 
        	$data["idMoneda"], //0
        	 $data["CurrencyISO"], //1
        	  $data["CurrencyName"],  //2
        	  $data["Symbol"]   ); //3
        }
        return $datos;
    }
    else{
      return 0;
    }


mysqli_close($conection);
}



function MonedaServicio($idServicio){

	include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"SELECT * FROM servicio sv
	INNER JOIN moneda mo ON sv.idMoneda=mo.idMoneda

      WHERE idServicio = ".$idServicio);

    $result=mysqli_num_rows($query);
     
     $datos=Array();
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

        array_push($datos, 
        	$data["idMoneda"], //0
        	 $data["CurrencyISO"], //1 
        	 $data["CurrencyName"], //2
        	 $data["Symbol"]   ); //3
        }
        return $datos;
    }
    else{
      return 0;
    }

mysqli_close($conection);

}






//**********************impuestos y descuentos LE PASAMOS EL ID DEL SERVICIO Y EL PRECIO DE LA SALIDA Y NOS DEVUELVE EL RESULTADO*********************************************

function SumaImpuestosYDescuentos($idServicio, $precio){

	include($GLOBALS['path'].'/conectar.php');
	$impuestosYDescuentos=Array();
		$impuestoPorcentual=0.00;
		$impuestoMonetario=0.00;
		$query=mysqli_query($conection,"SELECT * FROM servicio_impuestos si INNER JOIN nombre_impuestos ni ON
			si.idImpuesto=ni.idImpuesto
			INNER JOIN simbolo_impuestos sim ON
			si.idSimboloImpuestos=sim.idSimboloImpuestos
		WHERE idServicio=".$idServicio);
		$result=mysqli_num_rows($query);
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
			
				switch ($data['idSimboloImpuestos']) {
						
						case 1:
						$impuestoPorcentual+=$data['valor'];
					
						break;	

					case 2:
						$impuestoMonetario+=$data['valor'];
						
						break;
				default:
					
						break;
				}
			}
			$impuestosYDescuentos[0]=$impuestoPorcentual;
			$impuestosYDescuentos[1]=$impuestoMonetario;
			 
				//$impuestos[$contador]=$data['nombre'];
				//$impuestos[$contador]=$data['valor'];
				//$impuestos[$contador]=$data['simbolo'];
			$query=mysqli_query($conection,"SELECT * FROM servicio_descuentos sd INNER JOIN nombre_descuentos nd ON
			sd.idDescuento=nd.idDescuento
			INNER JOIN simbolo_impuestos sim ON
			sd.idSimboloImpuestos=sim.idSimboloImpuestos
		WHERE idServicio=".$idServicio);
			$descuentoMonetario=0;
			$descuentoPorcentual=0;
		$result=mysqli_num_rows($query);
		
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

							switch ($data['idSimboloImpuestos']) {
					case 1:
						$descuentoPorcentual+=$data['valor'];
					
					break;	

					case 2:
						$descuentoMonetario+=$data['valor'];
					break;	
					default:
					
					break;
				}

			}
		}
		$impuestosYDescuentos[2]=$descuentoPorcentual;
		$impuestosYDescuentos[3]=$descuentoMonetario;
		return PrecioImpuestoDescuento($precio, $impuestosYDescuentos[2],$impuestosYDescuentos[3],$impuestosYDescuentos[0],$impuestosYDescuentos[1] );
	}
	mysqli_close($conection);
}

//**********************impuestos y descuentos LE PASAMOS EL ID DEL SERVICIO Y EL PRECIO DE LA SALIDA Y NOS DEVUELVE EL RESULTADO*********************************************

//**********************verServicio LE PASAMOS EL ID DEL SERVICIO Y NOS DEVUELVE UN ARRAY CON SUS DATOS**********************************************************************
function VerServicioNew($idServicio){
	include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio 
		
		WHERE idServicio=".$idServicio);

		$result=mysqli_num_rows($query);
	if ($result == 1) {
			while ($data = mysqli_fetch_array($query)) {

				return($data);
			}}
}


function VerServicio($idServicio){
	include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio  
		LEFT JOIN categoria_servicio ON servicio.idCategoria_servicio = categoria_servicio.idCatSrv  
		LEFT JOIN horarios ON servicio.idServicio = horarios.servicioId 
		WHERE idServicio=".$idServicio." ORDER BY idServicio DESC");
	 	$datos=array();

		$result=mysqli_num_rows($query);
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
			//$datos["id"]=$data['idCatSrv'];
			$lugaresReservados=LugaresOcupados($data['horarioId']);
			$datos[0]=$data['horarioId'];
			$datos[1]=$data['horaInicio'];
			$datos[2]=$data['horaFin'];

			$disponibilidad=$data['lugares']-$lugaresReservados;
		 if ($disponibilidad==0) {
				 	 $datos[3]= "LLENO"; 
				 }
				 else{
				 	$datos[3]=$disponibilidad;
				 }


			$datos[4]=PrecioImpuestoDescuento($data['pAdulto'],$descuentoPorcentual,$descuentoMonetario,$impuestoPorcentual,$impuestoMonetario );
			$datos[5]=PrecioImpuestoDescuento($data['pMenor12'],$descuentoPorcentual,$descuentoMonetario,$impuestoPorcentual,$impuestoMonetario ); 
			$datos[6]=PrecioImpuestoDescuento($data['pMenor5'],$descuentoPorcentual,$descuentoMonetario,$impuestoPorcentual,$impuestoMonetario ); 
			$datos[7]=PrecioImpuestoDescuento($data['pMenor3'],$descuentoPorcentual,$descuentoMonetario,$impuestoPorcentual,$impuestoMonetario );
			$datos[8]=date("d-m-Y", strtotime($data['fechaIn']));
			$datos[9]=date("d-m-Y", strtotime($data['fechaOut']));

		}
	}
	mysqli_close($conection);
}
//**********************verServicio LE PASAMOS EL ID DEL SERVICIO Y NOS DEVUELVE UN ARRAY CON SUS DATOS**********************************************************************

//**********************DevuelveFotosServicio LE PASAMOS EL ID DEL SERVICIO Y NOS DEVUELVE LAS FOTOS***********************************************************************************

function DevuelveFotosServicio($idServicio){
	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM img_servicio
			WHERE idImg=".$idServicio);
		$result=mysqli_num_rows($query);
		$fotos=Array();
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				array_push($fotos, $data['ruta']);
				
				//echo '<img src="./img/uploads/'.$data['ruta'].'" class="output_image_servicio" alt="Responsive image" />';

		}
		return $fotos;
	}
	mysqli_close($conection);
}

function DevuelveFotosCategoria($idCatSrv){
	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM categoria_servicio
			WHERE idCatSrv=".$idCatSrv);
		$result=mysqli_num_rows($query);
		$fotos=Array();
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				array_push($fotos, $data['img_categoria_servicio']);
				
				//echo '<img src="./img/uploads/'.$data['ruta'].'" class="output_image_servicio" alt="Responsive image" />';

		}
		return $fotos;
	}
	mysqli_close($conection);
}

//**********************FIN   DevuelveFotosServicio LE PASAMOS EL ID DEL SERVICIO Y NOS DEVUELVE LAS FOTOS*******************************************************************************

//*************CALCULA DIAS ENTRE 2 FECHAS***********************************************************************

function CalculaDias($fecha_inicial,$fecha_final)
{

$dias = (strtotime($fecha_inicial)-strtotime($fecha_final))/86400;
$dias = abs($dias); 
$dias = floor($dias);
return $dias+1;
}
//*************FIN    CALCULA DIAS ENTRE 2 FECHAS***********************************************************************

//*********LE PASAMOS UNA FECHA Y UNA CANTIDAD DE DIAS, Y NOS DEVUELVE LA FECHA CON LA CANTIDAD DE DIAS SUMADOS*********************
function SumaFecha($fecha, $dias){
	$fecha;$dias;
	$fechanew=date("Y-m-d",strtotime($fecha."+ 1 days"));
	return $fechanew ;

}
//*********FIN  LE PASAMOS UNA FECHA Y UNA CANTIDAD DE DIAS, Y NOS DEVUELVE LA FECHA CON LA CANTIDAD DE DIAS SUMADOS*********************

//*********LugaresOcupados 	le pasamos el horario id y nos devuelve cuantas plazas estan ocupadas *********************
function LugaresOcupados($horarioId){
include($GLOBALS['path'].'/conectar.php');

				$query=mysqli_query($conection,"SELECT * FROM reserva_horarios  WHERE horarioId=".$horarioId);
				$result=mysqli_num_rows($query);
				$lugaresReservados=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
		
			$idReserva=$data2["idReserva"];
			$pagos=DevuelveTotalComprobantesPagosReserva($idReserva,270);
			if ($pagos>0) {
					$lugaresReservados+= $data['cantAdultos'];
			$lugaresReservados+= $data['cant12'];
			$lugaresReservados+= $data['cant5'];
			$lugaresReservados+= $data['cant3'];
			}
		}
	}

	return $lugaresReservados;
	mysqli_close($conection);
}
//*********LugaresOcupados 	le pasamos el horario id y nos devuelve cuantas plazas estan ocupadas *********************


//*********LugaresLibres 	le pasamos el horarioid y la cantidad de reservados y nos devuelve cuantos libres quedan *********************
function LugaresLibres($horarioId, $reservados){
include($GLOBALS['path'].'/conectar.php');

				$query2=mysqli_query($conection,"SELECT * FROM horarios  WHERE horarioId=".$horarioId);
				$result2=mysqli_num_rows($query2);
				
		if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$lugaresLibres= $data2['lugares'];
			$lugaresLibres=$lugaresLibres-$reservados;
			
		}
	}
	return $lugaresLibres;
	mysqli_close($conection);
}
//*********FIN   LugaresLibres 	le pasamos el horarioid y la cantidad de reservados y nos devuelve cuantos libres quedan *********************



//****************precioAdicionales LE PASAMOS EL ID DE RESERVA Y NOS DEVUELVE EL COSTO DE LOS ADICIONALES************************

function precioAdicionales($idReserva){
include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"SELECT * FROM reserva_adicionales 
      WHERE idReserva = ".$idReserva);

    $result=mysqli_num_rows($query);
     
     $totalAdicionales=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

        $totalAdicionales+=$data['total'];      
        }
        return $totalAdicionales;
    }
    else{
      return 0;
    }
    mysqli_close($conection);
}
//****************FIN        precioAdicionales LE PASAMOS EL ID DE RESERVA Y NOS DEVUELVE EL COSTO DE LOS ADICIONALES************************


//LE PASAMOS EL ID DE RESERVA, Y NOS DEVUELVE EL PRECIO DE LA RESERVA SIN LOS ADICIONALES CONTRATADOS***********************
function precioReservaHorario($idReserva){
include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM reserva_horarios 

      WHERE idReserva = ".$idReserva);
$totalServicio=0;
    $result=mysqli_num_rows($query); 
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
       $totalServicio+= $data['total_reserva'];     
        }
        return $totalServicio; 
    }
    else{
     return (0);
    }
    mysqli_close($conection);
}
//FIN               LE PASAMOS EL ID DE RESERVA, Y NOS DEVUELVE EL PRECIO DE LA RESERVA SIN LOS ADICIONALES CONTRATADOS***********************


function DevuelveDatosReservaHorarios($idReserva){
include($GLOBALS['path'].'/conectar.php');
			$query2=mysqli_query($conection,"SELECT * FROM reserva_horarios rh INNER JOIN horarios hs ON rh.horarioId=hs.horarioId
		WHERE rh.idReserva='$idReserva' ");
		$result2=mysqli_num_rows($query2);
		$reserva=array();
		$i=0;
		if ($result2 > 0) {
			while ($data = mysqli_fetch_array($query2)) {
				$reserva[$i]=array();
			 array_push($reserva[$i], $data["servicioId"]);//0
			 array_push($reserva[$i], $data["horarioId"]);//1
			 array_push($reserva[$i], $data["cantAdultos"]);//2
			 array_push($reserva[$i], $data["cant12"]);//3
			 array_push($reserva[$i], $data["cant5"]);//4
			 array_push($reserva[$i], $data["cant3"]);//5
			 array_push($reserva[$i], $data["moneda"]);//6
			 array_push($reserva[$i], $data["cuponDescuento"]);//7
			 array_push($reserva[$i], $data["total"]);//8
			 array_push($reserva[$i], $data["moneda"]);//9
	array_push($reserva[$i], date_format(new DateTime($data["fechaIn"]), 'd-m-Y'));//10
array_push($reserva[$i], substr($data["horaInicio"], 0,5)); //11
array_push($reserva[$i], $data["idReservaHorario"]);//12
			
$i++;
			}
return $reserva;
		}
		mysqli_close($conection);
}


function DevuelveDatosReservaHorariosPaquetes($idReserva){
include($GLOBALS['path'].'/conectar.php');
			$query2=mysqli_query($conection,"SELECT * FROM reserva_horarios_paquetes rhp 
				
		WHERE rhp.idReserva='$idReserva' ");
		$result2=mysqli_num_rows($query2);
		$reserva=array();
		$i=0;
		if ($result2 > 0) {
			while ($data = mysqli_fetch_array($query2)) {
				$reserva[$i]=array();
			 array_push($reserva[$i], $data["servicioId"]);//0
			 array_push($reserva[$i], $data["idHorarioPaqueteSalida"]);//1
			 array_push($reserva[$i], $data["idHotel"]);//2
			 array_push($reserva[$i], $data["idHotelCuarto"]);//3
			 array_push($reserva[$i], $data["cantAdultos"]);//4
			 array_push($reserva[$i], $data["precioAdulto"]);//5
			array_push($reserva[$i], $data["cantAdultosAdicionales"]);//6
			 array_push($reserva[$i], $data["precioAdultoAdicional"]);//7
			 array_push($reserva[$i], $data["cantMenores"]);//8
			 array_push($reserva[$i], $data["precioMenor"]);//9
			 array_push($reserva[$i], $data["cuponDescuento"]);//10
	array_push($reserva[$i], "");	// array_push($reserva[$i], substr($data["horaSalida"], 0,5));//11
		array_push($reserva[$i], ""); // array_push($reserva[$i], date_format(new DateTime($data["fechaIn"]), 'd-m-Y'));//12
		  	  array_push($reserva[$i], $data["total"]);//13
		  	   array_push($reserva[$i], $data["idHorarioPaquete"]);//14
		  	      array_push($reserva[$i], $data["idReservaHorarioPaquete"]);//15
			
$i++;
			}
return $reserva;
		}
		mysqli_close($conection);
}


function DevuelveTotalAdicionalesReservaServicios($idReservaHorario){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM reserva_adicionales ra
		WHERE idReservaHorario=".$idReservaHorario);
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$totalAdicionales+= $data["total"];
			}
			return $totalAdicionales;
		}
		else{
			return 0;
		}

mysqli_close($conection);
}
function DevuelveTotalAdicionalesReservaPaquetes($idReservaHorario){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM reserva_adicionales ra
		WHERE idReservaHorarioPaquete=".$idReservaHorario);
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$totalAdicionales+= $data["total"];
			}
			return $totalAdicionales;
		}
		else{
			return 0;
		}

mysqli_close($conection);
}

function DevuelveTotalReserva($idReserva){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM reserva_adicionales ra
		WHERE idReserva=".$idReserva);
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$totalAdicionales+= $data["total"];
			}
			return $totalAdicionales+precioReservaHorario($idReserva);
		}
		else{
			return 0;
		}

mysqli_close($conection);
}

function DevuelveImpuestosServicio($idServicio){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio_impuestos
		WHERE idServicio=".$idServicio);
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		$datos;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$datos+= $data["valor"]/100;
			}
			
			
				return $datos;
			
		}
		else{
			return 0;
		}

mysqli_close($conection);
}


function DevuelveDescuentosServicio($idServicio){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio_descuentos
		WHERE idServicio=".$idServicio);
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$datos= $data["valor"]/100;
			}
			
			
				return $datos;
			
		}
		else{
			return 0;
		}

mysqli_close($conection);
}


function DevuelveComisionVentaServicio($idServicio){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio_comisiones sc
		INNER JOIN servicio sv ON sv.idServicio=sc.idServicio
		WHERE sv.idServicio=".$idServicio." AND sc.idComisiones=1");
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$datos= $data["comision"]/100;
			}
		
			
				return $datos;
			
		}
		else{
			return 0;
		}

mysqli_close($conection);
}

function DevuelveComisionReservateServicio($idServicio){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio_comisiones sc
		INNER JOIN servicio sv ON sv.idServicio=sc.idServicio
		WHERE sv.idServicio=".$idServicio." AND sc.idComisiones=2");
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$datos= $data["comision"]/100;
			}
		
			
				return $datos;
			
		}
		else{
			return 0;
		}

mysqli_close($conection);
}



function DevuelveComisionCompensatoria($idServicio){
	
			include($GLOBALS['path'].'/conectar.php');
	$query=mysqli_query($conection,"SELECT * FROM servicio_comisiones sc
		INNER JOIN servicio sv ON sv.idServicio=sc.idServicio
		WHERE sv.idServicio=".$idServicio." AND sc.idComisiones=3");
		$result=mysqli_num_rows($query);
		$totalAdicionales=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {

				$datos= $data["comision"]/100;
			}
			
		
				return $datos;
		

		}
		else{
			return 0;
		}

mysqli_close($conection);
}

function DevuelveComprobantesPagosReserva($idReserva){
include($GLOBALS['path'].'/conectar.php');
			$query2=mysqli_query($conection,"SELECT * FROM comprobante cp
		WHERE idReserva='$idReserva' ");
		$pagado=0;
		$result2=mysqli_num_rows($query2);
		$comprobantes=array();
		if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
			 array_push($comprobantes, 
			 	$data["idComprobante"], //0
			 	$data["total"],			//1
			 	$data["origenComprobante"], //2
$data["monedaComprobante"]//3


			 	); 

			}
return $comprobantes;
		}
		mysqli_close($conection);
}


function DevuelveTotalComprobantesPagosReserva($idReserva, $monedaDestino){
include($GLOBALS['path'].'/conectar.php');
		$query2=mysqli_query($conection,"SELECT * FROM comprobante cp
		WHERE idReserva='$idReserva' ");
		$pagado=0;
		$result2=mysqli_num_rows($query2);
		if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
				$monedaOrigen=$data2["monedaComprobante"];
				$pagado+= ConvierteMoneda($monedaOrigen , $monedaDestino, $data2["total"]);
			

			}

		}return $pagado;
		mysqli_close($conection);
}


function svAdicionales(){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM servicios_adicionales sa
	 where not EXISTS (select idServiciosAdicionales from servicios_adicionales_categoria sac where sac.idServiciosAdicionales = sa.idServiciosAdicionales AND sac.idCatSrv=1) 
	");
    $result=mysqli_num_rows($query);
    $svAdicionales=array();
    $i=0;
    if ($result > 0) {
    	  while ($data = mysqli_fetch_array($query)) {
    	  	  $svAdicionales[$i]=array();
    	  	array_push($svAdicionales[$i], $data['idServiciosAdicionales']);
			array_push($svAdicionales[$i], $data['nombre']);
				$i++;
		}   
    }
	return $svAdicionales;
	mysqli_close($conection);
}


function svAdicionalesNoIncluidosEnCategoria($idCatSrv){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM servicios_adicionales sa
	 where not EXISTS (select idServiciosAdicionales from servicios_adicionales_categoria sac where sac.idServiciosAdicionales = sa.idServiciosAdicionales AND sac.idCatSrv=$idCatSrv) 
	");
    $result=mysqli_num_rows($query);
    $svAdicionales=array();
    $i=0;
    if ($result > 0) {
    	  while ($data = mysqli_fetch_array($query)) {
    	  	  $svAdicionales[$i]=array();
    	  	array_push($svAdicionales[$i], $data['idServiciosAdicionales']);
			array_push($svAdicionales[$i], $data['nombre']);
				$i++;
		}   
    }
	return $svAdicionales;
	mysqli_close($conection);
}


function svAdicionalesCategoria($idCatSrv){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM servicios_adicionales_categoria sac INNER JOIN servicios_adicionales sa ON sac.idServiciosAdicionales=sa.idServiciosAdicionales WHERE sac.idCatSrv=$idCatSrv");
    $result=mysqli_num_rows($query);
    $svAdicionales=array();
    $i=0;
    if ($result > 0) {
    	  while ($data = mysqli_fetch_array($query)) {
    	  	  $svAdicionales[$i]=array();
    	  	array_push($svAdicionales[$i], $data['idServiciosAdicionales']);
			array_push($svAdicionales[$i], $data['nombre']);
			array_push($svAdicionales[$i], $data['idCatSrv']);
			array_push($svAdicionales[$i], $data['idServiciosAdicionalesCategoria']);
				$i++;
		}   
    }
	return $svAdicionales;
	mysqli_close($conection);
}

function EliminasvAdicionalesCategoria($idServiciosAdicionalesCategoria){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM servicios_adicionales_categoria where idServiciosAdicionalesCategoria=$idServiciosAdicionalesCategoria");
  
    if ($query) {
      
    }
	return $query;
	mysqli_close($conection);
}


function AgregasvAdicionalesCategoria($idServiciosAdicionales, $idCatSrv){
	include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"INSERT INTO servicios_adicionales_categoria (idCatSrv, idServiciosAdicionales) VALUES('$idCatSrv','$idServiciosAdicionales')");


if ($query) {
	return 1;
}
else
{return -5;}
mysqli_close($conection);
}

function AgregasvAdicional($nombre){
	include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"INSERT INTO servicios_adicionales ( nombre) VALUES('$nombre')");


if ($query) {
	return 1;
}
else
{return -5;}
mysqli_close($conection);
}


function EliminasvAdicional($idServiciosAdicionales){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM servicios_adicionales where idServiciosAdicionales=$idServiciosAdicionales");
  
    if ($query) {
      
    }
	return $query;
	mysqli_close($conection);
}

function EliminaServicio($idServicio){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM servicio where idServicio=$idServicio");
$query=mysqli_query($conection,"DELETE FROM servicios_adicionales_servicio where idServicio=$idServicio");

  $query=mysqli_query($conection,"DELETE FROM horarios where servicioId=$idServicio");




	return $query;
	mysqli_close($conection);
}

//LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LOS SV ADICIONALES QUE ESTAN INCLUIDOS DENTRO DEL SERVICIO********************************
function svAdicionalesIncluidos($idServicio){
$idCatSrv=idCategoriaServicio($idServicio);
	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,
	"SELECT * FROM servicios_adicionales_servicio sas 
INNER JOIN servicios_adicionales sa ON sas.idServiciosAdicionales=sa.idServiciosAdicionales
WHERE sas.idServicio= $idServicio
");
    $result=mysqli_num_rows($query);
    $svAdicionalesIncluidos=array();
    if ($result > 0) {
   	  while ($data = mysqli_fetch_array($query)) {
   	  	if($data["free"]=="true"){
				
			array_push(
   	  
$svAdicionalesIncluidos, $data['nombre']);
}
		}   
    }
	return $svAdicionalesIncluidos;
	mysqli_close($conection);
}

function svAdicionalesNoIncluidos($idServicio, $money){
$idCatSrv=idCategoriaServicio($idServicio);
	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,
	"SELECT * FROM servicios_adicionales_servicio sas 
INNER JOIN servicios_adicionales sa ON sas.idServiciosAdicionales=sa.idServiciosAdicionales
WHERE sas.idServicio= $idServicio
");
$i=0;
    $result=mysqli_num_rows($query);
    $svAdicionalesNoIncluidos=array();
    if ($result > 0) {
   	  while ($data = mysqli_fetch_array($query)) {
   	  	if($data["free"]=="false"){
   	  		    $svAdicionalesNoIncluidos[$i]=array();
 $idSimboloMonetario=$data['idSimboloMonetario'];
    $adicional=$data['adicional'];
    $precio=0;

$precio=ConvierteMoneda( $idSimboloMonetario, $money,$adicional);
			array_push(
   	  
$svAdicionalesNoIncluidos[$i], 
$data['nombre'],//0
$precio,//1
$data['idServiciosAdicionales']//2



);
			$i++;
}
		}   
    }
	return $svAdicionalesNoIncluidos;
	mysqli_close($conection);
}
//FIN         LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LOS SV ADICIONALES QUE ESTAN INCLUIDOS DENTRO DEL SERVICIO******************


//svAdicionalesNoIncluidos LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LOS SV ADICIONALES NO INCLUIDOS*****************************

function svAdicionalesNoIncluidosOLD($idServicio, $money){

include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"SELECT * FROM servicios_adicionales_servicio sas
INNER JOIN servicios_adicionales sa ON sas.idServiciosAdicionales=sa.idServiciosAdicionales

      WHERE idServicio = ".$idServicio);

    $result=mysqli_num_rows($query);
    $contador = 0;
    $noIncluidos = Array() ;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
    $idSimboloMonetario=$data['idSimboloMonetario'];
    $adicional=$data['adicional'];
    $precio=0;
    
    if ($data['free']=="false") {
    	$precio=ConvierteMoneda( $idSimboloMonetario, $money,$adicional);

    }
    
        $noIncluidos[$contador]=Array();
array_push($noIncluidos[$contador], $contador);//0
array_push($noIncluidos[$contador], $data['idServiciosAdicionales']);//1
array_push($noIncluidos[$contador], $data['nombre']);//2
array_push($noIncluidos[$contador], $idSimboloMonetario);//3
array_push($noIncluidos[$contador],  $precio);//4
array_push($noIncluidos[$contador], $data['free']);//5
$contador++;
    }
 return $noIncluidos;
  }
mysqli_close($conection);
}

//svAdicionalesNoIncluidos LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LOS SV ADICIONALES NO INCLUIDOS*****************************


function DevuelveHorariosPaquetesSalidasUbicacion($idServicio){

			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM horarios_paquetes_salidas 

      WHERE idServicio = ".$idServicio);
    $result=mysqli_num_rows($query);
     $ubicacion=Array();
$contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

        $ubicacion[$contador]['idHorarioPaqueteSalida']=$data['idHorarioPaqueteSalida'];
      	$ubicacion[$contador]['direccion']= $data['direccion'];

      	$ubicacion[$contador]['latitud']=$data['latitud'];
      	$ubicacion[$contador]['longitud']= $data['longitud'];
      	$contador+=1;
     }
        	return $ubicacion; 
    }
    else{
		    return (0);
    }mysqli_close($conection);
}

function DevuelveHotelesUbicacion($idServicio){

			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM hoteles 

      WHERE idServicio = ".$idServicio);
    $result=mysqli_num_rows($query);
     $ubicacion=Array();
$contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {

        $ubicacion[$contador]['direccionHotel']=$data['direccionHotel'];
      
      	$ubicacion[$contador]['latitud']=$data['latitud'];
      	$ubicacion[$contador]['longitud']= $data['longitud'];
      	$contador+=1;
     }
        	return $ubicacion; 
    }
    else{
		    return (0);
    }mysqli_close($conection);
}

//DevuelveCoordenadasServicio LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LA UBICACION DEL MISMO EN FORMA DE LATITUD Y DE LONGITUD
function DevuelveCoordenadasServicio($idServicio){

			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM servicio_ubicacion 

      WHERE idServicio = ".$idServicio);
    $result=mysqli_num_rows($query);
     $ubicacion=Array();

    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
      	$ubicacion['latitud']=$data['latitud'];
      	$ubicacion['longitud']= $data['longitud'];
     }
        	return $ubicacion; 
    }
    else{
		    return (0);
    }mysqli_close($conection);
}
function DevuelveDireccionServicio($idServicio){

			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM servicio_ubicacion 

      WHERE idServicio = ".$idServicio);
    $result=mysqli_num_rows($query);
     $ubicacion=Array();

    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
      	$ubicacion=$data['direccion'];
      	
     }
        	return $ubicacion; 
    }
    else{
		    return (0);
    }mysqli_close($conection);
}

//FIN     DevuelveCoordenadasServicio LE PASAMOS EL ID DE SERVICIO Y NOS DEVUELVE LA UBICACION DEL MISMO EN FORMA DE LATITUD Y DE LONGITUD
function DevuelveNombreServicio($idReserva){
	
			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"SELECT * FROM `reserva_horarios` rh 
INNER JOIN horarios hs ON rh.horarioId=hs.horarioId
INNER JOIN servicio sv ON hs.servicioId=sv.idServicio
WHERE idReserva= ".$idReserva);
    $result=mysqli_num_rows($query);


    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     
      	$nombre= $data['nombre_servicio'];
     }
        	return $nombre; 
    }
    else{
		    return (0);
    }mysqli_close($conection);
}


function InsertaPago($idReserva, $total, $origenComprobante, $monedaComprobante, $compOrigen){
	
			include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"INSERT INTO comprobante (idReserva, total, origenComprobante, monedaComprobante, compOrigen) VALUES('$idReserva', '$total', '$origenComprobante', '$monedaComprobante', '$compOrigen')");


if ($query) {
		return  mysqli_insert_id($conection);//obtenemos el ultimo id agregado
}

mysqli_close($conection);
}



function InsertaComprobante($idUsuario, $total, $monedaComprobante){
	
			include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"INSERT INTO usuario_comprobantes (idUsuario, moneda, total) VALUES('$idUsuario', '$total', '$monedaComprobante')");


if ($query) {
	return  mysqli_insert_id($conection);//obtenemos el ultimo id agregado
}

mysqli_close($conection);
}



function DevuelveContacto($idReserva){
include($GLOBALS['path'].'/conectar.php');


		$query=mysqli_query($conection,"SELECT * FROM reservas rh WHERE idReserva=".$idReserva);
		$result=mysqli_num_rows($query);
		$contacto=array();
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				
		array_push($contacto,  $data['nombre_apellido']);//0
		array_push($contacto,  $data['nacionalidad']);//1
		array_push($contacto,  $data['documento']);//2
		array_push($contacto,  $data['emailResponsable']);//3
		array_push($contacto,  $data['telefono']);
		array_push($contacto,  $data['celular']);
		array_push($contacto,  $data['facebook']);
		

				}
				return $contacto;
		}
		

mysqli_close($conection);
 
}


function AltaUsuarioGoogle($email, $nombre, $foto){
	
			include($GLOBALS['path'].'/conectar.php');

$query=mysqli_query($conection,"INSERT INTO usuario ( email, usuario , fotoUsuario) VALUES('$email', '$nombre', '$foto')");


if ($query) {
	return  mysqli_insert_id($conection);//obtenemos el ultimo id agregado
}
else
	{return "error";}
mysqli_close($conection);
}




function DevuelveUsuario($idusuario){
include($GLOBALS['path'].'/conectar.php');


		$query=mysqli_query($conection,"SELECT * FROM usuario us WHERE idUsuario=".$idusuario);
		$result=mysqli_num_rows($query);
		$contacto=array();
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				
		array_push($contacto,  $data['rol']);//0
		array_push($contacto,  $data['usuario']);//1
		array_push($contacto,  $data['email']);//2
		
		

				}
				return $contacto;
		}
		

mysqli_close($conection);
 
}
function DesactivarCupon($codigoamigable){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"UPDATE cupones_descuento SET cupon_usado='true' WHERE CodigoAmigable='".$codigoamigable."'");
if ($query) {
	return true;
}mysqli_close($conection);mysqli_close($conection);
}



function CuponValido($codigoamigable2){
include($GLOBALS['path'].'/conectar.php');

$consulta="SELECT * FROM `cupones_descuento` cd 

 WHERE cd.CodigoAmigable='".$codigoamigable2."'";

		$query=mysqli_query($conection,$consulta);
		$result=mysqli_num_rows($query);
		$cupones=array();
		$contador=0;
		if ($result ==1) {
					while ($data = mysqli_fetch_array($query)) {
				
		$cupones[0]=1;

	$cupones[1]=DevuelveUsuario($data["idUsuario"])[1];
		$cupones[2]=$data["descuentoPorcentual"];
		$cupones[3]=$data["cupon_usado"];

			echo (json_encode($cupones));

				}
	
				}
				else{
					$cupones[0]=0;
			echo (json_encode($cupones));
				}


mysqli_close($conection);

}

function CuponValidoFNC($codigoamigable2){
include($GLOBALS['path'].'/conectar.php');

$consulta="SELECT * FROM `cupones_descuento` cd 

 WHERE cd.CodigoAmigable='".$codigoamigable2."'";

		$query=mysqli_query($conection,$consulta);
		$result=mysqli_num_rows($query);
		$cupones=array();
		$contador=0;
		if ($result ==1) {
					while ($data = mysqli_fetch_array($query)) {
				
		$cupones[0]=1;

	$cupones[1]=DevuelveUsuario($data["idUsuario"])[1];
		$cupones[2]=$data["descuentoPorcentual"];
		$cupones[3]=$data["cupon_usado"];

			return $cupones;

				}
	
				}
				else{
					$cupones[0]=0;
			return $cupones;
				}


mysqli_close($conection);

}



function InsertaIdReservaHorariosEnCupon($idReservaHorarios,$cupon){

	include($GLOBALS['path'].'/conectar.php');
$query_update=mysqli_query($conection,"UPDATE cupones_descuento SET idReservaHorarios='$idReservaHorarios' WHERE CodigoAmigable='".$cupon."'");
if ($query_update) {
	return true;
}
mysqli_close($conection);
}




function IdUsuarioCuponDescuento($cupon){

	if(strlen($cupon)<2){
		return "Reservate";
	}
	include($GLOBALS['path'].'/conectar.php');
	$query2=mysqli_query($conection,"SELECT * FROM cupones_descuento cd INNER JOIN usuario us ON cd.idUsuario=us.idUsuario  WHERE CodigoAmigable='".$cupon."'");
				$result2=mysqli_num_rows($query2);
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nombre= $data2['usuario'];
		
		
			
		}
			return $nombre;
	}
	else{
		return "Reservate";
	}mysqli_close($conection);
}


function IdUsuarioCuponDescuento2($cupon){

	if(strlen($cupon)<2){
		return "Reservate";
	}
	include($GLOBALS['path'].'/conectar.php');
	$query2=mysqli_query($conection,"SELECT * FROM cupones_descuento cd INNER JOIN usuario us ON cd.idUsuario=us.idUsuario  WHERE CodigoAmigable='".$cupon."'");
				$result2=mysqli_num_rows($query2);
	if ($result2 == 1) {
			while ($data2 = mysqli_fetch_array($query2)) {
			$nombre= $data2['idUsuario'];
		
		
			
		}
			return $nombre;
	}
	else{
		return "Reservate";
	}mysqli_close($conection);
}

function DevuelveCuponesDescuento(){
include($GLOBALS['path'].'/conectar.php');


		$query=mysqli_query($conection,"SELECT * FROM cupones_descuento cd
			INNER JOIN usuario us ON cd.idUsuario=us.idUsuario");
		$result=mysqli_num_rows($query);
		$cupones=array();
		$contador=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				$cupones[$contador]=array();
		array_push($cupones[$contador],  $data['idCuponDescuento']);
		array_push($cupones[$contador],  $data['usuario']);
		array_push($cupones[$contador],  $data['descuentoPorcentual']);
		if ($data['cupon_usado']==="true") {
			array_push($cupones[$contador], $data['idReservaHorarios']);



		}
		else{
			array_push($cupones[$contador],  "No");
		}
		array_push($cupones[$contador], $data['CodigoAmigable']);
	$contador++;
		

				}
				
		}
		

return $cupones;
 mysqli_close($conection);
}


function Cuponera($codigoamigable){
include($GLOBALS['path'].'/conectar.php');


		$query=mysqli_query($conection,"SELECT * FROM cupones_descuento cd
				WHERE CodigoAmigable=$codigoamigable");
		$result=mysqli_num_rows($query);
		$cupon=array();
		$contador=0;
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				array_push($cupon,  $data['descuentoPorcentual']);
	
	$contador++;
		

				}
				
		}
		

return $cupon;
 mysqli_close($conection);
}


function DevuelveDescuentoPorcentualCupon($codigoamigable){
include($GLOBALS['path'].'/conectar.php');


		$query=mysqli_query($conection,"SELECT * FROM cupones_descuento cd
				WHERE CodigoAmigable='$codigoamigable'");
		$result=mysqli_num_rows($query);
		

		if ($result == 1) {
			while ($data = mysqli_fetch_array($query)) {
		

		return ($data['descuentoPorcentual'])/100;

				}
				
		}
		mysqli_close($conection);	

}


function enviaMail($receptor, $asunto, $cuerpo){
require($GLOBALS['path'].'/email/PHPMailerAutoload.php');
$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.ipower.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'metelebrasil@metelebrasil.com';                 // SMTP username
$mail->Password = 'Cambiar$#2019';                           // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->Helo = "www.metelebrasil.com"; //Muy importante para que llegue a hotmail y otros
$mail->From = 'metelebrasil@metelebrasil.com';
$mail->FromName = 'Reservas METELEBRASIL.COM';
$mail->addAddress($receptor);     // Add a recipient
$mail->addAddress('mails@metelebrasil.com');     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $asunto;
$mail->Body    = $cuerpo." Mensaje generado automaticamente por Reservate software®, si ud no desea recibir estos emails, haga click <a href='http://www.metelebrasil.com/unSuscribe.php?email=".$receptor."'> Aqui </a>";
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
if(!$mail->send()) {
    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;
} else {
    return 'Mensaje enviado correctamente';
}
}
/*****************Nueva enviamail*****************/
function enviaMail2($receptor, $asunto, $cuerpo){
require($GLOBALS['path'].'/email/PHPMailerAutoload.php');
$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               // Enable verbose debug output
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.ipower.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'metelebrasil@metelebrasil.com';                 // SMTP username
$mail->Password = 'Nueva$123';                           // SMTP password
$mail->SMTPSecure = 'ssl';                         // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to
$mail->Helo = "www.metelebrasil.com"; //Muy importante para que llegue a hotmail y otros
$mail->From = 'metelebrasil@metelebrasil.com';
$mail->FromName = 'Reservas METELEBRASIL.COM';
$mail->addAddress($receptor);     // Add a recipient
$mail->addAddress('mails@metelebrasil.com');     // Add a recipient
$mail->isHTML(true);                                  // Set email format to HTML
$mail->Subject = $asunto;

$mail->Body    = $cuerpo.'"Mensaje generado automaticamente por Reservate software®, si ud no desea recibir mas estos emails, haga click <a href="http://www.metelebrasil.com/unSuscribe.php?email='.$receptor.'"> Aqui </a>';
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
if(!$mail->send()) {
    return 'El mensaje no se pudo enviar.'.'Envie este error al programador: ' . $mail->ErrorInfo;
} else {
    return 'Mensaje enviado correctamente';
}
}





/*****************Nueva enviamail*****************/












//******************Genera codigo amigable de cupon*******************************
function GeneradorAleatorio($cantidadLetras,$cantidadNumeros){

$string="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$numero_random= rand(100,10000000);

$letras_random= substr(str_shuffle($string),0,($cantidadLetras));

$resultado= substr($letras_random, 0,3).substr($numero_random, 0,($cantidadNumeros));

return $resultado;

}

function GeneraCodigoAmigable(){
	include($GLOBALS['path'].'/conectar.php');
$result=1;
$codigo=GeneradorAleatorio(3,3);
while ( $result > 0) {
	$query=mysqli_query($conection,"SELECT * FROM `cupones_descuento` cd 
	WHERE CodigoAmigable= '".$codigo."'");
    $result=mysqli_num_rows($query);
    if ($result>0) {
    	
    	$codigo=GeneradorAleatorio(3,3);
    }
	else{
	return $codigo;
		}

	}
  mysqli_close($conection);
}

//******************FIN Genera codigo amigable de cupon*******************************


function GeneraCupones($cantidad, $usuario, $descuentoPorcentual){
include($GLOBALS['path'].'/conectar.php');

$cupones["cupones"]=Array();

for ($i=0; $i < $cantidad; $i++) { 
	
$codigoAmigable= GeneraCodigoAmigable();

$query=mysqli_query($conection,"INSERT INTO cupones_descuento (idusuario, CodigoAmigable, descuentoPorcentual ) VALUES('$usuario','$codigoAmigable','$descuentoPorcentual')");

if ($query) {
	
	   $id=mysqli_insert_id($conection);//obtenemos el ultimo id agregado
       
       array_push($cupones["cupones"], $id);
}

	}
	echo(json_encode($cupones));
	mysqli_close($conection);
}


function DevuelveHorarios($fecha, $id, $idMonedaDestino, $impuestosPais){
$precioFinal;
include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM horarios hs
      INNER JOIN servicio sv ON hs.servicioId=sv.idServicio
  WHERE (  hs.fechaIn='$fecha' AND hs.servicioId='$id')");
    $result=mysqli_num_rows($query);
    $id; //DECLARO EL ID DE SERVICIO ACA PARA PASARLO A JS y usarlo en imp y desc de functions
       $datos=Array();
       $contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     $idServicio=$id;
      $pAdulto=$data['pAdulto'];  //USO DE IMPUESTOS
       $pMenor12=$data['pMenor12']; //USO DE IMPUESTOS
      $pMenor5=$data['pMenor5']; //USO DE IMPUESTOS
      $pMenor3=$data['pMenor3']; //USO DE IMPUESTOS     
      $horaInicio=$data['horaInicio']; //USO DE IMPUESTOS   
      $fechain= date_format(new DateTime($data['fechaIn']), 'd-m-Y');//1

      $lugaresReservados=LugaresOcupados($data['horarioId']);
   $monedaId=$data['idMoneda'];
      $lugaresLibres=LugaresLibres($data['horarioId'], $lugaresReservados);
      $datos[$contador]=Array();
      $horarioId=$data['horarioId'];

$pAdulto=ConvierteMoneda($monedaId, $idMonedaDestino, $pAdulto);

/***********************IMPUESTOS Y COMISIONES*************/

$impuestosServicio = DevuelveImpuestosServicio($idServicio);
$DevuelveComisionReservateServicio=DevuelveComisionReservateServicio($idServicio);
$comisionVentaServicio=DevuelveComisionVentaServicio($idServicio);
$ComisionCompensatoria=DevuelveComisionCompensatoria($idServicio);
$precioFinal=($pAdulto*$impuestosServicio)+$pAdulto;
$precioFinal=($pAdulto*$comisionVentaServicio)+$precioFinal;
$precioFinal=($pAdulto*$DevuelveComisionReservateServicio)+$precioFinal;
$precioFinal=($pAdulto*$ComisionCompensatoria)+$precioFinal;
$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;

$pAdulto=round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
	

/***********************FIN IMPUESTOS Y COMISIONES*************/
$pMenor12=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor12);
/***********************IMPUESTOS Y COMISIONES*************/
$precioFinal=($pMenor12*$impuestosServicio)+$pMenor12;
$precioFinal=($pMenor12*$comisionVentaServicio)+$precioFinal;
$precioFinal=($pMenor12*$DevuelveComisionReservateServicio)+$precioFinal;
$precioFinal=($pMenor12*$ComisionCompensatoria)+$precioFinal;
$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;

$pMenor12=round($precioFinal, 2, PHP_ROUND_HALF_EVEN);
/***********************FIN IMPUESTOS Y COMISIONES*************/

$pMenor5=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor5);
 /***********************IMPUESTOS Y COMISIONES*************/
$precioFinal=($pMenor5*$impuestosServicio)+$pMenor5;
$precioFinal=($pMenor5*$comisionVentaServicio)+$precioFinal;
$precioFinal=($pMenor5*$DevuelveComisionReservateServicio)+$precioFinal;
$precioFinal=($pMenor5*$ComisionCompensatoria)+$precioFinal;
$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;

$pMenor5=round($precioFinal, 2, PHP_ROUND_HALF_EVEN);

/***********************FIN IMPUESTOS Y COMISIONES*************/
$pMenor3=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor3);
     /***********************IMPUESTOS Y COMISIONES*************/
$precioFinal=($pMenor3*$impuestosServicio)+$pMenor3;
$precioFinal=($pMenor3*$comisionVentaServicio)+$precioFinal;
$precioFinal=($pMenor3*$DevuelveComisionReservateServicio)+$precioFinal;
$precioFinal=($pMenor3*$ComisionCompensatoria)+$precioFinal;
$precioFinal=($precioFinal*$impuestosPais)+$precioFinal;

$pMenor3=round($precioFinal, 2, PHP_ROUND_HALF_EVEN);

/***********************FIN IMPUESTOS Y COMISIONES*************/
      array_push($datos[$contador], 
      	$horaInicio , //0
      	$lugaresLibres,  //1
      	$fechain, //2
      	$pAdulto, //3
      	$pMenor12, //4
      	$pMenor5, //5
      	$pMenor3,//6
      	$monedaId, //7
      	$horarioId, //8
      	$lugaresReservados //9
      );

        $contador++;
        }
    }
    $datos=json_encode($datos);
    echo $datos;
mysqli_close($conection);
}



function DevuelveHorariosCrudo($idServicio){
$precioFinal;
include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM horarios 
        WHERE servicioId = '$idServicio'");
    $result=mysqli_num_rows($query);

       $datos=array();
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
array_push($datos, $data);
}}
return $datos;


mysqli_close($conection);
}


function DevuelveHorariosPaquete($fecha, $id, $idMonedaDestino){
include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM horarios_paquetes_salidas hs
     
     WHERE (  hs.fechaIn='$fecha' AND hs.servicioId='$id')");

    $result=mysqli_num_rows($query);
    $id; //DECLARO EL ID DE SERVICIO ACA PARA PASARLO A JS y usarlo en imp y desc de functions
       $datos=Array();
       $contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     
      $idHorarioPaquete=$data['idHorarioPaqueteSalida'];  //USO DE IMPUESTOS
   

$datos[$contador]=array();
$datos[$contador][0]= $idHorarioPaquete;//0
$datos[$contador][1]= 270;//3 


        $contador++;

        }
    }
    $datos=json_encode($datos);
    echo $datos;
mysqli_close($conection);
}





function tipoDeCuarto( $tipoDeCuarto){
include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM tipo_cuarto      
     WHERE (  idTipoCuarto='$tipoDeCuarto')");

    $result=mysqli_num_rows($query);

       $datos=Array();
  
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     

      $nombreTipoCuarto=$data['nombreTipoCuarto'];  //
 $maxPersonas=$data['maxPersonas'];  //


      array_push($datos, $nombreTipoCuarto);//0
      array_push($datos, $maxPersonas);//1
    



     
        }
    }
    
  return $datos;
mysqli_close($conection);
}


function DevuelveHorarioPaquete($idHorarioPaquete){
	include($GLOBALS['path'].'/conectar.php');
 $query=mysqli_query($conection,"SELECT * FROM horarios_paquetes hs
	 INNER JOIN servicio sv ON hs.servicioId=sv.idServicio
      WHERE hs.idHorarioPaquete='$idHorarioPaquete'");
   $result=mysqli_num_rows($query);
   $datos=Array();
       if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
      	array_push($datos, 
      		$data['fechaIn'],//0
date_format(new DateTime($data2['fechaIn']), 'd-m-Y')//1
      );
 	

      }
  }
return $datos;
}

function DevuelveHorarioPaqueteSalida($idHorarioPaqueteSalida){
	include($GLOBALS['path'].'/conectar.php');
 $query=mysqli_query($conection,"SELECT * FROM horarios_paquetes_salidas
	      WHERE idHorarioPaqueteSalida='$idHorarioPaqueteSalida'");
   $result=mysqli_num_rows($query);
   $datos=Array();
       if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {


$fechaRegreso=date_format(new DateTime($data["fechaRegreso"]), 'd-m-Y');
      	array_push($datos, 
      		$data['horaSalida'],//0

$data["direccion"], //2
$fechaRegreso,//3
$data["horaRegreso"]//4
      );
 	

      }
  }
return $datos;
}


function DevuelveHotel($idHotel){
	include($GLOBALS['path'].'/conectar.php');
 $query=mysqli_query($conection,"SELECT * FROM hoteles
	      WHERE idHotel='$idHotel'");
   $result=mysqli_num_rows($query);
   $datos=Array();
       if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {


      	array_push($datos, 
      		$data['nombre'],//0

$data["direccionHotel"] //2

      );
 	

      }
  }
return $datos;
}



function DevuelveCuartoHotel($idHotelCuarto, $monedaDestino){
	include($GLOBALS['path'].'/conectar.php');
 $query=mysqli_query($conection,"SELECT * FROM hoteles_cuartos hc
 	INNER JOIN hoteles ht on hc.idHotel=ht.idHotel
 	INNER JOIN servicio sv on ht.idServicio=sv.idServicio
	      WHERE idHotelCuarto='$idHotelCuarto'");
   $result=mysqli_num_rows($query);
   $datos=Array();
       if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
$idMoneda=$data["idMoneda"];
$precioAdulto=$data["precioAdulto"];
$precioCamaAdicional=$data["precioCamaAdicional"];
$precioCamaAdicionalMenor=$data["precioCamaAdicionalMenor"];
      	array_push($datos, 
      		$data['idTipoCuarto'],//0

 ConvierteMoneda($idMoneda,$monedaDestino, $precioAdulto), //1
$data["camaAdicional"],//2
ConvierteMoneda($idMoneda,$monedaDestino, $precioCamaAdicional),//3
ConvierteMoneda($idMoneda,$monedaDestino, $precioCamaAdicionalMenor),//4
$data["cantCamasAdicionales"], //5
$idMoneda,//6
DevuelveCuarto($data['idTipoCuarto'])[0][0]

     );
 	

      }
  }
return $datos;
}



function DevuelveFechaHorario($id){
	$idMonedaDestino=188;

include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM horarios hs
	 	INNER JOIN servicio sv ON hs.servicioId=sv.idServicio
      WHERE horarioId='$id'");
    $result=mysqli_num_rows($query);
    $id; //DECLARO EL ID DE SERVICIO ACA PARA PASARLO A JS y usarlo en imp y desc de functions
       $datos=Array();
       $contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     
      $pAdulto=$data['pAdulto'];  //USO DE IMPUESTOS
       $pMenor12=$data['pMenor12']; //USO DE IMPUESTOS
      $pMenor5=$data['pMenor5']; //USO DE IMPUESTOS
      $pMenor3=$data['pMenor3']; //USO DE IMPUESTOS     
      $horaInicio=$data['horaInicio']; //USO DE IMPUESTOS   
      $fechain=$data['fechaIn']; //USO DE IMPUESTOS
      $lugaresReservados=LugaresOcupados($data['horarioId']);
  		 $monedaId=$data['idMoneda'];
      $lugaresLibres=LugaresLibres($data['horarioId'], $lugaresReservados);
      $datos[$contador]=Array();
      $horarioId=$data['horarioId'];

     $pAdulto=ConvierteMoneda($monedaId,$idMonedaDestino, $pAdulto);
     $pMenor12=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor12);
     $pMenor5=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor5);
     $pMenor3=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor3);


      array_push($datos[$contador], $horaInicio ,$lugaresLibres,  $fechain, $pAdulto, $pMenor12, $pMenor5, $pMenor3,$monedaId,$horarioId);


        $contador++;

        }
    }
return $datos;
mysqli_close($conection);
}
//funcion que le pasamos datos en datos-p.php se le pone -5 para tener el valor original de la reserva, en su moneda nativa
function DevuelveTotalReservaTMP($idHorario,$idMonedaDestino, $cantAdultos, $cant12, $cant5, $cant3){


include($GLOBALS['path'].'/conectar.php');
	 $query=mysqli_query($conection,"SELECT * FROM horarios hs
	 	INNER JOIN servicio sv ON hs.servicioId=sv.idServicio
      WHERE horarioId='$idHorario'");
    $result=mysqli_num_rows($query);
    $id; //DECLARO EL ID DE SERVICIO ACA PARA PASARLO A JS y usarlo en imp y desc de functions
       $datos=Array();
       $contador=0;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
     
      $pAdulto=$data['pAdulto'];  //USO DE IMPUESTOS
   
       $pMenor12=$data['pMenor12']; //USO DE IMPUESTOS
      $pMenor5=$data['pMenor5']; //USO DE IMPUESTOS
      $pMenor3=$data['pMenor3']; //USO DE IMPUESTOS     
      $horaInicio=$data['horaInicio']; //USO DE IMPUESTOS   
      $fechain=$data['fechaIn']; //USO DE IMPUESTOS
      $lugaresReservados=LugaresOcupados($data['horarioId']);
      $idServicio=$data['idServicio'];
  	$monedaId=$data['idMoneda'];
  		if($idMonedaDestino==(-5)){
  			$idMonedaDestino=$monedaId;
  		}
      $lugaresLibres=LugaresLibres($data['horarioId'], $lugaresReservados);
      $datos[$contador]=Array();
      $horarioId=$data['horarioId'];

     $pAdulto2=ConvierteMoneda($monedaId,$idMonedaDestino, $pAdulto)*$cantAdultos;
     $pMenor122=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor12)*$cant12;
     $pMenor52=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor5)*$cant5;
     $pMenor32=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor3)*$cant3;


   $precioAdultoIndividual=ConvierteMoneda($monedaId,$idMonedaDestino, $pAdulto);
     $precio12Individual=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor12);
     $precio5Individual=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor5);
     $precio3Individual=ConvierteMoneda($monedaId,$idMonedaDestino, $pMenor3);
$total=$pAdulto2+$pMenor122+$pMenor52+$pMenor32;
      array_push($datos[$contador],
       $horaInicio ,//0
       $lugaresLibres, //1 
       $fechain, //2
       $pAdulto2, //3
       $pMenor122, //4
       $pMenor52, //5
       $pMenor32,//6
       $monedaId,//7
       $horarioId, //8
       $total,//9
       $idServicio,//10
       $precioAdultoIndividual,//11
       $precio12Individual,//12
       $precio5Individual,//13
       $precio3Individual//14
   );


        $contador++;

        }
    }
return $datos;
mysqli_close($conection);
}
//FIN   funcion que le pasamos datos en datos-p.php

function DevuelveAdicional($idServiciosAdicionales,$servicio, $money){
include($GLOBALS['path'].'/conectar.php');
$cons="SELECT * FROM servicios_adicionales_servicio sas
INNER JOIN servicios_adicionales sa ON sas.idServiciosAdicionales=sa.idServiciosAdicionales
      WHERE sas.idServiciosAdicionales = '$idServiciosAdicionales' AND
	sas.idServicio='$servicio'
      ";
   //   echo "string-".$cons;
$query=mysqli_query($conection,$cons);

    $result=mysqli_num_rows($query);
    $contador = 0;
    $noIncluidos = Array() ;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
    
     
        array_push($noIncluidos, $data[$contador]);//0
        array_push($noIncluidos, $data['idServiciosAdicionales']);//1
        array_push($noIncluidos, $data['nombre']);//2
        array_push($noIncluidos, $data['idSimboloMonetario']);//3
        if ($money==-5) {
        	$money=$data['idSimboloMonetario'];
        }
        array_push($noIncluidos, ConvierteMoneda( $data['idSimboloMonetario'], $money, $data['adicional']));//4
        array_push($noIncluidos, $data['free']);//5
$contador++;
    }
 return $noIncluidos;
  }
mysqli_close($conection);

}

function DevuelveAdicionalesReserva($idReserva, $_monedaSel){
include($GLOBALS['path'].'/conectar.php');
$cons="SELECT * FROM reserva_adicionales ra
INNER JOIN reserva_horarios rh ON ra.idReserva=rh.idReserva
INNER JOIN servicios_adicionales sa on ra.idServiciosAdicionales=sa.idServiciosAdicionales
      WHERE ra.idReserva = '$idReserva'

      ";

$query=mysqli_query($conection,$cons);

    $result=mysqli_num_rows($query);
    $contador = 0;
    $noIncluidos = Array() ;
    if ($result > 0) {
      while ($data = mysqli_fetch_array($query)) {
$noIncluidos[$contador] = Array() ;

      	$nombreServicio=$data["nombre"];
      	$totalpers=$data['cantidad_reserva_adicionales'];
      	$precioUni=$data['precioReservaAdicionales'];
      	$precioTot=$data['precioReservaAdicionales']*$data['cantidad_reserva_adicionales'];
      	$monedaNativa=$data["moneda"];
      	$monedaSel=$data["monedaSel"];
      	$totalConvertido=ConvierteMoneda( $monedaNativa,$monedaSel, $precioTot);
 		$unitarioConvertido=ConvierteMoneda( $monedaNativa,$monedaSel, $precioUni);
         array_push($noIncluidos[$contador], 
    	
		$totalpers,
		$nombreServicio,
		$totalConvertido,
		$unitarioConvertido





    );
     
$contador++;
    }
 return $noIncluidos;
  }
mysqli_close($conection);

}


function InsertaReserva($idHorario, $cantAdultos, $cant12, $cant5, $cant3,$codCupon,$adicionales, $contacto, $monedaSel){
	
			include($GLOBALS['path'].'/conectar.php');
			

$total=	DevuelveTotalReservaTMP($idHorario,-5, $cantAdultos, $cant12, $cant5, $cant3);

$monedaOriginal=$total[0][7];
$totalReserva=$total[0][9];
$idServicio=$total[0][10];
$IdUsuarioCuponDescuento=IdUsuarioCuponDescuento($codCupon);


$query=mysqli_query($conection,"INSERT INTO reserva_horarios 
	(total_reserva, cantAdultos, cant12 ,
	cant5, cant3, servicioId,
	 horarioId, moneda, cuponDescuento, monedaSel, idUsuario ) 
	VALUES
	($totalReserva,$cantAdultos, $cant12, 
	$cant5, $cant3,$idServicio,
	$idHorario, $monedaOriginal, '$codCupon','$monedaSel','$IdUsuarioCuponDescuento')");

if ($query) {
	  
 $idReserva=mysqli_insert_id($conection);//obtenemos el ultimo id agregado
	   //echo "reservaoknro:".$idReserva;
 echo $idReserva;
	   for ($i=0; $i < count($adicionales) ; $i++) { 
$DevuelveAdicional=(DevuelveAdicional($adicionales[$i][0],$idServicio,-5));
$subtotal=(floatval($DevuelveAdicional[4])*floatval($adicionales[$i][1]));


  $query2=mysqli_query($conection,"INSERT INTO reserva_adicionales 
	( idReserva, idServiciosAdicionales, cantidad_reserva_adicionales, precioReservaAdicionales, total ) 
	VALUES
	('$idReserva', ".$adicionales[$i][0].",".$adicionales[$i][1].",'".$DevuelveAdicional[4]."','".$subtotal."')");
	  
	  if ($query2) {
	  
 $idAdicionall=mysqli_insert_id($conection);//obtenemos el ultimo id agregado
	 //  echo "reservaAdicionales:".$idAdicionall;

					}  
 }


		  $query3=mysqli_query($conection,"INSERT INTO reserva_contacto 
	( idReserva, nombre_apellido, email, telefono ) 
	VALUES
	('$idReserva','".$contacto[0]." ".$contacto[1]."','".$contacto[4]."','".$contacto[2]." ".$contacto[3]."')");
	  
	  if ($query3) {
	  
 $idContacto=mysqli_insert_id($conection);//obtenemos el ultimo id agregado

 enviaMail($contacto[4], "Reserva con exito en metelebrasil.com", "Su reserva en metelebrasil.com se realizo con exito, su numero de reserva es: ". $idReserva);
	   //echo "contacto:".$idReserva;


					} 
				



	 											 
	

 

			}

mysqli_close($conection);
}


function OpinionesCategoria($idCategoriaServicio){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM opiniones_categoria  WHERE idCatSrv=".$idCategoriaServicio);
				$result2=mysqli_num_rows($query2);
				$opiniones= Array();
				$estrellas=0;
	if ($result2 > 0) {
		$i=0;
		$estrellasAcu=0;
			while ($data2 = mysqli_fetch_array($query2)) {
			
				$opiniones[$i]=Array();
				$estrellasAcu+=$data2['estrellas'];	
		$fAlta=date_format(new DateTime($data2['fechaAlta']), 'd-m-Y');
				array_push($opiniones[$i], 
					$data2['nombre'], //0
					$data2['opinion'], //1
					$estrellasAcu, //2
					$fAlta, //3
					$data2['pais'], //4
					"null",
					//$data2['selPasajeros'], //5
					$data2['estrellas'], //6
					$data2['idOpinionCategoria'],//7
					$result2
				);
		
			$i++;
				
			
		}
		return $opiniones;
	}mysqli_close($conection);
}


function OpinionesServicio($idServicio){

	include($GLOBALS['path'].'/conectar.php');
				$query2=mysqli_query($conection,"SELECT * FROM opiniones_servicio  WHERE idServicio=".$idServicio);
				$result2=mysqli_num_rows($query2);
				$opiniones= Array();
				$estrellasAcu=0;
	if ($result2 > 0) {
		$i=0;
			while ($data2 = mysqli_fetch_array($query2)) {
			
				$opiniones[$i]=Array();
				$estrellasAcu+=$data2['estrellas'];	
		$fAlta=date_format(new DateTime($data2['fechaAlta']), 'd-m-Y');
				array_push($opiniones[$i], 
					$data2['nombre'], //0
					$data2['opinion'], //1
					$estrellasAcu, //2
					$fAlta, //3
					$data2['pais'], //4
					$data2['selPasajeros'], //5
					$data2['estrellas'], //6
					$data2['idOpinionServicio'] //77
				);
	
			$i++;
				
			
		}
		return $opiniones;
	}mysqli_close($conection);
}

function InsertaOpinionServicio($opinion){
	
			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO opiniones_servicio
 (nombre, opinion, estrellas, pais, selPasajeros, idServicio) 
 VALUES
 ('$opinion[0]', '$opinion[1]', '$opinion[2]','$opinion[3]','$opinion[4]','$opinion[5]')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}


function InsertaOpinionCategoria($opinion){
	
			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO opiniones_categoria
 (nombre, opinion, estrellas, pais, idCatSrv) 
 VALUES
 ('$opinion[0]', '$opinion[1]', '$opinion[2]','$opinion[3]','$opinion[5]')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}
function EliminaHorarioSalida($idHorario){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM horarios where horarioId = '$idHorario'");

	return $query;
	mysqli_close($conection);
}

function EliminaOpinionServicio($idOpinionServicio){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM opiniones_servicio where idOpinionServicio=$idOpinionServicio");

	return $query;
	mysqli_close($conection);
}
function EliminaOpinionCategoria($idOpinionCategoria){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM opiniones_categoria where idOpinionCategoria=$idOpinionCategoria");

	return $query;
	mysqli_close($conection);
}

function OpinionesDeServicioTexto($selPasajeros){

switch ($selPasajeros) {
	case '1':
	$texto='<i class="fas fa-child fa-4x"></i><p>Viajó solo</p>';
		break;

	case '2':
	$texto='<i class="fas fa-female fa-4x "></i><p>Viajó sola</p>';
		break;

	case '3':
	$texto='<i class="fa fa-restroom fa-4x "></i><p>Viajó en pareja</p>';
		break;

	case '4':
	$texto='<i class="fas fa-users fa-4x"></i><p>Viajó en familia</p>';
		break;

	case '5':
	$texto='<i class="fas fa-user-friends fa-4x"></i><p>Viajó con amigos</p>';
		break;
	
}
return $texto;
mysqli_close($conection);
}




function DevuelveTipoCuarto(){

	include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,"SELECT * FROM tipo_cuarto");
				$result2=mysqli_num_rows($query2);
				$tipo_cuarto= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$tipo_cuarto[$i]= Array();
			
	array_push($tipo_cuarto[$i], 
	
					$data2['nombreTipoCuarto'], //0
					$data2['idTipoCuarto'], //1
					$data2['minPersonas'],  //2
					$data2['maxPersonas'],  //3
					$data2['camaExtra'] 	//4
				);
	
			$i++;
				
			
		}
		return $tipo_cuarto;
	}
	mysqli_close($conection);
}


function DevuelveCuarto($idTipoCuarto){

	include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,"SELECT * FROM tipo_cuarto WHERE idTipoCuarto=$idTipoCuarto");
				$result2=mysqli_num_rows($query2);
				$tipo_cuarto= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$tipo_cuarto[$i]= Array();
			
	array_push($tipo_cuarto[$i], 
	
					$data2['nombreTipoCuarto'], //0
					$data2['idTipoCuarto'], //1
					$data2['minPersonas'],  //2
					$data2['maxPersonas'],  //3
					$data2['camaExtra'] 	//4
				);
	
			$i++;
				
			
		}
		return $tipo_cuarto;
	}
	mysqli_close($conection);
}




function Accesibilidad($idServicio){

$consulta="SELECT * FROM servicio WHERE idServicio=$idServicio";
	



	include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,$consulta);
				$result2=mysqli_num_rows($query2);
				
	if ($result2 > 0) {
		
	while ($data2 = mysqli_fetch_array($query2)) {
		
if($data2["accesibilidad"]==0){

	return false;
		
} else{
	return true;
}
				
			
		}
		
	}
	mysqli_close($conection);
}
//********************TEXTO    CANCELACIONES**********************************

function Cancelaciones($idServicio){
if ($idServicio==(-5)) {
	$consulta="SELECT * FROM cancelaciones WHERE 1";
}
else{
	$consulta="SELECT * FROM servicio sv
INNER JOIN 	cancelaciones can ON sv.cancelaciones=can.idCancelacion
	WHERE sv.idServicio=$idServicio";

}


	include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,$consulta);
				$result2=mysqli_num_rows($query2);
				$cancelaciones= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$cancelaciones[$i]= Array();
			
	array_push($cancelaciones[$i], 
	
					$data2['idCancelacion'], //0
					$data2['texto'] //1
				
				);
	
			$i++;
				
			
		}
		return $cancelaciones;
	}
	mysqli_close($conection);
}


function InsertaCancelaciones($texto){
	
			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO cancelaciones
 ( texto) 
 VALUES
 ('$texto')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}

function EliminaCancelaciones($idCancelacion){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM cancelaciones where idCancelacion=$idCancelacion");

	return $query;
	mysqli_close($conection);
}

//********************FIN CANCELACIONES**********************************


//********************TEXTO    MINIATURAS**********************************

function TextoMiniaturas($idServicio){
if ($idServicio==(-5)) {
	$consulta="SELECT * FROM texto_miniaturas WHERE 1";
}
else{
	$consulta="SELECT * FROM servicio sv
INNER JOIN 	texto_miniaturas tm ON sv.idTextoMiniaturas=tm.idTextoMiniaturas
	WHERE sv.idServicio=$idServicio";

}


	include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,$consulta);
				$result2=mysqli_num_rows($query2);
				$cancelaciones= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$textoMiniaturas[$i]= Array();
			
	array_push($textoMiniaturas[$i], 
	
					$data2['idTextoMiniaturas'], //0
					$data2['texto'] //1
				
				);
	
			$i++;
				
			
		}
		return $textoMiniaturas;
	}
	mysqli_close($conection);
}


function InsertaTextoMiniaturas($texto){
$texto=strtoupper($texto);
			include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO texto_miniaturas
 ( texto) 
 VALUES
 ('$texto')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}

function EliminaTextoMiniaturas($idTextoMiniaturas){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM texto_miniaturas where idTextoMiniaturas=$idTextoMiniaturas");

	return $query;
	mysqli_close($conection);
}

//********************FIN MINIATURAS**********************************




function DevuelvePaises($idPais){
if ($idPais==(-5)) {
$consulta="SELECT * FROM paises ";
}
else{
$consulta="SELECT * FROM paises WHERE id='$idPais'";
}include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,$consulta);
				$result2=mysqli_num_rows($query2);
				$paises= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$paises[$i]= Array();
			
	array_push($paises[$i], 
	
					$data2['id'], //0
					$data2['nombre'], //1
		$data2['iso3166a1'] //2
				);
	
			$i++;
				
			
		}
		return $paises;
	}
	mysqli_close($conection);
}



function DevuelveImpuestosPais($idPais){
if ($idPais==(-5)) {
$consulta="SELECT * FROM impuestos_pais ";
}
else{
$consulta="SELECT * FROM impuestos_pais ip
WHERE ip.idPais='$idPais'";
}include($GLOBALS['path'].'/conectar.php');
$query2=mysqli_query($conection,$consulta);
				$result2=mysqli_num_rows($query2);
				$paises= Array();
			
	if ($result2 > 0) {
		$i=0;
	while ($data2 = mysqli_fetch_array($query2)) {
				$paises[$i]= Array();
			$nomPais=(DevuelvePaises($data2['idPais'])[0][1]);
	array_push($paises[$i], 
	
					$data2['idImpuestoPais'], //0
					$data2['nombreImpuesto'], //1
					$nomPais,//2
					$data2['valor']//3
				);
	
			$i++;
				
			
		}
		return $paises;
	}
	mysqli_close($conection);
}


function InsertaImpuestosPais($idPais, $nombre, $valor){
include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO impuestos_pais
 ( idPais, nombreImpuesto, valor) 
 VALUES
 ('$idPais', '$nombre', '$valor')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}

function EliminaImpuestosPais($idImpuestoPais){

	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"DELETE FROM impuestos_pais where idImpuestoPais='$idImpuestoPais'");

	return $query;
	mysqli_close($conection);
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
 		$geoLocalizacionIp[3]=$data["geoplugin_longitude"];
 		$geoLocalizacionIp[4]=$data["geoplugin_longitude"];
  		$geoLocalizacionIp[5]=$data["geoplugin_city"];
    
    switch ($countryCode) {
      case 'AR':
      $geoLocalizacionIp[0] ="Argentina";
      $geoLocalizacionIp[1] = 270;
      $geoLocalizacionIp[2] = "AR$"; 
      $geoLocalizacionIp[3] = "13"; 
 	  $geoLocalizacionIp[4] =$countryCode; 
 	  $geoLocalizacionIp[5] ="ES"; 
 	   $geoLocalizacionIp[6] ="ESPAÑOL"; 
        break; 

        case 'BR':
      $geoLocalizacionIp[0] ="Brasil";
      $geoLocalizacionIp[1] = 283;
      $geoLocalizacionIp[2] = "R$"; 
      $geoLocalizacionIp[3] = "33";
      $geoLocalizacionIp[4] = $countryCode; 
        $geoLocalizacionIp[5] = "PT";
         $geoLocalizacionIp[6] ="PORTUGUES";
        break;
      
        case 'PY':
      $geoLocalizacionIp[0] ="Paraguay";
      $geoLocalizacionIp[1] = 225;
      $geoLocalizacionIp[2] = "G$"; 
      $geoLocalizacionIp[3] = "172";
      $geoLocalizacionIp[4] = $countryCode; 
        $geoLocalizacionIp[5] ="GU";
         $geoLocalizacionIp[6] ="GUARANI";
        break;

        case 'CL':
      $geoLocalizacionIp[0] ="Chile";
      $geoLocalizacionIp[1] = 271;
      $geoLocalizacionIp[2] = "CL$"; 
      $geoLocalizacionIp[3] = "46";
      $geoLocalizacionIp[4] = $countryCode; 
        $geoLocalizacionIp[5] ="ES";
         $geoLocalizacionIp[6] ="ESPAÑOL";
        break;

      default:
        $geoLocalizacionIp[0] =$data["geoplugin_countryCode"];
      $geoLocalizacionIp[0] ="Desconocido";
      $geoLocalizacionIp[1] = 188;
      $geoLocalizacionIp[2] = "U$S"; 
      $geoLocalizacionIp[3] = "-5";
      $geoLocalizacionIp[4] = $countryCode; 
      $geoLocalizacionIp[5] ="EN";
       $geoLocalizacionIp[6] ="INGLES";
        break;


    } return $geoLocalizacionIp;
}

function impuestosPais($idPais){


	include($GLOBALS['path'].'/conectar.php');

$query2=mysqli_query($conection,"SELECT * FROM impuestos_pais   ip INNER JOIN paises pa ON ip.idPais=pa.id WHERE ip.idPais='$idPais'");
			$i=0;
			$result2=mysqli_num_rows($query2);
	if ($result2==0) {
	$query2=mysqli_query($conection,"SELECT * FROM impuestos_pais ip INNER JOIN paises pa ON ip.idPais=pa.id WHERE ip.idPais=0");
	$result2=mysqli_num_rows($query2);
			}
			$valores=Array();
			$valorImpuesto;

	if ($result2 > 0) {
			while ($data2 = mysqli_fetch_array($query2)) {
					$valores[$i]=Array();
					$valores[$i][0]= $data2['nombreImpuesto'];
					$valores[$i][1]= $data2['valor']/100;
					$valores[$i][2]= $data2['nombre'];
				$i++;
			
			
		}

	
	
	}
	
	return $valores;
	mysqli_close($conection);

}


function devuelveDeudaReserva($idReserva, $money){


	include($GLOBALS['path'].'/conectar.php');
	$totalReserva=0;

		$query=mysqli_query($conection,"SELECT * FROM reservas  res
			WHERE idReserva=".$idReserva);
		$result=mysqli_num_rows($query);
		if ($result > 0) {
			while ($data = mysqli_fetch_array($query)) {
				$totalReserva=0;
				$idReserva=$data['idReserva'];
$monedaSel=$data["monedaSel"];
$fechaAlta=date_format(new DateTime($data['fechaAlta']), 'd-m-Y');
 $comprobantesReserva=DevuelveTotalComprobantesPagosReserva($idReserva, $money);

$datosReservaHorario=DevuelveDatosReservaHorarios($idReserva);

for ($i=0; $i < count($datosReservaHorario); $i++) { 

	$idServicio=$datosReservaHorario[$i][0];
	$horarioId=$datosReservaHorario[$i][1];
	$cantAdultos=$datosReservaHorario[$i][2];
	$cant12=$datosReservaHorario[$i][3];
	$cant5=$datosReservaHorario[$i][4];
	$cant3=$datosReservaHorario[$i][5];
	$moneda=$datosReservaHorario[$i][6];
	$cuponDescuento=$datosReservaHorario[$i][7];
	$fecha=$datosReservaHorario[$i][10];
$hora=$datosReservaHorario[$i][11];
$subTotalReserva=$datosReservaHorario[$i][8];
	$idReservaHorario=$datosReservaHorario[$i][12];
$vendedor=IdUsuarioCuponDescuento($cuponDescuento);


$totalPersonas=$cantAdultos+$cant12+$cant5+$cant3;
 $totalAdicionales=DevuelveTotalAdicionalesReservaServicios($idReservaHorario);

$total=$totalAdicionales+ConvierteMoneda($monedaSel, $money,$subTotalReserva);
$totalReserva+=$total;


		}


	




$datosReserva=DevuelveDatosReservaHorariosPaquetes($idReserva);

for ($i=0; $i < count($datosReserva); $i++) { 

$subTotalReserva=$datosReserva[$i][13];
$idReservaHorarioPaquete=$datosReserva[$i][15];
$totalAdicionales=DevuelveTotalAdicionalesReservaPaquetes($idReservaHorarioPaquete);

$total=$totalAdicionales+ConvierteMoneda($monedaSel, $money,$subTotalReserva);
$totalReserva+=$total;
}






} //fin for horarios
$diferencia=($totalReserva-$comprobantesReserva);
return $diferencia;

	  } //fin if


}



function Visitante($datos){
	
	include($GLOBALS['path'].'/conectar.php');
$query=mysqli_query($conection,"INSERT INTO visitantes
 (ip, lugar, pagina) 
 VALUES
 ('$datos[0]','$datos[1]','$datos[2]')");


if ($query) {
	return 1;
}
else{
	return -5;
}
mysqli_close($conection);

}




 ?>

