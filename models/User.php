<?php
require_once __DIR__ . '/../config/db.php';

class User {

    // Obtener perfil básico de un usuario por ID
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, nombres, apellidos, intereses, programa, semestre FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo usuario (registro)
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users 
            (nombres, apellidos, email, ciudad, pais, descripcion, intereses, programa, semestre, username, password, rol)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombres'],
            $data['apellidos'],
            $data['email'],
            $data['ciudad'],
            $data['pais'],
            $data['descripcion'],
            $data['intereses'],
            $data['programa'],
            $data['semestre'],
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['rol']
        ]);
    }

    // Obtener todos los usuarios
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT id, nombres, apellidos, email, programa, semestre, rol FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualizar datos de un usuario
    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET
            nombres = ?, apellidos = ?, email = ?, ciudad = ?, pais = ?, descripcion = ?, intereses = ?, programa = ?, semestre = ?, rol = ?
            WHERE id = ?");
        return $stmt->execute([
            $data['nombres'],
            $data['apellidos'],
            $data['email'],
            $data['ciudad'],
            $data['pais'],
            $data['descripcion'],
            $data['intereses'],
            $data['programa'],
            $data['semestre'],
            $data['rol'],
            $id
        ]);
    }

    // Eliminar usuario
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    //Cambiar contraseña

    public static function cambiarPassword($id, $nuevaPassword, $passwordActual) {
    global $pdo;

    // Obtener la contraseña actual del usuario
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return ["success" => false, "message" => "Usuario no encontrado"];
    }

    // Verificar que la contraseña actual coincida
    if (!password_verify($passwordActual, $usuario['password'])) {
        return ["success" => false, "message" => "Contraseña actual incorrecta"];
    }

    // Actualizar la nueva contraseña
    $nuevaHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$nuevaHash, $id]);

    return ["success" => true, "message" => "Contraseña actualizada correctamente"];
   }



   public static function filtrarPorPrograma($programa) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE programa LIKE ?");
    $stmt->execute(["%$programa%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar login
    public static function login($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return [
            "success" => true,
            "user" => [
                "id" => $user['id'],
                "nombres" => $user['nombres'],
                "apellidos" => $user['apellidos'],
                "rol" => $user['rol']
            ]
        ];
    } else {
        return ["success" => false, "message" => "Credenciales inválidas"];
    }
}



}
?>
