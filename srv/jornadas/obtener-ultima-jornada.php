<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '/TABLA_JORNADAS.php';
require_once __DIR__ . '../../../lib/php/recuperaTexto.php';

ejecutaServicio(function () {
  
 $idLiga = recuperaTexto('id');

  
 $lista = fetch(Bd::pdo()->query(
  "SELECT JOR_NUMERO
FROM JORNADAS
WHERE LIG_ID = '$idLiga'
ORDER BY JOR_NUMERO DESC
LIMIT 1;
"
));



if (isset($lista)) {
    $id = 1;
}else{
    $encodeId = urlencode($lista[JOR_NUMERO]);
    $id = intval(htmlentities($encodeId)) + 1;
}


$render =
    "
    <input
        type='text'
        name='id_liga'
        value='$idLiga'
        readonly
        hidden
        class='w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500'
    >
    <label for='numeroJornada' class='text-sm font-medium text-gray-700'>
        Numero de la jornada
    </label>
    <input
        type='text'
        name='numeroJornada'
        value='$id'
        readonly
        class='w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500'
    >";

 
 devuelveJson(['numero-jornada' => ['innerHTML' => $render]]);
 
});
