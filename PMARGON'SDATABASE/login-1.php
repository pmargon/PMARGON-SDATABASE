<?php

require_once "comunes/biblioteca.php";

session_name("sesiondb");
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:tabla-personas/personas.php");
    exit;
}

cabecera("Login 1");

$aviso = recoge("aviso");

if ($aviso != "") {
    print "    <p class=\"aviso\">$aviso</p>\n";
    print "\n";
}
?>
    <form action="login-2.php" method="post">
      <p>Enter your username and password:</p>

      <table>
        <tr>
          <td>User:</td>
          <td><input type="text" name="usuario"  autofocus/></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="password" name="password" /></td>
        </tr>
      </table>

      <p>
        <input type="submit" value="Sign in">
        <input type="reset" value="Clear credentials">
      </p>
    </form>
<php 
pie();
?>
