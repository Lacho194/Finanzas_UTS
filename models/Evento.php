<?php
require_once __DIR__ . '/../config/db.php';

class Evento {

    // Obtener todos los eventos
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM eventos ORDER BY fecha ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un nuevo evento
    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO eventos (nombre, descripcion, fecha, lugar) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['fecha'],
            $data['lugar']
        ]);
    }

    // Actualizar evento existente
    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE eventos SET nombre = ?, descripcion = ?, fecha = ?, lugar = ? WHERE id = ?");
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['fecha'],
            $data['lugar'],
            $id
        ]);
    }

    // Eliminar un evento
    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM eventos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Registrar usuario a un evento
    public static function registrarUsuario($userId, $eventoId) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT IGNORE INTO eventos_usuarios (user_id, evento_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $eventoId]);
    }

    // Cancelar registro de usuario
    public static function cancelarRegistro($userId, $eventoId) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM eventos_usuarios WHERE user_id = ? AND evento_id = ?");
        return $stmt->execute([$userId, $eventoId]);
    }

    // Obtener eventos en los que está registrado un usuario
    public static function getEventosPorUsuario($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT e.* FROM eventos e
            INNER JOIN eventos_usuarios eu ON e.id = eu.evento_id
            WHERE eu.user_id = ?
            ORDER BY e.fecha ASC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Filtrar eventos por fecha

    public static function filtrarPorFecha($fecha) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM eventos WHERE fecha = ?");
    $stmt->execute([$fecha]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
