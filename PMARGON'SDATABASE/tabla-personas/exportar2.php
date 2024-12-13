<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['data_type'])) {
    $data_type = $_POST['data_type'];
    
    // Realizamos la consulta dependiendo de la opción seleccionada
    if ($data_type == 'all') {
        $query = "SELECT * FROM personas";  
    } elseif ($data_type == 'specific' && isset($_POST['users'])) {
        // Si es "specific", obtenemos los usuarios seleccionados
        $selected_users = $_POST['users'];
        $placeholders = str_repeat('?,', count($selected_users) - 1) . '?';
        $query = "SELECT * FROM personas WHERE id IN ($placeholders)";
    }

    // Ejecutar la consulta
    $stmt = $pdo->prepare($query);
    if (isset($selected_users)) {
        $stmt->execute($selected_users);
    } else {
        $stmt->execute();
    }
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificamos si tenemos resultados
    if (count($rows) > 0) {
        // Definir el nombre del archivo CSV
        $filename = "exported_data_" . date("Y-m-d_H-i-s") . ".csv";
        
        // Establecemos las cabeceras para la descarga del archivo CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // Abrir el archivo en memoria para escribir
        $output = fopen('php://output', 'w');
        
        // Renombrar las columnas para que coincidan con las del archivo de importación
        $customHeaders = ['ID', 'Nombre', 'Apellido', 'Email', 'Telefono'];
        fputcsv($output, $customHeaders);

        // Escribir los datos de las filas con los nuevos nombres
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['id'], 
                $row['nombre'], 
                $row['apellidos'], 
                $row['correo'], 
                $row['telefono']
            ]);
        }

        fclose($output);  // Cerrar el archivo
        exit;  // Detener el script después de la descarga
    } else {
        print "<p class=\"aviso\">No data available to export.</p>";
    }
} else {
    print "<p class=\"aviso\">No data type selected.</p>";
}
?>
