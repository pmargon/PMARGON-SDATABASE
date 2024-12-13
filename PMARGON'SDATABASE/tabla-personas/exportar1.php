<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Export Data - Step 1");

?>

<form action="exportar2.php" method="post">
  <p>Select the type of data to export:</p>
  <select name="data_type" required id="data_type" onchange="toggleUserSelection()">
    <option value="all">All Data</option>
    <option value="specific">Specific Data</option>
  </select>
  
  <div id="specific_data" style="display: none;">
    <p>Select users to export:</p>
    <?php
    // Mostrar la lista de usuarios
    $query = "SELECT id, nombre, apellidos FROM personas";  
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user):
    ?>
      <input type="checkbox" name="users[]" value="<?= $user['id'] ?>" id="user_<?= $user['id'] ?>">
      <label for="user_<?= $user['id'] ?>"><?= $user['nombre'] . ' ' . $user['apellidos'] ?></label><br>
    <?php endforeach; ?>
  </div>

  <p>
    <input type="submit" value="Export">
    <input type="reset" value="Clear Selection">
  </p>
</form>

<script>
  function toggleUserSelection() {
    var dataType = document.getElementById('data_type').value;
    var specificDataDiv = document.getElementById('specific_data');
    if (dataType === 'specific') {
      specificDataDiv.style.display = 'block';
    } else {
      specificDataDiv.style.display = 'none';
    }
  }
</script>

<?php

pie();
?>
