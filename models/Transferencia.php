<?php
require_once __DIR__ . '/../config/db.php';

class Transferencia {

    // Agregar duts a un usuario
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
            'descripcion' => $data['descripcion']
        ]);

        if ($resultado) {
            return ['success' => true, 'message' => 'DUTS agregados correctamente'];
        } else {
            return ['success' => false, 'message' => 'Error al agregar DUTS'];
        }
    }

    // Registrar una transferencia entre usuarios
     public static function enviarDuts($origenId, $destinoId, $cantidad, $descripcion = null) {
        global $pdo;

        // Verificar si el usuario origen tiene suficiente saldo
        $stmt = $pdo->prepare("SELECT SUM(cantidad) AS saldo FROM duts WHERE user_id = ?");
        $stmt->execute([$origenId]);
        $saldo = $stmt->fetch(PDO::FETCH_ASSOC)['saldo'];

        if ($saldo < $cantidad) {
            return ['success' => false, 'message' => 'Saldo insuficiente para realizar la transferencia'];
        }

        // Insertar la transferencia
        $query = "INSERT INTO transferencias (origen_id, destino_id, cantidad, fecha) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);

        // Verificar si la inserción fue exitosa
        if ($stmt->execute([$origenId, $destinoId, $cantidad])) {
            // Actualizar el saldo de ambos usuarios
            $pdo->prepare("UPDATE duts SET cantidad = cantidad - ? WHERE user_id = ?")->execute([$cantidad, $origenId]);
            $pdo->prepare("UPDATE duts SET cantidad = cantidad + ? WHERE user_id = ?")->execute([$cantidad, $destinoId]);

            return ['success' => true, 'message' => 'Transferencia realizada con éxito'];
        } else {
            return ['success' => false, 'message' => 'Error al realizar la transferencia'];
        }
    }

    // Obtener historial de transferencias realizadas o recibidas por un usuario
    public static function historial($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT 
                t.id,
                u1.username AS origen,
                u2.username AS destino,
                t.cantidad,
                t.fecha
            FROM transferencias t
            JOIN users u1 ON t.origen_id = u1.id
            JOIN users u2 ON t.destino_id = u2.id
            WHERE t.origen_id = ? OR t.destino_id = ?
            ORDER BY t.fecha DESC
        ");
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
