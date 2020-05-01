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
     * Constructuro del controlador base carga el modelo del menu
     *
     * @return void
     */
    public function __construct()
    {
        $this->loadm("menu");
    }

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
        $menu = $this->getMenu();

        if ($this->layout == false) {
            echo $body;
        } else {
            ob_start();
            include ROOT . "views/layouts/" . $this->layout . ".php";
            $tmpl = ob_get_clean();
            $tmpl = str_replace("[MENU]", $menu, $tmpl);
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

    /**
     * Usa el usuario actual si esta logueado para construir el menu correspondiente
     *
     * @return string retorna el menu en HTML correspondiente al usuario actual
     */
    public function getMenu()
    {
        if (logged()) {
            $id_rol = getSes("id_rol");
            $options = $this->menu->fetchWhere(
                "id_rol = ? and id_menu is null", array($id_rol)
            );
            $menu = "";
            foreach ($options as $option) {
                switch ($option->tipo) {
                case "dropdown":
                    $menu .= $this->createDropdown($option->id, $option->texto);
                    break;
                case "link":
                    $menu .= $this->createLink($option->href, $option->texto);
                    break;
                }
            }
            return $menu;
        } else {
            return "";
        }
    }
    
    /**
     * Crea un anchor con URL y texto 
     *
     * @param string $href Url donde navegar
     * @param string $text Texto a mostrar para el link
     *
     * @return string HTML anchor con la Url y texto donde navegar 
     */
    public function createLink($href, $text)
    {
        return "<a class='nav-link' href='" . baseUrl() . "$href'>$text</a>
        ";
    }

    /**
     * Crea una division para submenus tipo dropdown 
     *
     * @return string HTML de un div para menus dropdown
     */
    public function createDivider()
    {
        return "<div class='dropdown-divider'></div>";
    }

    /**
     * Dibuja una vista como respuesta a una llamada a un controlador
     *
     * @param string $id   Id del menu padre 
     * @param string $text Texto a mostrar para el dropdown 
     *
     * @return string HTML del dropdown con sus links correspondientes 
     */
    public function createDropdown($id, $text)
    {
        $id_rol = getSes("id_rol");
        $options = $this->menu->fetchWhere(
            "id_rol = ? and id_menu = ?", array($id_rol, $id)
        );
        $body = "";
        foreach ($options as $option) {
            switch ($option->tipo) {
            case "divider":
                $body .= $this->createDivider();
                break;
            case "link":
                $body .= $this->createLink($option->href, $option->texto);
                break;
            }
        }
        $html_id = $text . "_" . $id;
        $dropdown = "
        <li class='nav-item dropdown'>
            <a class='nav-link dropdown-toggle' href='#' id='$html_id' 
                role='button' data-toggle='dropdown'
                aria-haspopup='true' aria-expanded='false'>
                $text
            </a>
            <div class='dropdown-menu' aria-labelledby='$html_id'>
                [BODY]
            </div>
        </li>";
        return str_replace("[BODY]", $body, $dropdown);
    }
}