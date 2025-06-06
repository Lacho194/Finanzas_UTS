<?php
require_once __DIR__ . '/../models/Evento.php';

class EventosController {

    // Obtener todos los eventos disponibles
    public static function listarEventos() {
        return Evento::getAll();
    }

    // Crear nuevo evento
    public static function crearEvento($data) {
        return Evento::create($data);
    }

    // Editar evento
    public static function actualizarEvento($id, $data) {
        return Evento::update($id, $data);
    }

    // Eliminar evento
    public static function eliminarEvento($id) {
        return Evento::delete($id);
    }

    // Ver eventos en los que está inscrito un usuario
    public static function eventosDelUsuario($userId) {
        return Evento::getEventosPorUsuario($userId);
    }

    // Registrar un usuario en un evento
    public static function registrarEnEvento($userId, $eventoId) {
        return Evento::registrarUsuario($userId, $eventoId);
    }

    // Darse de baja de un evento
    public static function darseDeBaja($userId, $eventoId) {
        return Evento::cancelarRegistro($userId, $eventoId);
    }
}
?>
