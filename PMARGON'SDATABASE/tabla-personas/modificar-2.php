<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Modificar 2");

$id = recoge("id");

// Comprobamos el dato recibido
$idOk = false;

if ($id == "") {
    print "    <p class=\"aviso\">No record has been selected.</p>\n";
} else {
    $idOk = true;
}

// Comprobamos que el registro con el id recibido existe en la base de datos
$registroEncontradoOk = false;

if ($idOk) {
    $consulta = "SELECT COUNT(*) FROM personas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">No records found.</p>\n";
    } else {
        $registroEncontradoOk = true;
    }
}

// Si todas las comprobaciones han tenido éxito ...
if ($idOk && $registroEncontradoOk) {
    // Recuperamos el registro con el id recibido para incluir sus valores en el formulario
    $consulta = "SELECT * FROM personas
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error preparing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error executing the query. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        $registro = $resultado->fetch();

    print "<form action=\"modificar-3.php\" method=\"get\">";
    print "      <p>Modifique los campos que desee:</p>";

    print "      <table>";
    print "        <tr>";
    print "          <td>Nombre:</td>";
    print "          <td><input type=\"text\" name=\"Name\" value=\"$registro[nombre]\" autofocus></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Apellidos:</td>";
    print "          <td><input type=\"text\" name=\"Surname\" value=\"$registro[apellidos]\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Teléfono:</td>";
    print "          <td><input type=\"text\" name=\"Phone\" value=\"$registro[telefono]\"></td>";
    print "        </tr>";
    print "        <tr>";
    print "          <td>Correo:</td>";
    print "          <td><input type=\"text\" name=\"Email\" value=\"$registro[correo]\"></td>";
    print "        </tr>";
    print "      </table>";
    print "";
    print "      <p>";
    print "        <input type=\"hidden\" name=\"id\" value=\"$id\">";
    print "        <input type=\"submit\" value=\"Update\">";
    print "        <input type=\"reset\" value=\"Reset form\">";
    print "      </p>";
    print "    </form>";
  }
}

pie();
?>
