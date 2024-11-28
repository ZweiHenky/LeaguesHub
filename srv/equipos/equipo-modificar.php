<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaImagen.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/update.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_EQUIPOS.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");
 $nombre = recuperaTexto("nombre");
 $logo = recuperaImagen("logo");

 $nombre = validaNombre($nombre);
 if ($logo) {
    update(
        pdo: Bd::pdo(),
        table: EQUIPOS,
        set: [
          EQI_NOMBRE => $nombre,
          EQI_LOGO => $logo
       ],
        where: [EQI_ID => $id]
       );
 }else{
    update(
        pdo: Bd::pdo(),
        table: EQUIPOS,
        set: [
          EQI_NOMBRE => $nombre,
       ],
        where: [EQI_ID => $id]
       );
 }



 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
  "logo" => ["value" => $logo],
 ]);
});
