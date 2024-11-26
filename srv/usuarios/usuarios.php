<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '../../usuarios/TABLA_USUARIOS.php';
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";

ejecutaServicio(function () {
  
 $correo = recuperaTexto("correo");

 if ($correo == "") {
    $lista = select(pdo: Bd::pdo(),  from: USUARIO,  orderBy: USU_ID);
 }else{
    $lista = select(pdo: Bd::pdo(),  from: USUARIO,  orderBy: USU_ID, where: [USU_EMAIL => $correo]);
 }

 $render = '';
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[USU_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[USU_NOM]);
  $apellido = "";
  if ($modelo[USU_LAST]) {
    $apellido = htmlentities($modelo[USU_LAST]);
  }
  $correo = htmlentities($modelo[USU_EMAIL]);

  $pdo = Bd::pdo();

  $rolIds = fetchAll(
    $pdo->query(
     'SELECT ROL_ID
       FROM USU_ROL
       WHERE USU_ID = :USU_ID
       ORDER BY USU_ID'
    ),
    [':USU_ID' => $modelo[USU_ID]],
    PDO::FETCH_COLUMN
   );


   $render .= "
   <tr>
       <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>$id</td>
       <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>$nombre $apellido</td>
       <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>$correo</td>
       <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>Admin</td>
       <td class='px-6 py-4 whitespace-nowrap text-right text-sm font-medium'>
           <button class='bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded'
               onclick=\"
                   if (confirm('Confirma la eliminación')) {
                       consumeJson('../../srv/usuarios/usuario-eliminar.php?id=$id')
                       .then(() => location.href = 'usuarios.html')
                       .catch(muestraError);
                   }
               \">
               Eliminar
           </button>
       </td>
   </tr>
   ";
 }

 
 devuelveJson(['usuario' => ['innerHTML' => $render]]);
 
});
