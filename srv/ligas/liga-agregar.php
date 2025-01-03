<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaImagen.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/insert.php";
require_once __DIR__ . "../../../lib/php/devuelveCreated.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_LIGAS.php";
require_once __DIR__ . "../../usuarios/TABLA_USUARIOS.php";


ejecutaServicio(function () {

$pdo = Bd::pdo();

 $nombre = recuperaTexto("nombre");
 $direccion = recuperaTexto("direccion");
 $descripcion = recuperaTexto("descripcion");
 $premio = recuperaTexto("premio");
 $logo = recuperaImagen("logo");
 $correo = recuperaTexto("correo");

 $nombre = validaNombre($nombre);

 $usuario =
 selectFirst(pdo: $pdo, from: USUARIO, where: [USU_EMAIL => $correo]);

 $pdo = Bd::pdo();
 insert(pdo: $pdo, into: LIGAS, values: [
    LIG_NOMBRE => $nombre,
    LIG_DESCRIPCION => $descripcion, 
    LIG_DIRECCION => $direccion, 
    LIG_PREMIO => $premio,
    LIG_LOGO => $logo,
    USU_ID => $usuario[USU_ID]
]);
 $id = $pdo->lastInsertId();

 $encodeId = urlencode($id);
 devuelveCreated("/liga.php?id=$encodeId", [
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
 ]);
});
