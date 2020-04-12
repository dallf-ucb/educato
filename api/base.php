<?php
/**
 * Clase base de todos los controladores para web API
 *
 * Encargado de definir las operaciones básicas disponibles para cada controlador
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/api/base.php
 */

 /**
  * Clase base de todos los controladores para web API
  *
  * Encargado de definir las operaciones básicas disponibles para cada controlador
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/api/base.php
  */
class Base
{
    public $model = null;
    /**
     * Imprime la salida JSON de un método controlador de Web API
     *
     * @param object $output Objeto a codificar usando JSON
     *
     * @return void
     */
    public function render($output)
    {
        echo json_encode($output, JSON_NUMERIC_CHECK);
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

    /**
     * Imprime la salida JSON de error cuando un controlador de Web API no existe
     *
     * @param string $controller Controlador que produjo el error
     *
     * @return void
     */
    public function error($controller)
    {
        echo json_encode(
            array("Error" => "no_controller" , "Controller" => $controller)
        );
    }
}
