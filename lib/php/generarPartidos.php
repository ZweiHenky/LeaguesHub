<?php
function generarPartidos($equipos, $dias, $horaInicio, $horaFin, $intervaloMinutos = 60) {
    $numEquipos = count($equipos);

    // Verificar que haya un número par de equipos; si no, agregar "DESCANSO".
    if ($numEquipos % 2 !== 0) {
        $equipos[] = ["EQI_NOMBRE" => "DESCANSO"];
        $numEquipos++;
    }

    $partidos = [];
    $horasDisponibles = generarHoras($horaInicio, $horaFin, $intervaloMinutos);
    $diaIndex = 0; // Índice del día actual.
    $horaIndex = 0; // Índice de la hora actual.

    for ($i = 0; $i < $numEquipos / 2; $i++) {
        $equipo1 = $equipos[$i]["EQI_NOMBRE"];
        $equipo2 = $equipos[$numEquipos - 1 - $i]["EQI_NOMBRE"];

        // Evitar partidos con "DESCANSO".
        if ($equipo1 !== "DESCANSO" && $equipo2 !== "DESCANSO") {
            $partidos[] = [
                "equipo1" => $equipo1,
                "equipo2" => $equipo2,
                "dia" => $dias[$diaIndex],
                "hora" => $horasDisponibles[$horaIndex],
            ];

            // Avanzar la hora.
            $horaIndex++;

            // Si no hay más horas disponibles, avanzar al siguiente día.
            if ($horaIndex >= count($horasDisponibles)) {
                $horaIndex = 0; // Reiniciar horas.
                $diaIndex = ($diaIndex + 1) % count($dias); // Cambiar de día.
            }
        }
    }

    return $partidos;
}

function generarHoras($horaInicio, $horaFin, $intervaloMinutos) {
    $horas = [];
    $inicio = strtotime($horaInicio);
    $fin = strtotime($horaFin);

    while ($inicio <= $fin) {
        $horas[] = date("H:i", $inicio);
        $inicio = strtotime("+$intervaloMinutos minutes", $inicio);
    }

    return $horas;
}
