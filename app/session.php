<?php
/**
 * Archivo con los método necesarios para hacer el manejo de las sesiones de PHP
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/model.php
 */

/**
 * Inicia una sesión de PHP
 *
 * @return void
 */
function startSes()
{
    session_start();
}

/**
 * Verifica si hay un usuario logueado y si no ha transcurrido más de 30 minutos
 * desde la última acción que realizo en la página, en caso que ya haya 
 * transcurrido ese tiempo se cierra la sesión y se pide que el usuario 
 * ingrese de nuevo
 *
 * @return bool Valor lógico indicando si un usuario esta logueado
 */
function logged()
{
    $timeout = 60 * 30; // In seconds, i.e. 30 minutes.
    $fingerprint = hash_hmac(
        "sha256",
        $_SERVER["HTTP_USER_AGENT"],
        hash("sha256", $_SERVER["REMOTE_ADDR"], true)
    );

    if ((isset($_SESSION["last_active"])
        && $_SESSION["last_active"]<(time()-$timeout))
        || (isset($_SESSION["fingerprint"])
        && $_SESSION["fingerprint"]!=$fingerprint)
    ) { // Try to protect against PHP Session Fixation / Hijacking
        logout();
    }

    session_regenerate_id();
    $_SESSION["last_active"] = time();
    $_SESSION["fingerprint"] = $fingerprint;

    return isset($_SESSION["username"]);
}

/**
 * Cierra la sesión actual y borra todos los valores guardados y redirecciona
 * a la página de login por si el usuario desea volver a ingresar
 *
 * @return void
 */
function logout()
{
    setcookie(session_name(), "", time()-3600, "/");
    $_SESSION = array();
    session_destroy();
    redirect("auth/login");
}

/**
 * Verifica si en la sesión actual el rol guardado corresponde a cierto rol que se
 * desea comprobar para ver que permisos tiene el usuario logueado actualmente
 *
 * @param string $role Cadena con el nombre del rol a verificar
 *
 * @return bool Valor lógico indicando si cumple o no con el rol a verificar
 */
function inRole($role)
{
    if (!logged()) {
        return false;
    }
    return isset($_SESSION["role"]) && $_SESSION["role"] == $role;
}

/**
 * Guarda un valor en un índice de la sesión
 *
 * @param string $key   Índice al cual se quiere establecer un valor en la sesión
 * @param string $value Valor a guardar en la sesión en el índice indicado
 *
 * @return void
 */
function setSes($key, $value = "")
{
    if (!empty($key)) {
        if ($key != "token") {
            $_SESSION[$key] = $value;
        } else {
            $fingerprint = hash_hmac("sha256", $_SERVER["HTTP_USER_AGENT"], hash("sha256", $_SERVER["REMOTE_ADDR"], true));
            $_SESSION["last_active"] = time();
            $_SESSION["fingerprint"] = $fingerprint;
        }
    }
}

/**
 * Inicia una sesión de PHP
 *
 * @param string $key Índice cuyó valor se pretende obtener de la sesión
 *
 * @return value Devuelve null o el valor almacenado en cierto índice de la sesión
 */
function getSes($key)
{
    if (isset($_SESSION[$key])) {
        return $_SESSION[$key];
    } else {
        return null;
    }
}
