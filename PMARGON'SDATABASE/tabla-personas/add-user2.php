<?php
require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Add User - Step 2");

$username = recoge("username");
$password = recoge("password");

// Comprobamos que no se intenta crear un registro vacío
$registroNoVacioOk = false;

if ($username == "" || $password == "") {
    print "    <p class=\"aviso\">Both fields are required. The user was not added.</p>\n";
} else {
    $registroNoVacioOk = true;
}

// Comprobamos que no se intenta crear un usuario idéntico a uno que ya existe
$registroDistintoOk = false;

if ($registroNoVacioOk) {
    $consulta = "SELECT COUNT(*) FROM usuarios
                 WHERE usuario = :usuario"; 

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":usuario" => $username])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">The username already exists.</p>\n";
    } else {
        $registroDistintoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($registroNoVacioOk && $registroDistintoOk) {
    // Insertamos el usuario en la tabla
    $consulta = "INSERT INTO usuarios
                 (usuario, contrasena) 
                 VALUES (:usuario, :contrasena)"; // 'usuario' y 'contrasena' son los nombres correctos de las columnas en la base de datos

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        // Encriptar la contraseña antes de guardarla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if (!$resultado->execute([":usuario" => $username, ":contrasena" => $hashedPassword])) {
            print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } else {
            print "    <p>User added successfully.</p>\n";
        }
    }
}

pie();
?>
