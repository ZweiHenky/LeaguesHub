<?php

require_once __DIR__ . "/BAD_REQUEST.php";
require_once __DIR__ . "/ProblemDetails.php";

function validaCorreo($cue)
{

 if ($cue === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta el correo.",
   type: "/error/faltacue.html",
   detail: "La solicitud no tiene el valor de correo.",
  );

 $trimCue = trim($cue);

 if ($trimCue === "")
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Cue en blanco.",
   type: "/error/cuenblanco.html",
   detail: "Pon texto en el campo correo.",
  );

 return $trimCue;
}
