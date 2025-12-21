<?php

function getBandera($idPais){


switch ($idPais) {
    case 270:
      $bandera= 'img/countries/Argentina-icon.png';
    return $bandera;
        break;
    case 283:
    $bandera= 'img/countries/Brazil-icon.png';
    return $bandera;
        break;
    
    default:
     $bandera= 'img/countries/United-States-of-Americ-icon.png';
    return $bandera;
        break;
}

    

    

    }



    

?>