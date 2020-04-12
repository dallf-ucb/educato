<?php
/**
 * Clase controlador para web API de usuario
 *
 * Encargado de definir las operaciones de web API de usuarios
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/controller.php
 */

 /**
  * Clase controlador para web API de usuario
  *
  * Encargado de definir las operaciones de web API de usuarios
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/app/controller.php
  */
class Ausuario extends Base implements IAuth
{
    /**
     * Función encargada de manejar la peticiones GET
     *
     * @return void
     */
    public function get()
    {
        $result = array();
        $result["Error"] = "";
        if (isset($_GET["id"])) {
            $result["Data"] = $this->model->fetchOneWhere(
                " id = ? ",
                array($_GET["id"])
            );
        } elseif (isset($_GET["pageNumber"])) {
            $start = $_GET["startDate"];
            $end = $_GET["endDate"];
            $nombre = "%" . $_GET["nombre"] . "%";
            $rol = "%" . $_GET["rol"] . "%";
            $page = $_GET["pageNumber"];
            $size = $_GET["pageSize"];
            $sort = $_GET["sortBy"];
            $dir = $_GET["sortDirection"];
            $conds = "created_at between ? and ? and nombre like ? and rol like ?";
            $vals = array($start, $end, $nombre, $rol);
            $result["Total"] = $this->model->countAllWhere($conds, $vals);
            $result["Data"] = $this->model->fetchPagedWhere(
                $conds,
                $vals,
                $page,
                $size,
                $sort,
                $dir
            );
        } else {
            $result["Error"] = "no_params";
        }
        $this->render($result);
    }

    /**
     * Función encargada de actualizar los datos de un usuarios existente.
     *
     * @return void
     */
    public function put()
    {
        $result = array();
        $usuario = new Musuario();
        $_PUT = array();
        parse_str(file_get_contents('php://input'), $_PUT);
        $usuario->id = $_PUT["id"];
        $usuario->nombre = $_PUT["nombre"];
        $usuario->rol = $_PUT["rol"];
        $result["Error"] = "";
        if (isset($_PUT["clave"]) && !empty($_PUT["clave"])) {
            $usuario->clave = sha1($_PUT["clave"]);
            if (!$usuario->save()) {
                $result["Error"] = "db";
            }
        } else {
            if (!$usuario->updateNoPwd(
                array($usuario->nombre, $usuario->rol, $usuario->id)
            )
            ) {
                $result["Error"] = "db";
            }
        }
        $this->render($result);
    }

    /**
     * Función encargada de insertar los datos de un usuarios nuevo.
     *
     * @return void
     */
    public function post()
    {
        $result = array();
        $usuario = new Musuario();
        $usuario->id = $_REQUEST["id"];
        $usuario->nombre = $_REQUEST["nombre"];
        $usuario->rol = $_REQUEST["rol"];
        $usuario->clave = sha1($_REQUEST["clave"]);
        $result["Error"] = "";
        if (!$usuario->save()) {
            $result["Error"] = "db";
        }
        $this->render($result);
    }

    /**
     * Función encargada de borrar los datos de un usuarios existente.
     *
     * @return void
     */
    public function delete()
    {
        $result = array();
        $usuario = new Musuario();
        $usuario->id = $_REQUEST["id"];
        $result["Error"] = "";
        if (!$usuario->delete()) {
            $result["Error"] = "db";
        }
        $this->render($result);
    }
}
