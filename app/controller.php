<?php
class controller
{
    var $vars = [];
    var $layout = "default";
    var $model = null;

    function set($data)
    {
        $this->vars = array_merge($this->vars, $data);
    }

    function render()
    {
        extract($this->vars);
        ob_start();
        $filename = debug_backtrace()[1]['function'];
        require(ROOT . "views/" . get_class($this) . '/' . $filename . '.php');

        $body = ob_get_clean();

        if ($this->layout == false)
        {
            echo $body;
        }
        else
        {
            ob_start();
            require(ROOT . "views/layouts/" . $this->layout . '.php');
            $tmpl = ob_get_clean();
            echo str_replace("[BODY]", $body, $tmpl);
        }
    }

    function loadm($name) {
        $model = ROOT . 'models/m_' . $name . '.php';
        if (file_exists($model)) {
            require($model);
            $model = 'm_' . $name;
            $controller->$name = new $model();
        }
    }
}
?>