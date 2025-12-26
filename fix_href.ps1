$txt = Get-Content -Raw 'includes\navbar.php'
$txt = $txt -replace 'href id=', 'href="#" id='
$txt = $txt -replace '<i href="carrito.php" class="fa fa-shopping-cart">', '<i class="fa fa-shopping-cart">'
$txt | Set-Content 'includes\navbar.php'
Write-Host "Fixed href attributes"
