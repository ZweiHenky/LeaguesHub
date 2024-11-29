<?php

require_once __DIR__ . '../../../lib/php/ejecutaServicio.php';
require_once __DIR__ . '../../../lib/php/select.php';
require_once __DIR__ . '../../../lib/php/devuelveJson.php';
require_once __DIR__ . '../../Bd.php';
require_once __DIR__ . '../../equipos/TABLA_EQUIPOS.php';
require_once __DIR__ . '../../usuarios/TABLA_USUARIOS.php';
require_once __DIR__ . '../../../lib/php/recuperaTexto.php';

ejecutaServicio(function () {
  
 $idLiga = recuperaTexto('id');
  
// Consulta para obtener los datos de partidos
$data = fetchAll(Bd::pdo()->query("
    SELECT P.*, J.*
    FROM JORNADAS J
    INNER JOIN LIGAS L ON L.LIG_ID = J.LIG_ID  -- Unir por LIG_ID
    INNER JOIN PARTIDOS P ON P.PAR_ID = J.PAR_ID  -- Unir por PAR_ID
    WHERE J.LIG_ID = '$idLiga'
"));



// Inicializa el array de estadísticas
$stats = [];

// Recolectar todos los equipos únicos
foreach ($data as $partido) {
    $local = $partido['PAR_LOCAL'];
    $visitante = $partido['PAR_VISITANTE'];

    // Agregar cada equipo al array de estadísticas si no está ya
    if (!isset($stats[$local])) {
        $stats[$local] = ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0];
    }
    if (!isset($stats[$visitante])) {
        $stats[$visitante] = ['played' => 0, 'won' => 0, 'drawn' => 0, 'lost' => 0, 'points' => 0];
    }
}

// Procesar los partidos para calcular estadísticas
foreach ($data as $partido) {
    $local = $partido['PAR_LOCAL'];
    $visitante = $partido['PAR_VISITANTE'];
    $golLocal = $partido['PAR_GOL_LOCAL'];
    $golVisitante = $partido['PAR_GOL_VISITANTE'];

    // Ignorar partidos no jugados (goles null)
    if (is_null($golLocal) || is_null($golVisitante)) {
        continue;
    }

    // Incrementar partidos jugados
    $stats[$local]['played'] += 1;
    $stats[$visitante]['played'] += 1;

    // Determinar resultado
    if ($golLocal > $golVisitante) {
        $stats[$local]['won'] += 1;
        $stats[$local]['points'] += 3;
        $stats[$visitante]['lost'] += 1;
    } elseif ($golLocal < $golVisitante) {
        $stats[$visitante]['won'] += 1;
        $stats[$visitante]['points'] += 3;
        $stats[$local]['lost'] += 1;
    } else {
        $stats[$local]['drawn'] += 1;
        $stats[$visitante]['drawn'] += 1;
        $stats[$local]['points'] += 1;
        $stats[$visitante]['points'] += 1;
    }
}

// Ordenar equipos por puntos descendentes y luego por nombre
uasort($stats, function ($a, $b) {
    return $b['points'] <=> $a['points'];
});

// Generar la tabla HTML
$render = '
    <thead>
        <tr>
            <th>#</th>
            <th>Equipo</th>
            <th>J</th>
            <th>G</th>
            <th>E</th>
            <th>P</th>
            <th>Pts</th>
        </tr>
    </thead>
    <tbody>';

$position = 1;
foreach ($stats as $team => $stat) {
    $render .= "<tr>
        <td class='p-2'>{$position}</td>
        <td class='p-2 text-center'>{$team}</td>
        <td class='p-2 text-center'>{$stat['played']}</td>
        <td class='p-2 text-center'>{$stat['won']}</td>
        <td class='p-2 text-center'>{$stat['drawn']}</td>
        <td class='p-2 text-center'>{$stat['lost']}</td>
        <td class='p-2 text-center font-bold'>{$stat['points']}</td>
    </tr>";
    $position++;
}

$render .= '</tbody>';


 
 devuelveJson(['tabla-general' => ['innerHTML' => $render]]);
 
});
