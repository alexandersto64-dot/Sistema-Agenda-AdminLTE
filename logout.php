<?php

session_start();

// limpiar completamente la sesión
$_SESSION = [];

session_unset();
session_destroy();

header("Location: index.php");
exit;