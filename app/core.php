<?php
/**
 * Archivo PHP destinado a cargar las clases y funcionalidad base del Framework MVC
 *
 * Encargado de cargar el despachador de request, la base de datos,
 * el modelo y controlador base, y el manejador de sesiones
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/core.php
 */
require "dispatcher.php";
require "db.php";
require "model.php";
require "controller.php";
require "api/base.php";
require "iauth.php";
require "session.php";
/**
 * Encuentra la URL base de la app para usarla en llamadas y recarga de archivos web
 *
 * @return string La URL base de la app
 */
function baseUrl()
{
    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS'])
            && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $base_url = sprintf("%s://%s/", $http, $hostname);
    } else {
        $base_url = 'http://localhost/';
    }

    return $base_url;
}
/**
 * Redirecciona a otra URL cambiando el header de la petición
 *
 * @param string $path URL a la cual se va a direccionar el flujo del request
 *
 * @return void
 */
function redirect($path = "")
{
    header("Location:" . baseUrl() . $path);
    exit;
}
