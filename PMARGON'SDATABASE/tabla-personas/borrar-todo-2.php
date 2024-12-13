<?php

require_once "../comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (!isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$borrar = recoge("borrar");

if ($borrar != "Yes") {
    header("Location:personas.php");
    exit;
}

$pdo = conectaDb();

cabecera("Personas - Borrar todo 2");

borraTodo();

pie();
