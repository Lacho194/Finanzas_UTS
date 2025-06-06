<?php
$host = "localhost";        // Dirección del servidor MySQL (local si usas XAMPP)
$dbname = "uts_finanzas";   // Nombre de la base de datos que creaste/importaste
$user = "root";             // Usuario por defecto en XAMPP
$pass = "";                 // Contraseña vacía por defecto en XAMPP

try {
    // Conexión PDO a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // Activar errores como excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Mostrar error si la conexión falla
    die("Conexión fallida: " . $e->getMessage());
}
?>
