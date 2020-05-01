<?php
/**
 * Auth Controller clase controladora para manejo de la Autorización y
 * Auntenticación de usuarios
 *
 * Se encarga de presentar la vista login, validar el login de usuarios,
 * registrar usuarios y cerrar la sesión de un usuario
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/auth.php
 */

/**
 * Auth Controller clase controladora para manejo de la Autorización y
 * Auntenticación de usuarios
 *
 * Se encarga de presentar la vista login, validar el login de usuarios,
 * registrar usuarios y cerrar la sesión de un usuario
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/auth.php
 */
class Auth extends Controller
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
     * Función que muestra la vista de login de usuario
     *
     * @param string $msg Texto que indica si hubo un error al intentar loguearse
     *
     * @return void
     */
    public function login($msg = "")
    {
        $error = array();
        if (!empty($msg)) {
            $error["msg"]
                = "Hay un error con sus datos verifiquelos e intente de nuevo";
        }
        $this->render($error);
    }

    /**
     * Función que muestra la vista de login de usuario usando JSON
     *
     * @return void
     */
    public function jlogin()
    {
        $this->render();
    }

    /**
     * Función encargada de llamar al método de cerrar sesión y acerlo accesible
     * al usuario para que este pueda terminar su sesión
     *
     * @return void
     */
    public function logout()
    {
        logout();
    }

    /**
     * Función encargada de validar los datos de un usuario que intenta iniciar
     * sesión, lo redireccionará a la URL solicitada si se puede loguear bien o
     * le mostrará de nuevo el login con un mensaje de error en caso contrario
     *
     * @return void
     */
    public function validate()
    {
        $usuario = $this->usuario->fetchForValidation(
            $_POST["nombre"],
            sha1($_POST["clave"])
        );
        if ($usuario != null) {
            setSes("id", $usuario->id);
            setSes("username", $usuario->nombre);
            setSes("id_rol", $usuario->id_rol);
            setSes("token");
            $url = getSes("url");
            if (!empty($url)) {
                setSes("url", "");
                redirect($url);
            } else {
                redirect();
            }
        } else {
            redirect("auth/login/error");
        }
    }

    /**
     * Función encargada de registrar un usuario en el la base de datos
     *
     * @return void
     */
    public function register()
    {
    }
}