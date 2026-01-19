<?php
$pdo=new PDO('mysql:host=localhost;dbname=metelebrasil_experimental;charset=utf8mb4','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$stmt=$pdo->query('DESCRIBE moneda');
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($rows as $r){ echo $r['Field']."\n"; }
