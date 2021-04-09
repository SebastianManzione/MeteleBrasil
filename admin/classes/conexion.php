<?php 
try {
	$host= gethostname();

	$productionMode=false;
	if ($host=="server") {
	$pdo = new PDO('mysql:host=localhost;dbname=reservate', 'admin', 'poli089089');
	
		$pdo -> exec("SET CHARACTER SET utf8");
	}else{
		
			$pdo = new PDO('mysql:host=localhost;dbname=pontopraiacom_metelebrasil', 'pontopraiacom_metelebrasil', 'Reservate$2019');
		
		$pdo -> exec("SET CHARACTER SET utf8");
	}
	
} catch (Exception $e) {
	echo "ERROR: ".$e->getMessage();
} 
?>
