<?php
/**
 * Usuario Controller clase controladora para administrar los datos de los
 * usuarios y realizar las operaciones CRUD correspondientes
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/usuario.php
 */

/**
 * Usuario Controller clase controladora para administrar los datos de los
 * usuarios y realizar las operaciones CRUD correspondientes
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/usuario.php
 */
class Usuario extends Controller implements IAuth
{
    /**
     * Función que muestra la página de inicio del CRUD SPA de usuarios
     *
     * @return void
     */
    public function index()
    {
        $this->render();
    }
}
