<?php
/**
 * Dispacher clase que interpreta las URLs y llama a los controladores
 *
 * Encargado de valdiar las rutas de las peticiones y hacer el ruteo, autorización,
 * y navegar al correspondiente controlador y vista
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/dispacher.php
 */

/**
 * Dispacher clase que interpreta las URLs y llama a los controladores
 *
 * Encargado de valdiar las rutas de las peticiones y hacer el ruteo, autorización,
 * y navegar al correspondiente controlador y vista
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/dispacher.php
 */
class Dispatcher
{
    private $_request;
    /**
     * Parseado de las URLs, cargado de los controladores, modelos y navegación
     *
     * @return void
     */
    public function dispatch()
    {
        $this->_request = new stdClass;
        $this->_parse();
        $controller = $this->load();
        if (method_exists($controller, $this->_request->view)
            && is_callable(array($controller, $this->_request->view))
            && !($controller instanceof IAuth)
        ) {
            call_user_func_array(
                [$controller, $this->_request->view],
                $this->_request->params
            );
        } elseif ($controller instanceof IAuth) {
            if (logged()) {
                call_user_func_array(
                    [$controller, $this->_request->view],
                    $this->_request->params
                );
            } else {
                if ($this->_request->type == "web") {
                    setSes(
                        "url",
                        $this->_request->controller . "/" . $this->_request->view
                    );
                    redirect("auth/login");
                } else {
                    echo json_encode(array("Error" => "login"));
                }
            }
        } else {
            if ($this->_request->type == "web") {
                redirect("home/error");
            } else {
                echo json_encode(
                    array("Error" => "no_api", "Api" => $this->_request->view)
                );
            }
        }
    }

    /**
     * Cargado de un controlador validando su existencia
     *
     * @return Controller Nueva instancia de un clase controlador específica
     */
    public function load()
    {
        $name = $this->_request->controller;
        if ($name == "api") {
            $name = explode("?", $this->_request->view)[0];
            $file = ROOT . "api/a" . $name . ".php";
            if (!file_exists($file)) {
                $this->_request->controller = "base";
                $this->_request->params = array($name);
                $this->_request->view = "error";
                $cname = $name = "base";
                $file = ROOT . "api/" . $name . ".php";
            } else {
                $view = strtolower($_SERVER["REQUEST_METHOD"]);
                $this->_request->view = $view;
                $cname = "A".$name;
                include $file;
            }
            $this->_request->type = "api";
        } else {
            $file = ROOT . "controllers/" . $name . ".php";
            if (!file_exists($file)) {
                $this->_request->controller = "home";
                $this->_request->params = array($this->_request->view);
                $this->_request->view = "error";
                $name = "home";
                $file = ROOT . "controllers/" . $name . ".php";
            }
            include $file;
            $this->_request->type = "web";
            $cname = ucfirst($name);
        }
        $controller = new $cname();
        $model = ROOT . "models/m" . $name . ".php";
        if (file_exists($model)) {
            include $model;
            $model = "M" . $name;
            $controller->model = new $model();
        }

        return $controller;
    }

    /**
     * Parseado de las URLs, encontrando los parámetros
     * y verificando la URL solicitada
     *
     * @return void
     */
    private function _parse()
    {
        $url = $_SERVER["REQUEST_URI"];
        $url = trim(str_replace("/", " ", $url));
        if (empty($url)) {
            $this->_request->controller = "home";
            $this->_request->view = "index";
            $this->_request->params = [];
        } else {
            $explode_url = explode(" ", $url);
            if (count($explode_url) >= 1) {
                $this->_request->controller = $explode_url[0];
                if (count($explode_url) >= 2) {
                    $this->_request->view = $explode_url[1];
                } else {
                    $this->_request->view = "index";
                }
                $this->_request->params = array_slice($explode_url, 2);
            } else {
                $this->_request->controller = "home";
                $this->_request->view = "error";
                $this->_request->params = [];
            }
        }
    }
}
