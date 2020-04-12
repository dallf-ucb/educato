<?php
/**
 * Controller clase padre de todos los controladores
 *
 * Encargado de definir las operaciones básicas disponibles para cada controlador
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
  * Controller clase padre de todos los controladores
  *
  * Encargado de definir las operaciones básicas disponibles para cada controlador
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/app/controller.php
  */
class Controller
{
    public $layout = "default";
    public $model = null;
    /**
     * Dibuja una vista como respuesta a una llamada a un controlador
     *
     * @param array $vars Conjunto de varibles que se van a pasar a la vista
     *
     * @return void
     */
    public function render($vars = array())
    {
        extract($vars);
        ob_start();
        $filename = debug_backtrace()[1]["function"];
        $folder = lcfirst(get_class($this));
        $view = ROOT . "views/" . $folder . "/" . $filename . ".php";
        if (file_exists($view)) {
            include $view;
        } else {
            echo "Error: view not found.";
        }
        $body = ob_get_clean();

        if ($this->layout == false) {
            echo $body;
        } else {
            ob_start();
            include ROOT . "views/layouts/" . $this->layout . ".php";
            $tmpl = ob_get_clean();
            echo str_replace("[BODY]", $body, $tmpl);
        }
    }

    /**
     * Carga un modelo diferente al predeterminado para ser usado en el controlador
     * los modelos se guardan en archivos que empiezan con la letra m al igual que
     * la clase del modelo empieza con la letra M mayúscula no es necesario incluir
     * esta letra al llamar a este método
     *
     * @param string $name Nombre del modelo a cargar sin incluir la M inicial
     *
     * @return void
     */
    public function loadm($name)
    {
        $model = ROOT . "models/m" . $name . ".php";
        if (file_exists($model)) {
            include $model;
            $model = "M" . $name;
            $this->$name = new $model();
        }
    }
}
