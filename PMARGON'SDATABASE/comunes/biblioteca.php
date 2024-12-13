<?php

// Carga Biblioteca específica de la base de datos utilizada

function recoge($key, $type = "", $default = null, $allowed = null)
{
    if (!is_string($key) && !is_int($key) || $key == "") {
        trigger_error("Function recoge(): Argument #1 (\$key) must be a non-empty string or an integer", E_USER_ERROR);
    } elseif ($type !== "" && $type !== []) {
        trigger_error("Function recoge(): Argument #2 (\$type) is optional, but if provided, it must be an empty array or an empty string", E_USER_ERROR);
    } elseif (isset($default) && !is_string($default)) {
        trigger_error("Function recoge(): Argument #3 (\$default) is optional, but if provided, it must be a string", E_USER_ERROR);
    } elseif (isset($allowed) && !is_array($allowed)) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (is_array($allowed) && array_filter($allowed, function ($value) { return !is_string($value); })) {
        trigger_error("Function recoge(): Argument #4 (\$allowed) is optional, but if provided, it must be an array of strings", E_USER_ERROR);
    } elseif (!isset($default) && isset($allowed) && !in_array("", $allowed)) {
        trigger_error("Function recoge(): If argument #3 (\$default) is not set and argument #4 (\$allowed) is set, the empty string must be included in the \$allowed array", E_USER_ERROR);
    } elseif (isset($default, $allowed) && !in_array($default, $allowed)) {
        trigger_error("Function recoge(): If arguments #3 (\$default) and #4 (\$allowed) are set, the \$default string must be included in the \$allowed array", E_USER_ERROR);
    }

    if ($type == "") {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = "";
        } else {
            $tmp = trim(htmlspecialchars($_REQUEST[$key]));
        }
        if ($tmp == "" && !isset($allowed) || isset($allowed) && !in_array($tmp, $allowed)) {
            $tmp = $default ?? "";
        }
    } else {
        if (!isset($_REQUEST[$key]) || (is_array($_REQUEST[$key]) != is_array($type))) {
            $tmp = [];
        } else {
            $tmp = $_REQUEST[$key];
            array_walk_recursive($tmp, function (&$value) use ($default, $allowed) {
                $value = trim(htmlspecialchars($value));
                if ($value == "" && !isset($allowed) || isset($allowed) && !in_array($value, $allowed)) {
                    $value = $default ?? "";
                }
            });
        }
    }
    return $tmp;
}
/* 
Esta función pinta la parte superior de las páginas web
SI LA SESIÓN ESTÁ INICIADA: Saca el menú de las funciones que se pueden hacer en la base de datos + DESCONECTARSE
SI LA SESIÓN NO ESTÁ INICIADA: Saca exclusivamente el menu CONECTARSE 
*/
function cabecera()
{
    print "<!DOCTYPE html>\n";
    print "<html lang=\"es\">\n";
    print "<head>\n";
    print "  <meta charset=\"utf-8\">\n";
    print "  <title>\n";
    print "  PMARGON'S DATABASE\n";   
    print "  </title>\n";
    print "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
    print "  <link rel=\"stylesheet\" href=\"mclibre-php-proyectos.css\" title=\"Color\">\n";
    print "  <link href=\"https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap\" rel=\"stylesheet\">\n";  // Agregado para la fuente Poppins
    print "  <style>\n";
    print "    body {\n";
    print "      margin: 0;\n";
    print "      padding: 0;\n";
    print "      background: url('fondo.jpg') no-repeat center center fixed;\n"; 
    print "      background-size: cover; /* Asegura que cubra toda la pantalla */\n";
    print "      font-family: 'Poppins', sans-serif;\n"; 
    print "      color: white;\n"; 
    print "    }\n";
    print "    header {\n";
    print "      background: rgba(0, 0, 0, 0.6); /* Fondo oscuro para el encabezado */\n";
    print "      padding: 20px;\n";
    print "      text-align: center;\n";
    print "      position: relative;\n";
    print "    }\n";
    print "    header h1 {\n";
    print "      font-size: 4rem;\n"; 
    print "      text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7), 0 0 25px yellow, 0 0 5px orange;\n"; 
    print "      color: white;\n"; 
    print "      letter-spacing: 2px;\n"; 
    print "      font-weight: bold;\n"; 
    print "      text-transform: uppercase;\n"; 
    print "      animation: glow 1.5s ease-in-out infinite alternate, slide 3s ease-out 1 forwards;\n"; 
    print "      display: inline-block;\n"; 
    print "    }\n";
    
    print "    @keyframes glow {\n";
    print "      0% {\n";
    print "        text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7), 0 0 10px yellow, 0 0 20px orange;\n";
    print "      }\n";
    print "      100% {\n";
    print "        text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7), 0 0 25px yellow, 0 0 5px orange;\n";
    print "      }\n";
    print "    }\n";
    
    
    print "    header h1:hover {\n";
    print "      animation: none;\n"; 
    print "      color: yellow;\n"; 
    print "      text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.7), 0 0 25px red, 0 0 15px red;\n"; 
    print "      transform: scale(1.1);\n"; 
    print "    }\n";
    
    print "    nav ul {\n";
    print "      list-style: none;\n";
    print "      padding: 0;\n";
    print "    }\n";
    print "    nav ul li a {\n";
    print "      color: white;\n";
    print "      text-decoration: none;\n";
    print "      font-weight: bold;\n";
    print "      padding: 10px 15px;\n";
    print "      background-color: rgba(0, 0, 0, 0.7);\n";  
    print "      border-radius: 5px;\n";
    print "      transition: background-color 0.3s ease, color 0.3s ease;\n";  
    print "    }\n";
    print "    nav ul li a:hover {\n";
    print "      background-color: yellow; /* Fondo amarillo en hover */\n";
    print "      color: black; /* Texto negro para contraste adecuado */\n";
    print "    }\n";
    
    print "    footer {\n";
    print "      background-color: rgba(0, 0, 0, 0.6);\n";
    print "      color: white;\n";
    print "      text-align: center;\n";
    print "      padding: 20px;\n";
    print "      position: relative;\n";
    print "    }\n";
    print "    footer p {\n";
    print "      margin: 10px 0;\n";
    print "    }\n";
    print "    footer a {\n";
    print "      color: #ddd;\n";
    print "      text-decoration: none;\n";
    print "    }\n";
    print "    footer a:hover {\n";
    print "      text-decoration: underline;\n";
    print "    }\n";
    print "  </style>\n";
    print "</head>\n";
    print "\n";
    print "<body>\n";
    print "  <header>\n";
    print "    <h1>PMARGON'S DATABASE</h1>\n";
    print "\n";
    print "    <nav>\n";
    print "      <ul>\n";
    if (!isset($_SESSION["conectado"])) {
        print "        <li><a href=\"login-1.php\">LOG IN</a></li>\n";
    } else {
        print "        <li><a href=\"insertar-1.php\">Add record</a></li>\n";
        print "        <li class='Listar'><a href=\"listar.php\">List</a></li>\n";
        print "        <li><a href=\"borrar-1.php\">Delete</a></li>\n";
        print "        <li><a href=\"buscar-1.php\">Search</a></li>\n";
        print "        <li><a href=\"modificar-1.php\">Modify</a></li>\n";
        print "        <li><a href=\"borrar-todo-1.php\">Delete all</a></li>\n";
        print "        <li><a href=\"../logout.php\">Logout</a></li>\n";
        print "        <li><a href=\"importar1.php\">Import Data</a></li>\n";
        print "        <li><a href=\"exportar1.php\">export Data</a></li>\n";
        print "        <li><a href=\"add-user1.php\">Add a new user</a></li>\n";
    }
    print "      </ul>\n";
    print "    </nav>\n";
    print "  </header>\n";
    print "\n";
    print "  <main>\n";
}

