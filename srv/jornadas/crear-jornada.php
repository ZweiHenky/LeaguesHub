<?php

require_once __DIR__ . "../../../lib/php/ejecutaServicio.php";
require_once __DIR__ . "../../../lib/php/recuperaTexto.php";
require_once __DIR__ . "../../../lib/php/recuperaImagen.php";
require_once __DIR__ . "../../../lib/php/validaNombre.php";
require_once __DIR__ . "../../../lib/php/insert.php";
require_once __DIR__ . "../../../lib/php/fetchAll.php";
require_once __DIR__ . "../../../lib/php/devuelveCreated.php";
require_once __DIR__ . "../../../lib/php/generarPartidos.php";
require_once __DIR__ . "../../Bd.php";
require_once __DIR__ . "/TABLA_JORNADAS.php";
require_once __DIR__ . "/TABLA_PARTIDOS.php";
require_once __DIR__ . "../../ligas/TABLA_LIGAS.php";
require_once __DIR__ . "../../equipos/TABLA_EQUIPOS.php";


ejecutaServicio(function () {

$pdo = Bd::pdo();

 $id_liga = recuperaTexto("id_liga");
 $numeroJornada = intval(recuperaTexto("numeroJornada"));
 $dias = recuperaTexto("dia");
 $hora_inicio = recuperaTexto("hora_inicio");
 $hora_final = recuperaTexto("hora_final");
 $intervalo = recuperaTexto("intervalo");

 $arrayDias = [];

 if ($dias == "lunes-viernes") {
    $arrayDias = ["lunes", "martes", "miercoles", "jueves", "viernes"];
 }else if($dias == "sabado-domingo"){
    $arrayDias = ["sabado", "domingo"];
 }else if($dias == "sabado"){
    $arrayDias = ["sabado"];
 }else if($dias == "domingo"){
    $arrayDias = ["domingo"];
 }

 $equipos = fetchAll(Bd::pdo()->query(
    "SELECT E.EQI_NOMBRE
FROM EQUIPOS E
INNER JOIN LIGA_EQUIPO L ON E.EQI_ID = L.EQI_ID
WHERE L.LIG_ID = '$id_liga'
  "
  ));

  $jornadas = generarPartidos($equipos, $arrayDias, $hora_inicio, $hora_final, $intervalo);
 
 $pdo = Bd::pdo();
 $pdo->beginTransaction();

 foreach ($jornadas as $jornada) {
    $fecha = $jornada['dia'] . ' ' . $jornada['hora'];

    insert(pdo: $pdo, into: PARTIDOS, values: [
        PAR_FECHA => $fecha,
        PAR_LOCAL => $jornada["equipo1"], 
        PAR_VISITANTE => $jornada["equipo2"], 
    ]);

    
    $parId = $pdo->lastInsertId();

    // Preparamos la consulta SQL para insertar los datos en la tabla PARTIDOS
    $sql = "INSERT INTO JORNADAS (JOR_NUMERO, PAR_ID, LIG_ID) 
    VALUES (:numero, :par_id, :lig_id)";

    // Preparamos la declaración de PDO
    $stmt = $pdo->prepare($sql);

    // Vinculamos los valores a los parámetros de la consulta
    $stmt->bindParam(':numero', $numeroJornada);
    $stmt->bindParam(':par_id', $parId);
    $stmt->bindParam(':lig_id', $id_liga);

    // Ejecutamos la consulta
    $stmt->execute();

 }

 $pdo->commit();

 devuelveCreated("/equipo.php?id=$hora_inicio", [
  "id" => ["value" => $dias],
  "nombre" => ["value" => $hora_inicio],
 ]);
});
