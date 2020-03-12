<?php
    class controller
    {
        var $vars = [];
        var $layout = "";
        var $body = "";
        var $model = null;

        function set($d)
        {
            $this->vars = array_merge($this->vars, $d);
        }

        function replace($buffer)
        {
            return (str_replace("[BODY]", $this->body, $buffer));
        }

        function render()
        {
            extract($this->vars);
            ob_start();
            $filename = debug_backtrace()[1]['function'];
            require(ROOT . "views/" . get_class($this) . '/' . $filename . '.php');
            //echo ROOT . "views/" . get_class($this) . '/' . $filename . '.php';

            $this->body = ob_get_clean();

            if ($this->layout == false)
            {
                echo $this->body;
            }
            else
            {
                ob_start("replace");
                require(ROOT . "views/layouts/" . $this->layout . '.php');
                echo ob_get_clean();
            }
        }
    }
?>