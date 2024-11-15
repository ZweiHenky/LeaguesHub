<?php

require_once __DIR__ . "../../../lib/php/NOT_FOUND.php";
require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "../../../lib/php/selectFirst.php";
require_once __DIR__ . "../../../lib/php/fetchAll.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "../../../lib/php/ProblemDetails.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_USUARIOS.php";

ejecutaServicio(function () {

 $correo = recuperaTexto("correo");

 $pdo = Bd::pdo();

 $modelo = selectFirst(pdo: $pdo, from: USUARIO, where: [USU_EMAIL => $correo]);

 if ($modelo === false) {
  $htmlId = htmlentities($correo);
  throw new ProblemDetails(
   title: "Usuario no encontrado.",
   status: NOT_FOUND,
   type: "/error/usuarionoencontrado.html",
   detail: "No se encontró ningún usuario con el correo $htmlId.",
  );
 } else {

  $rolIds = fetchAll(
   $pdo->query(
    "SELECT ROL_ID
      FROM USU_ROL
      WHERE USU_ID = :USU_ID
      ORDER BY USU_ID"
   ),
   [":USU_ID" => $modelo[USU_ID]],
   PDO::FETCH_COLUMN
  );

  devuelveJson([
   "id" => ["value" => $modelo[USU_ID]],
   "correo" => ["value" => $modelo[USU_EMAIL]],
   "tel" => ["value" => $modelo[USU_TEL]],
   "nombre" => ["value" => $modelo[USU_NOM]],
   "apellido" => ["value" => $modelo[USU_LAST]],
   "rolIds[]" => $rolIds
  ]);
 }
});
