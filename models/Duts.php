<?php
require_once __DIR__ . '/../config/db.php';


class Duts {

    private static function calcularPromedio($query, $userId) {
    global $pdo;
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    // Obtener el saldo actual del usuario
    public static function getSaldoActual($userId) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT SUM(cantidad) AS saldo FROM duts WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear un movimiento de DUTS (recarga o descuento)
    public static function crearMovimiento($data) {
    global $pdo;

    $query = "INSERT INTO duts (user_id, cantidad, descripcion, fecha) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    return $stmt->execute([
        $data['user_id'],
        $data['cantidad'],
        $data['descripcion'],
        $data['fecha'] ?? date('Y-m-d H:i:s') // usa fecha actual si no se proporciona
    ]);
}
    



    // Obtener historial completo de movimientos
    public static function getHistorial($userId, $limit = 50, $offset = 0) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT * FROM duts 
        WHERE user_id = ? 
        ORDER BY fecha DESC 
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $userId, PDO::PARAM_INT);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    // Promedio por día
    public static function promedioPorDia($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(total_dia) AS promedio_dia FROM (
                SELECT DATE(fecha) as dia, SUM(cantidad) AS total_dia
                FROM duts
                WHERE user_id = ?
                GROUP BY DATE(fecha)
            ) AS subconsulta");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Promedio por semana
    public static function promedioPorSemana($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(total_semana) AS promedio_semana FROM (
                SELECT WEEK(fecha) as semana, YEAR(fecha) as anio, SUM(cantidad) AS total_semana
                FROM duts
                WHERE user_id = ?
                GROUP BY WEEK(fecha), YEAR(fecha)
            ) AS subconsulta");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Promedio por mes
    public static function promedioPorMes($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(total_mes) AS promedio_mes FROM (
                SELECT MONTH(fecha) as mes, YEAR(fecha) as anio, SUM(cantidad) AS total_mes
                FROM duts
                WHERE user_id = ?
                GROUP BY MONTH(fecha), YEAR(fecha)
            ) AS subconsulta");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Promedio por año
    public static function promedioPorAno($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(total_ano) AS promedio_ano FROM (
                SELECT YEAR(fecha) as anio, SUM(cantidad) AS total_ano
                FROM duts
                WHERE user_id = ?
                GROUP BY YEAR(fecha)
            ) AS subconsulta");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Promedio por semestre (1: enero-junio, 2: julio-diciembre)
    public static function promedioPorSemestre($userId) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(total_semestre) AS promedio_semestre FROM (
                SELECT 
                    IF(MONTH(fecha) <= 6, '1', '2') AS semestre,
                    YEAR(fecha) AS anio,
                    SUM(cantidad) AS total_semestre
                FROM duts
                WHERE user_id = ?
                GROUP BY semestre, anio
            ) AS subconsulta");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Ranking de usuarios con más DUTS

    public static function topUsuariosPorSaldo() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT u.id, u.username, u.nombres, u.apellidos, u.programa, SUM(d.cantidad) AS saldo
        FROM duts d
        JOIN users u ON d.user_id = u.id
        GROUP BY u.id
        ORDER BY saldo DESC
        LIMIT 10
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
