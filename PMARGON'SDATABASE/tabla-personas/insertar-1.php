<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Añadir 1");

    // Mostramos el formulario
?>
<form action="insertar-2.php" method="post">
  <p>Enter the data for the new record:</p>

  <table>
    <tr>
      <td>Name:</td>
      <td><input type="text" name="nombre" autofocus></td>
    </tr>
    <tr>
      <td>Surname:</td>
      <td><input type="text" name="apellidos"></td>
    </tr>
    <tr>
      <td>Phone:</td>
      <td><input type="text" name="telefono"></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input type="text" name="correo"></td>
    </tr>
  </table>
  <p>
    <input type="submit" value="Add">
    <input type="reset" value="Reset the form">
  </p>
</form>
<?php

pie();
?>
