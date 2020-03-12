<?php
class dispatcher
{
    private $request;

    public function dispatch()
    {
        $this->request = new stdClass;
        $this->parse();
        $controller = $this->load(); 
        if (method_exists($controller, $this->request->view) && is_callable(array($controller, $this->request->view)))
        {
            call_user_func_array([$controller, $this->request->view], $this->request->params);
        }
    }

    public function load()
    {
        $name = $this->request->controller;
        $file = ROOT . 'controllers/' . $name . '.php';
        if (!file_exists($file)) {
            $this->request->controller = "home";
            $this->request->view = "error";
            $this->request->params = [];
        }
        require($file);

        $model = ROOT . 'models/' . $name . '.php';
        if (file_exists($model)) {
            require($model);
        }

        $controller = new $name();
        return $controller;
    }

    private function parse()
    {
        $url = trim($_SERVER["REQUEST_URI"]); 

        if ($url == "/")
        {
            $this->request->controller = "home";
            $this->request->view = "index";
            $this->request->params = [];
        }
        else
        {
            $explode_url = explode('/', $url);
            $explode_url = array_slice($explode_url, 2);
            $this->request->controller = $explode_url[0];
            $this->request->view = $explode_url[1];
            $this->request->params = array_slice($explode_url, 2);
        }

    }
}
?>