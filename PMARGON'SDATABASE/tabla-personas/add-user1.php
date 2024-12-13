<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Add User - Step 1");

?>

<form action="add-user2.php" method="post">
  <p>Enter the details of the new user:</p>
  <p>
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
  </p>
  <p>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
  </p>
  <p>
    <input type="submit" value="Add User">
    <input type="reset" value="Clear Fields">
  </p>
</form>


<?php

pie();
?>
