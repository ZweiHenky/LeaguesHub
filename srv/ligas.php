<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_LIGAS.php";

ejecutaServicio(function () {
  
  
 $lista = select(pdo: Bd::pdo(),  from: LIGAS,  orderBy: LIG_NOMBRE);

 

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[LIG_ID]);
  $id = htmlentities($encodeId);
  $nombre = htmlentities($modelo[LIG_NOMBRE]);
  $render .=
   "<li>
     <p>
      <a href='modifica.html?id=$id'>$nombre</a>
     </p>
    </li>";
 }

 devuelveJson(["ligas" => ["innerHTML" => $render]]);
});