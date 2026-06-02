<?php

/* ================================================
   auth.Controller.php  — Agenda 2026
   
   NOTA: La redirección por rol se hace en
   Template.php, antes de incluir las páginas.
   Estas funciones son auxiliares por si se
   necesitan en otro contexto.
   ================================================ */

/**
 * Retorna true si hay sesión activa.
 */
function haySesion() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION["usuario"]);
}

/**
 * Retorna true si el usuario es Administrador.
 */
function esAdmin() {
    return (($_SESSION["rol"] ?? '') === "Administrador");
}

/**
 * Retorna true si el usuario es de rol Usuario normal.
 */
function esUsuario() {
    return (($_SESSION["rol"] ?? '') === "Usuario");
}
