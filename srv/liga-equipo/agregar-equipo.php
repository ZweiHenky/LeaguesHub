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
require_once __DIR__ . "/LIGA_EQUIPO.php";
require_once __DIR__ . "../../ligas/TABLA_LIGAS.php";
require_once __DIR__ . "../../equipos/TABLA_EQUIPOS.php";


ejecutaServicio(function () {


 $idEquipo = recuperaTexto("idEquipo");
 $idLiga = recuperaTexto("idLiga");

 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 insertBridges(
  pdo: $pdo,
  into: LIGA_EQUIPO,
  valuesDePadre: [LIG_ID => $idLiga],
  valueDeHijos: [EQI_ID => [$idEquipo]],
 );

 $pdo->commit();

 devuelveJson([
  "idLiga" => ["value" => $idLiga],
  "idEquipo" => ["value" => $idEquipo],
 ]);

});
