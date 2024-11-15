<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/devuelveJson.php";
require_once __DIR__ . "/CORREO.php";
require_once __DIR__ . "/ROL_IDS.php";

ejecutaServicio(function () {

 session_start();

 if (isset($_SESSION[CORREO])) {
  unset($_SESSION[CORREO]);
 }
 if (isset($_SESSION[ROL_IDS])) {
  unset($_SESSION[ROL_IDS]);
 }

 session_destroy();

 devuelveJson([]);
});
