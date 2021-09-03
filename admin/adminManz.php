<?php 
if (isset($_POST['clave']) && $_POST['clave']==base64_decode('cG9saTA4OTA4OQ==')) {
	session_start();
	echo('sos vos!');
	$_SESSION['login']['adminManz']="hola";
	exit();
}



 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title>ad</title>
 </head>
 <body>
 <form method="post">
 	<input type="password" name="clave">
 	<button type="submit">sos vos?</button>
 </form>
 </body>
 </html>