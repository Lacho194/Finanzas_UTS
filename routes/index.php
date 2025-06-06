<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/DutsController.php';
require_once __DIR__ . '/../controllers/EventosController.php';
require_once __DIR__ . '/../controllers/TransferenciaController.php';

header("Content-Type: application/json");

session_start();

// Obtener datos enviados por POST
$input = json_decode(file_get_contents('php://input'), true);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

// Rutas
switch ($method) {
    case 'POST':
        switch ($action) {
            case 'login':
                echo json_encode(AuthController::login($input['username'], $input['password']));
                break;
            case 'registrar_usuario':
                echo json_encode(UserController::registrarUsuario($input));
                break;
            case 'agregar_duts':
                echo json_encode(DutsController::agregarDuts($input));
                break;
            case 'transferir':
                echo json_encode(TransferenciaController::enviar(
                    $input['origen_id'],
                    $input['destino_id'],
                    $input['cantidad'],
                    $input['descripcion'] ?? null // importante si usas descripción
            ));
                break;

            case 'crear_evento':
                $result = EventosController::crearEvento($input);
                echo json_encode(['success' => $result]);
                break;
            case 'registrar_en_evento':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $userId = $_POST['user_id'] ?? null;
                    $eventoId = $_POST['evento_id'] ?? null;

                if ($userId && $eventoId) {
                    $resultado = EventosController::registrarEnEvento($userId, $eventoId);
                    echo json_encode(['success' => $resultado]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
             }
                } else {
                    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
          }
                break;


            case 'baja_evento':
                echo json_encode(EventosController::darseDeBaja($input['user_id'], $input['evento_id']));
                break;
            case 'cambiar_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_POST['user_id'] ?? null;
            $passwordActual = $_POST['password_actual'] ?? null;
            $nuevaPassword = $_POST['nueva_password'] ?? null;

            if ($userId && $passwordActual && $nuevaPassword) {
                require_once __DIR__ . '/../models/User.php'; // Ajusta la ruta si es necesario

                $resultado = User::cambiarPassword($userId, $nuevaPassword, $passwordActual);
                echo json_encode($resultado);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Faltan datos para cambiar la contraseña"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Método no permitido"
            ]);
        }
        break;



        }
        break;

    case 'GET':
        
        switch ($action) {
            case 'ver_perfil':
                if (isset($_GET['id'])) {
                    echo json_encode(UserController::verPerfil($_GET['id']));
                } elseif (isset($_SESSION['user_id'])) {
                    echo json_encode(UserController::verPerfil($_SESSION['user_id']));
                } else {
                    echo json_encode(["error" => "ID no proporcionado ni sesión iniciada"]);
          }
    break;

            case 'listar_usuarios':
                echo json_encode(UserController::listarUsuarios());
                break;
            case 'saldo_duts':
                echo json_encode(DutsController::obtenerSaldo($_GET['user_id']));
                break;
            case 'historial_duts':
    if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
        echo json_encode(["error" => "ID de usuario inválido"]);
    } else {
        $resultado = DutsController::obtenerHistorial($_GET['user_id']);
        if (empty($resultado)) {
            echo json_encode(["message" => "No hay datos"]);
        } else {
            echo json_encode($resultado);
        }
    }
    break;

            case 'promedio_duts':
                echo json_encode(DutsController::promedioPor($_GET['user_id'], $_GET['rango']));
                break;
            case 'listar_eventos':
                echo json_encode(EventosController::listarEventos());
                break;
            case 'eventos_usuario':
                echo json_encode(EventosController::eventosDelUsuario($_GET['user_id']));
                break;
            case 'historial_transferencias':
                $sessionId = $_SESSION['user_id'] ?? null;
                $queryId = $_GET['user_id'] ?? null;

                if ($sessionId && $sessionId == $queryId) {
                    echo json_encode(TransferenciaController::historial($sessionId));
                } else {
                    echo json_encode(["error" => "Acceso no autorizado"]);
         }
                break;

            case 'filtrar_eventos':
                echo json_encode(Evento::filtrarPorFecha($_GET['fecha']));
                break;
            case 'ranking_duts':
    require_once __DIR__ . '/../controllers/DutsController.php';

    $ranking = DutsController::obtenerRanking();

    $resultado = array_map(function ($usuario) {
        return [
            "nombres" => $usuario["nombres"] ?? $usuario["username"],
            "apellidos" => $usuario["apellidos"] ?? '',
            "programa" => $usuario["programa"] ?? 'No definido',
            "total_duts" => $usuario["saldo"]
        ];
    }, $ranking);
    header('Content-Type: application/json');
    echo json_encode($resultado);
    break;

            case 'filtrar_usuarios':
                echo json_encode(UserController::filtrarPorPrograma($_GET['programa']));
                break;
            




        }
        break;

    default:
        echo json_encode(["error" => "Método HTTP no soportado"]);
        break;
}
?>