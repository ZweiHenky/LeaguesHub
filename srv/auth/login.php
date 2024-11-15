<?php

require_once __DIR__ . "../../../lib/php/BAD_REQUEST.php";
require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/validaCorreo.php";
require_once __DIR__ . "../../../lib/php/ProblemDetails.php";
require_once __DIR__ . "../../../lib/php/selectFirst.php";
require_once __DIR__ . "../../../lib/php/fetchAll.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "/CORREO.php";
require_once __DIR__ . "/ROL_IDS.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "../../usuarios/TABLA_USUARIOS.php";
require_once __DIR__ . "/protege.php";

ejecutaServicio(function () {

 $sesion = protege();

 if ($sesion->correo !== "")
  throw new ProblemDetails(
   status: NO_AUTORIZADO,
   type: "/error/sesioniniciada.html",
   title: "Sesión iniciada.",
   detail: "La sesión ya está iniciada.",
  );

 $correo = recuperaTexto("email");
 $match = recuperaTexto("password");

 $correo = validaCorreo($correo);

 if ($match === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta el match.",
   type: "/error/faltamatch.html",
   detail: "La solicitud no tiene el valor de la contraseña.",
  );

 if ($match === "")
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Match en blanco.",
   type: "/error/matchenblanco.html",
   detail: "Pon texto en el campo match.",
  );

 $pdo = Bd::pdo();

 $usuario =
  selectFirst(pdo: $pdo, from: USUARIO, where: [USU_EMAIL => $correo]);

 if ($usuario === false || !password_verify($match, $usuario[USU_PASS]))
  throw new ProblemDetails(
   status: BAD_REQUEST,
   type: "/error/datosincorrectos.html",
   title: "Datos incorrectos.",
   detail: "El correo y/o el match proporcionados son incorrectos.",
  );

 $rolIds = fetchAll(
  $pdo->query(
   "SELECT ROL_ID
     FROM USU_ROL
     WHERE USU_ID = :USU_ID
     ORDER BY USU_ID"
  ),
  [":USU_ID" => $usuario[USU_ID]],
  PDO::FETCH_COLUMN
 );

 $_SESSION[CORREO] = $correo;
 $_SESSION[ROL_IDS] = $rolIds;
 devuelveJson([
  CORREO => $correo,
  ROL_IDS => $rolIds
 ]);
});
