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


 $correo = recuperaTexto("correo");

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 $modelo = selectFirst(pdo: $pdo, from: USUARIO, where: [USU_EMAIL => $correo]);

 delete(pdo: $pdo, from: USU_ROL, where: [USU_ID => $modelo[USU_ID]]);
 insertBridges(
  pdo: $pdo,
  into: USU_ROL,
  valuesDePadre: [USU_ID => $modelo[USU_ID]],
  valueDeHijos: [ROL_ID => ["Capitan", "Administrador"]]
 );

 $pdo->commit();

 devuelveJson([
  "id" => ["value" => $modelo[USU_ID]],
  "correo" => ["value" => $correo],
  "rolIds" => ["value" => ["Capitan","Administrador"]],
 ]);
});
