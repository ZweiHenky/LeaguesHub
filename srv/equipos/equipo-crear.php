<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaImagen.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/insert.php";
require_once __DIR__ . "../../../lib/php/devuelveCreated.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_EQUIPOS.php";
require_once __DIR__ . "../../usuarios/TABLA_USUARIOS.php";


ejecutaServicio(function () {

$pdo = Bd::pdo();

 $nombre = recuperaTexto("nombre");
 $logo = recuperaImagen("logo");
 $correo = recuperaTexto("correo");

 $nombre = validaNombre($nombre);

 $usuario =
 selectFirst(pdo: $pdo, from: USUARIO, where: [USU_EMAIL => $correo]);

 $pdo = Bd::pdo();
 insert(pdo: $pdo, into: EQUIPOS, values: [
    EQI_NOMBRE => $nombre,
    EQI_LOGO => $logo,
    USU_ID => $usuario[USU_ID]
]);
 $id = $pdo->lastInsertId();

 $encodeId = urlencode($id);
 devuelveCreated("/equipo.php?id=$encodeId", [
  "id" => ["value" => $id],
  "nombre" => ["value" => $nombre],
 ]);
});
