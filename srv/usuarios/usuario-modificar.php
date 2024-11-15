<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaArray.php";
require_once __DIR__ . "../../../lib/php/update.php";
require_once __DIR__ . "../../../lib/php/delete.php";
require_once __DIR__ . "../../../lib/php/insertBridges.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_USUARIOS.php";
require_once __DIR__ . "/TABLA_ROLS.php";
require_once __DIR__ . "/TABLA_USUARIO_ROL.php";

ejecutaServicio(function () {

 $usuId = recuperaIdEntero("id");
 $correo = recuperaTexto("correo");
 $nombre = recuperaTexto("nombre");
 $apellido = recuperaTexto("apellido");
 $tel = recuperaTexto("tel");
//  $password = recuperaTexto("password");
 $rolIds = recuperaArray("rolIds");

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 update(
  pdo: $pdo,
  table: USUARIO,
  set: [USU_EMAIL => $correo, USU_NOM => $nombre, USU_LAST => $apellido, USU_TEL => $tel],
  where: [USU_ID => $usuId]
 );
 delete(pdo: $pdo, from: USU_ROL, where: [USU_ID => $usuId]);
 insertBridges(
  pdo: $pdo,
  into: USU_ROL,
  valuesDePadre: [USU_ID => $usuId],
  valueDeHijos: [ROL_ID => $rolIds]
 );

 $pdo->commit();

 devuelveJson([
  "id" => ["value" => $usuId],
  "correo" => ["value" => $correo],
  "rolIds" => ["value" => $rolIds],
 ]);
});
