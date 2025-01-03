<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/select.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_LIGAS.php";
require_once __DIR__ . "../../usuarios/TABLA_USUARIOS.php";

ejecutaServicio(function () {

  $correo = recuperaTexto("correo");

  $usuario = selectFirst(pdo: Bd::pdo(), from: USUARIO, where: [USU_EMAIL => $correo]);
  
 $lista = select(pdo: Bd::pdo(),  from: LIGAS,  orderBy: LIG_NOMBRE, where: [USU_ID => $usuario[USU_ID]]);
 

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[LIG_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[LIG_NOMBRE]);
  $premio = htmlentities($modelo[LIG_PREMIO]);
  $render .=
   "<div class='bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300'>
        <div class='flex items-center justify-between mb-4'>
            <h3 class='text-xl font-semibold text-gray-800'>$nombre</h3>
            <span class='px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm'>$$premio</span>
        </div>
        <div class='flex justify-end space-x-2'>
            <a href='agregar-equipo.html?id=$id'>
              <button class='bg-slate-900 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded transition duration-300' onclick=''>
                <i class='fas fa-edit mr-2'></i>Agregar
              </button>
            </a>
            <a href= 'crear-jornada.html?id=$id'>
              <button class='bg-slate-900 hover:bg-slate-600 text-white font-bold py-2 px-4 rounded transition duration-300' onclick=''>
                  <i class='fas fa-edit mr-2'></i>Jornada
              </button>
            </a>
          <a href='modifica.html?id=$id'>
            <button onclick='' class='bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300' onclick=''>
                <i class='fas fa-edit mr-2'></i>Editar
            </button>
          </a>
        </div>
    </div>";
 }

 
 devuelveJson(["leagues-grid" => ["innerHTML" => $render]]);
 
});
