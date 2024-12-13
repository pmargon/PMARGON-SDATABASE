<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Import Data - Step 1");

?>
<form action="importar2.php" method="post" enctype="multipart/form-data">
  <p>Select a CSV file to import data:</p>
  <input type="file" name="file" accept=".csv" required>
  <p>
    <input type="submit" value="Import">
    <input type="reset" value="Clear Selection">
  </p>
</form>
<?php

pie();
?>
