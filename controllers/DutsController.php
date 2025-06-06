<?php
require_once __DIR__ . '/../models/Duts.php';

class DutsController {
    // Obtener Ranking con mas Duts
    public static function obtenerRanking() {
        return Duts::topUsuariosPorSaldo();
    }

    // Obtener DUTS actuales del usuario
    public static function obtenerSaldo($userId) {
        return Duts::getSaldoActual($userId);
    }

    // Crear una entrada de DUTS (ej. recarga o bonificación)
    public static function agregarDuts($data) {
    if (!isset($data['user_id'], $data['cantidad'], $data['descripcion'])) {
        return ['success' => false, 'message' => 'Faltan datos'];
    }

    if (!is_numeric($data['user_id']) || !is_numeric($data['cantidad'])) {
        return ['success' => false, 'message' => 'Datos inválidos'];
    }

    $resultado = Duts::crearMovimiento([
        'user_id' => $data['user_id'],
        'cantidad' => $data['cantidad'],
        'descripcion' => $data['descripcion'],
        'fecha' => $data['fecha'] ?? null // <--- NUEVA línea para permitir la fecha opcional
    ]);

    if ($resultado) {
        return ['success' => true, 'message' => 'DUTS agregados correctamente'];
    } else {
        return ['success' => false, 'message' => 'Error al agregar DUTS'];
    }
}


    // Obtener historial de movimientos
    public static function obtenerHistorial($userId) {
        return Duts::getHistorial($userId);
    }

    // Calcular promedios por distintos rangos de tiempo
    public static function promedioPor($userId, $rango) {
        switch ($rango) {
            case 'dia':
                return Duts::promedioPorDia($userId);
            case 'semana':
                return Duts::promedioPorSemana($userId);
            case 'mes':
                return Duts::promedioPorMes($userId);
            case 'año':
                return Duts::promedioPorAno($userId);
            case 'semestre':
                return Duts::promedioPorSemestre($userId);
            default:
                return ["error" => "Rango inválido"];
        }
    }
}
?>
