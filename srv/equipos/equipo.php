<?php

require_once __DIR__ . "../../../lib/php/NOT_FOUND.php";
require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "../../../lib/php/selectFirst.php";
require_once __DIR__ . "../../../lib/php/ProblemDetails.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_EQUIPOS.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");

 $modelo =
  selectFirst(pdo: Bd::pdo(),  from: EQUIPOS,  where: [EQI_ID => $id]);

 if ($modelo === false) {
  $idHtml = htmlentities($id);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "liga no encontrada.",
   type: "/error/liganoencontrada.html",
   detail: "No se encontró ningúna liga con el id $idHtml.",
  );
 }

 devuelveJson([
  "id" => ["value" => $id],
  "nombre" => ["value" => $modelo[EQI_NOMBRE]],
  "mostrarLogo" => ["src" => $modelo[EQI_LOGO]]
 ]);
});
