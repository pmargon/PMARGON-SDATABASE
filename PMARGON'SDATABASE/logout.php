<?php

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

session_destroy();

header("Location:index.php");
