<?php 

switch ($_SESSION['idioma']) {
  case 'PT':
  setlocale(LC_TIME, "pt_BR.utf-8");
    break;
    case 'EN':
  setlocale(LC_TIME, "en_US.utf8");
    break;
      case 'ES':
 setlocale (LC_ALL, 'spanish');
    break;
  default:
    // code...
    break;
}  


 ?>