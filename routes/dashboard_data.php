<?php
require_once __DIR__ . '/../controllers/DutsController.php';
require_once __DIR__ . '/../controllers/UserController.php';

header("Content-Type: application/json");
session_start();

// Obtener user_id desde sesión o GET
$userId = $_SESSION['user_id'] ?? $_GET['user_id'] ?? null;

if (!$userId) {
    echo json_encode(["error" => "ID de usuario no proporcionado"]);
    exit;
}

// Obtener perfil
$perfil = UserController::verPerfil($userId);
if (!$perfil || !isset($perfil['nombres']) || !isset($perfil['apellidos'])) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

// Obtener datos de DUTS y promedios
$saldo = DutsController::obtenerSaldo($userId);
$promedioDia = DutsController::promedioPor($userId, "dia");
$promedioSemana = DutsController::promedioPor($userId, "semana");
$promedioMes = DutsController::promedioPor($userId, "mes");
$promedioSemestre = DutsController::promedioPor($userId, "semestre");
$promedioAnual = DutsController::promedioPor($userId, "anual");

// Formatear respuesta
echo json_encode([
    "nombre" => $perfil['nombres'] . ' ' . $perfil['apellidos'],
    "duts_actuales" => isset($saldo['saldo']) ? floatval($saldo['saldo']) : 0,
    "promedio_dia" => isset($promedioDia['promedio']) ? floatval($promedioDia['promedio']) : 0,
    "promedio_semana" => isset($promedioSemana['promedio']) ? floatval($promedioSemana['promedio']) : 0,
    "promedio_mes" => isset($promedioMes['promedio']) ? floatval($promedioMes['promedio']) : 0,
    "promedio_semestre" => isset($promedioSemestre['promedio']) ? floatval($promedioSemestre['promedio']) : 0,
    "promedio_anual" => isset($promedioAnual['promedio']) ? floatval($promedioAnual['promedio']) : 0
]);
