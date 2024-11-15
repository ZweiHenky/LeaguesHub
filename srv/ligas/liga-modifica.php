<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaImagen.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/update.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_LIGAS.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");
 $nombre = recuperaTexto("nombre");
 $direccion = recuperaTexto("direccion");
 $descripcion = recuperaTexto("descripcion");
 $premio = recuperaTexto("premio");
 $logo = recuperaImagen("logo");

 $nombre = validaNombre($nombre);
 if ($logo) {
    update(
        pdo: Bd::pdo(),
        table: LIGAS,
        set: [
          LIG_NOMBRE => $nombre,
          LIG_DESCRIPCION => $descripcion, 
          LIG_DIRECCION => $direccion, 
          LIG_PREMIO => $premio,
          LIG_LOGO => $logo
       ],
        where: [LIG_ID => $id]
       );
 }else{
    update(
        pdo: Bd::pdo(),
        table: LIGAS,
        set: [
          LIG_NOMBRE => $nombre,
          LIG_DESCRIPCION => $descripcion, 
          LIG_DIRECCION => $direccion, 
          LIG_PREMIO => $premio
       ],
        where: [LIG_ID => $id]
       );
 }



 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
  "descripcion" => ["value" => $descripcion],
  "direccion" => ["value" => $direccion],
  "premio" => ["value" => $premio],
  "logo" => ["value" => $logo],
 ]);
});
