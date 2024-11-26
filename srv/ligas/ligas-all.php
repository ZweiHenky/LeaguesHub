<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '/TABLA_LIGAS.php';

ejecutaServicio(function () {
  
  
 $lista = select(pdo: Bd::pdo(),  from: LIGAS,  orderBy: LIG_NOMBRE);
 

 $render = '';
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[LIG_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[LIG_NOMBRE]);
  $premio = htmlentities($modelo[LIG_PREMIO]);
  $logo = htmlentities($modelo[LIG_LOGO]);
  $direccion = htmlentities($modelo[LIG_DIRECCION]);
  $descripcion = htmlentities($modelo[LIG_DESCRIPCION]);

  $render .=
   "<div class='bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition duration-300 $nombre liga'>
                    <div class='p-6'>
                        <div class='flex items-center space-x-4 mb-4'>
                            <img src='$logo' alt='$nombre logo' class='w-20 h-20 object-contain rounded-full bg-gray-100 p-2'>
                            <div>
                                <h2 class='text-xl font-bold text-gray-900'>$nombre</h2>
                                <p class='text-apple-blue font-medium'>$descripcion</p>
                            </div>
                        </div>
                        <div class='space-y-2 text-sm text-gray-600'>
                            <p class='flex items-center'><i class='fas fa-map-marker-alt w-5 text-apple-blue'></i> <span class='ml-2'>$direccion</span></p>
                            <p class='flex items-center'><i class='fas fa-users w-5 text-apple-blue'></i> <span class='ml-2'>20 equipos</span></p>
                            <p class='flex items-center'><i class='fas fa-trophy w-5 text-apple-blue'></i> <span class='ml-2'>Premio: $premio</span></p>
                        </div>
                    </div>
                    <div class='px-6 py-4 bg-gray-50'>
                        <a href='./detalle-liga?id=$id'>
                        <button class='w-full bg-apple-blue hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-full transition duration-300 shadow-md hover:shadow-lg'>
                            Ver detalles
                        </button>
                        </a>
                    </div>
                </div>";
 }

 
 devuelveJson(['leagues-grid' => ['innerHTML' => $render]]);
 
});
