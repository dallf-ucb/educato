<?php
/**
 * Archivo index.php punto de entrada
 *
 * Encargado de redireccionar todas las peticiones hechas a la app
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  Default
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/index.php
 */
error_reporting(E_ALL);
ini_set("display_errors", true);
ini_set("display_startup_errors", true);

define("ROOT", str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));
define("TZONE", "America/La_Paz");
date_default_timezone_set(TZONE);
require "app/core.php";
startSes();
$dispatch = new dispatcher();
$dispatch->dispatch();