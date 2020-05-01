<?php
/**
 * Home Controller clase controladora para mostrar la página de inicio,
 * y mensajes de error en caso de problemas de navegación
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/home.php
 */

/**
 * Home Controller clase controladora para mostrar la página de inicio,
 * y mensajes de error en caso de problemas de navegación
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/home.php
 */
class Home extends Controller
{
    /**
     * Función que muestra la página de bienvenida
     *
     * @return void
     */
    public function index()
    {
        $this->render();
    }

    /**
     * Función para mostrar la página de error en casos que no se encuentre
     * un controlador o vista solicitada
     *
     * @return void
     */
    public function error()
    {
        $this->render();
    }
}