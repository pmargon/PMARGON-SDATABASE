<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Import Data - Step 2");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        $header = fgetcsv($handle, 1000, ',');

        // Validate file header
        if ($header !== ["ID", "Nombre", "Apellido", "Email", "Telefono"]) {
            print "<p class=\"aviso\">Invalid file format. The columns must be: ID, Name, Last Name, Email, Phone.</p>";
        } else {
            $rowCount = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $rowCount++;

                // Validate that the row contains the expected columns
                $id       = $data[0] ?? null;
                $nombre   = $data[1] ?? null;
                $apellidos = $data[2] ?? null;
                $email    = $data[3] ?? null;
                $telefono = $data[4] ?? null;

                // Check if the row is empty
                if (empty($nombre) && empty($apellidos) && empty($email) && empty($telefono)) {
                    print "<p class=\"aviso\">Empty record on row " . ($rowCount + 1) . ". It will not be imported.</p>";
                    continue;
                }

                // Check for duplicates by nombre+apellidos or email
                $queryCheck = "SELECT COUNT(*) FROM personas
                               WHERE (nombre = :nombre AND apellidos = :apellidos)
                               OR correo = :email";

                $stmtCheck = $pdo->prepare($queryCheck);
                if (!$stmtCheck->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":email" => $email])) {
                    print "<p class=\"aviso\">Error checking duplicates for row " . ($rowCount + 1) . ".</p>";
                    continue;
                }

                if ($stmtCheck->fetchColumn() > 0) {
                    print "<p class=\"aviso\">Duplicate record detected on row " . ($rowCount + 1) . ". It will not be imported.</p>";
                    continue;
                }

                // Insert the record
                $queryInsert = "INSERT INTO personas (id, nombre, apellidos, correo, telefono)
                                VALUES (:id, :nombre, :apellidos, :email, :telefono)";

                $stmtInsert = $pdo->prepare($queryInsert);
                if (!$stmtInsert) {
                    print "<p class=\"aviso\">Error preparing the insert query on row " . ($rowCount + 1) . ".</p>";
                } elseif (!$stmtInsert->execute([
                    ':id'       => $id,
                    ':nombre'   => $nombre,
                    ':apellidos'=> $apellidos,
                    ':email'    => $email,
                    ':telefono' => $telefono,
                ])) {
                    print "<p class=\"aviso\">Error executing the insert query for row " . ($rowCount + 1) . ".</p>";
                } else {
                    print "<p>Row " . ($rowCount + 1) . " imported successfully.</p>";
                }
            }
        }

        fclose($handle);
    } else {
        print "<p class=\"aviso\">Error opening the file.</p>";
    }
} else {
    print "<p class=\"aviso\">No file was uploaded.</p>";
}

pie();
?>