function pie()
{
    print "  </main>\n";
    print "\n";
    print "  <footer>\n";
    print "    <p class=\"ultmod\">\n";
    print "      Last modification of this page:\n";
    print "      <time datetime=\"2024-02-20\">February 20, 2024</time>\n";
    print "    </p>\n";
    print "\n";
    print "    <p class=\"licencia\">\n";
    print "      This program is part of the course <strong><a href=\"https://educacionadistancia.juntadeandalucia.es/centros/sevilla/course/view.php?id=2056\">ASIR'S PHP Web Programming.</a><br>\n";

    print "    </p>\n";
    print "  </footer>\n";
    print "</body>\n";
    print "</html>\n";
    
}

// Funciones BASES DE DATOS
function conectaDb()
{
    

    try {
        $tmp = new PDO("mysql:host=localhost;dbname=db_w3_pmg;charset=utf8mb4", "root", "root");
       return $tmp;
    }  catch (PDOException $e) {
        print "    <p class=\"aviso\">Error: No puede conectarse con la base de datos. {$e->getMessage()}</p>\n";
    } 

}

// MYSQL: Borrado y creación de base de datos y tablas

function borraTodo()
{
    global $pdo;

    print "    <p>Database Management System: MySQL</p>\n";
    print "\n";

    // Solo eliminar los datos de la tabla 'personas', no la tabla en sí
    $consulta = "DELETE FROM personas";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error deleting records from the 'personas' table. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Records deleted successfully from the 'personas' table.</p>\n";
    }
    print "\n";

    // reiniciar el contador de autoincremento
    $consulta = "ALTER TABLE personas AUTO_INCREMENT = 1";

    if (!$pdo->query($consulta)) {
        print "    <p class=\"aviso\">Error resetting AUTO_INCREMENT for the 'personas' table. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>AUTO_INCREMENT reset successfully for the 'personas' table.</p>\n";
    }
}


function encripta($cadena)
{
    

    return hash("sha256", $cadena);
}
?> 