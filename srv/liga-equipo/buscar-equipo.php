<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '../../equipos/TABLA_EQUIPOS.php';
require_once __DIR__ . '../../usuarios/TABLA_USUARIOS.php';
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";

ejecutaServicio(function () {
  
 $nombre = recuperaTexto("nombre");
 $idLiga = recuperaTexto("id");
  
 $lista = fetchAll(Bd::pdo()->query(
  "SELECT EQUIPOS.*, USUARIO.USU_EMAIL
FROM EQUIPOS
INNER JOIN USUARIO ON EQUIPOS.USU_ID= USUARIO.USU_ID
WHERE EQUIPOS.EQI_NOMBRE = '$nombre';
"
));

 $render = '';
 foreach ($lista as $modelo) {
    $encodeId = urlencode($modelo[EQI_ID]);
    $id = htmlentities($encodeId);
    $nombre = htmlentities($modelo[EQI_NOMBRE]);
    $correo = htmlentities($modelo[USU_EMAIL]);
    $render .=
     "<div class='bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300'>
          <div class='flex flex-col justify-between mb-4'>
              <h2 class='text-xl font-semibold text-gray-800'>Equipo: $nombre</h2>
              <h4 class='text-xl font-semibold text-gray-800'>Correo: $correo</h4>
          </div>
          <div class='flex justify-end space-x-2'>
              <button class='bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-300'
              onclick=\"
                   if (confirm('¿Esta seguro de agregar el equipo?')) {
                       consumeJson('../../srv/liga-equipo/agregar-equipo.php?idEquipo=$id&idLiga=$idLiga')
                       .then(() => location.href = 'ligas.html')
                       .catch(muestraError);
                   }
               \"
              >
                  <i class='fas fa-edit mr-2'></i>Agregar
              </button>
          </div>
      </div>";
 }

 
 devuelveJson(['equipos' => ['innerHTML' => $render]]);
 
});
