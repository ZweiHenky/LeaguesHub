<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaArray.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/insert.php";
require_once __DIR__ . "../../../lib/php/insertBridges.php";
require_once __DIR__ . "../../../lib/php/devuelveCreated.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "../TABLA_USUARIOS.php";
require_once __DIR__ . "../TABLA_ROLS.php";
require_once __DIR__ . "../TABLA_USUARIO_ROL.php";

ejecutaServicio(function () {

 $nombre = recuperaTexto("name");
 $apellido = recuperaTexto("last_name");
 $tel = recuperaTexto("tel");
 $correo = recuperaTexto("email");
 $pass = recuperaTexto("password");
 $rolIds = recuperaArray("rolIds");

 $nombre = validaNombre($nombre);

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 insert(pdo: $pdo, into: USUARIO, values: [USU_NOM => $nombre, USU_LAST => $apellido, USU_TEL => $tel, USU_EMAIL => $correo, USU_PASS => password_hash($pass, PASSWORD_DEFAULT)]);
 $usuId = $pdo->lastInsertId();
 insertBridges(
  pdo: $pdo,
  into: USU_ROL,
  valuesDePadre: [USU_ID => $usuId],
  valueDeHijos: [ROL_ID => $rolIds]
 );

 $pdo->commit();

 $encodeUsuId = urlencode($usuId);
 devuelveCreated("/srv/usuario.php?id=$encodeUsuId", [
  "id" => ["value" => $usuId],
  "nombre" => ["value" => $nombre],
  "rolIds" => ["value" => $rolIds],
 ]);
});
