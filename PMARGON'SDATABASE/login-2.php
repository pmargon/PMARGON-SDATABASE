<?php

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

// Si ya está conectado, redirigimos
if (isset($_SESSION["conectado"])) {
    header("Location:tabla-personas/personas.php");
    exit;
}

$usuario  = recoge("usuario");
$password = recoge("password");

// Comprobamos que los datos no estén vacíos
if (empty($usuario) || empty($password)) {
    header("Location:login-1.php?aviso=Error: User and password are required.");
    exit;
}

// Conectamos a la base de datos
$pdo = conectaDb();

// Preparamos la consulta para buscar al usuario en la base de datos
$query = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
$query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$query->execute();

// Comprobamos si el usuario existe
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Si el usuario no existe
    header("Location:login-1.php?aviso=Error: Incorrect user or password.");
    exit;
}

// Verificamos si la contraseña es correcta utilizando bcrypt
if (!password_verify($password, $user['contrasena'])) {
    // Si la contraseña no es correcta
    header("Location:login-1.php?aviso=Error: Incorrect user or password.");
    exit;
}

// Si todo es correcto, establecemos la sesión
$_SESSION["conectado"] = true;

header("Location:tabla-personas/personas.php");
exit;

?>
