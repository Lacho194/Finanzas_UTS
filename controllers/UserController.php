<?php
require_once __DIR__ . '/../models/User.php';

class UserController {

    //Cambiar Password
    public static function cambiarPassword($id, $nuevaPassword, $passwordActual) {
    return User::cambiarPassword($id, $nuevaPassword, $passwordActual);
   }


    // Obtener perfil del usuario por ID (solo campos clave)
    public static function verPerfil($id) {
        return User::getById($id);
    }

    // Crear un nuevo usuario (registro)
    public static function registrarUsuario($data) {
        return User::create($data);
    }

    // Obtener todos los usuarios
    public static function listarUsuarios() {
        return User::getAll();
    }

    // Actualizar usuario
    public static function actualizarUsuario($id, $data) {
        return User::update($id, $data);
    }

    // Eliminar usuario
    public static function eliminarUsuario($id) {
        return User::delete($id);
    }

    public static function filtrarPorPrograma($programa) {
    return User::filtrarPorPrograma($programa);
    }

}
?>
