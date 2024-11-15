<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '/TABLA_ROLS.php';

ejecutaServicio(function () {

 $lista = select(pdo: Bd::pdo(), from: ROL, orderBy: ROL_ID);

 $render = '';
 foreach ($lista as $modelo) {
  $id = htmlentities($modelo[ROL_ID]);
  $descripcion = htmlentities($modelo[ROL_DES]);
  if ($modelo[ROL_ID] != 'SuperAdmin') {
    $render .=
   "<div class='font-sans'>
  <label class='flex items-start space-x-4 p-4 rounded-lg transition duration-300 ease-in-out hover:bg-gray-100'>
    <div class='relative'>
      <input type='checkbox' name='rolIds[]' value='$id' class='peer sr-only' />
      <div class='w-6 h-6 border-2 border-gray-300 rounded-md bg-white peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all duration-300 ease-in-out'></div>
      <div class='absolute inset-0 hidden peer-checked:flex items-center justify-center transition-all duration-300 ease-in-out'>
        <svg class='w-4 h-4 text-white' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'>
          <path fill-rule='evenodd' d='M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z' clip-rule='evenodd' />
        </svg>
      </div>
    </div>
    <div class='flex flex-col'>
      <span class='text-lg font-semibold text-gray-900'>$id</span>
      <span class='text-sm text-gray-500'>$descripcion</span>
    </div>
  </label>
</div>";
  }
 }

 devuelveJson(['roles' => ['innerHTML' => $render]]);
});
