<?php
/**
 * Clase autentificación para la web API
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/api/auth.php
 */

 /**
  * Clase autentificación para la web API
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/api/auth.php
  */
class Aauth extends Base
{
    /**
     * Constructor encargado de crear el controlador, realiza la carga del modelo
     * de usuario para realizar las demás operaciones usando el mismo
     *
     * @return void
     */
    public function __construct()
    {
        $this->loadm("usuario");
    }
    
    /**
     * Login function for JSON client which handles post data
     *
     * @return void
     */
    public function post()
    {
        $usuario = $this->usuario->fetchOneWhere(
            " nombre = ? and clave = ? ",
            array($_POST["nombre"],
            sha1($_POST["clave"]))
        );
        if ($usuario != null) {
            setSes("username", $usuario->nombre);
            setSes("role", $usuario->rol);
            setSes("token");
            echo json_encode(
                array("Error" => "")
            );
        } else {
            echo json_encode(
                array("Error" => "login" , "Data" => "Invalid Credentials")
            );
        }
    }
}
