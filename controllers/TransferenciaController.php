<?php
require_once __DIR__ . '/../models/Transferencia.php';

class TransferenciaController {

    // Realizar una transferencia de DUTS entre usuarios
    public static function enviarDuts($origenId, $destinoId, $cantidad) {
        return Transferencia::enviarDuts($origenId, $destinoId, $cantidad);
    }
    
    public static function enviar($origen_id, $destino_id, $cantidad, $descripcion = null) {
    return Transferencia::enviarDuts($origen_id, $destino_id, $cantidad);
}

    // Obtener historial de transferencias del usuario
    public static function historial($userId) {
        return Transferencia::historial($userId);
    }


    
}
?>
