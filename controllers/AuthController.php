<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // Iniciar sesión con username y password
    public static function login($username, $password) {
        global $pdo;

        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Remover contraseña del array
            unset($user['password']);

            // Guardar el ID del usuario en la sesión
            $_SESSION['user_id'] = $user['id'];

            return [
                "exito" => true,
                "message" => "Inicio de sesión exitoso",
                "user" => $user
            ];
        }

        return [
            "exito" => false,
            "error" => "Credenciales inválidas"
        ];
    }

    // Registrar usuario
    public static function register($data) {
        return User::create($data);
    }
}
?>
