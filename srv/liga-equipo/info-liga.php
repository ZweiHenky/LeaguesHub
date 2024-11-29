<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '../../ligas/TABLA_LIGAS.php';
require_once __DIR__ . '../../../lib/php/recuperaTexto.php';

ejecutaServicio(function () {
  
 $id = recuperaTexto('id');

$lista = select(pdo: Bd::pdo(),  from: LIGAS,  orderBy: LIG_NOMBRE, where: [LIG_ID => $id]);

 $render = '';
  $encodeId = urlencode($lista[0][LIG_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($lista[0][LIG_NOMBRE]);
  $premio = htmlentities($lista[0][LIG_PREMIO]);
  $logo = htmlentities($lista[0][LIG_LOGO]);
  $direccion = htmlentities($lista[0][LIG_DIRECCION]);
  $descripcion = htmlentities($lista[0][LIG_DESCRIPCION]);

  $render .=
   "<div class='flex items-center space-x-6 mb-6'>
                <img src='$logo' alt='$nombre logo' class='w-24 h-24 object-contain'>
                <div>
                    <h1 class='text-3xl font-bold'>$nombre</h1>
                    <p class='text-gray-600'>Mexico</p>
                </div>
            </div>
            <div class='grid grid-cols-2 md:grid-cols-4 gap-4 text-center'>
                <div>
                    <p class='text-sm text-gray-600'>Ubicación</p>
                    <p class='font-semibold'>$direccion</p>
                </div>
                <div>
                    <p class='text-sm text-gray-600'>Premio</p>
                    <p class='font-semibold'>$$premio</p>
                </div>
                <div>
                    <p class='text-sm text-gray-600'>Equipos</p>
                    <p class='font-semibold'>4</p>
                </div>
            </div>";

 
 devuelveJson(['leagueDetails' => ['innerHTML' => $render]]);
 
});
