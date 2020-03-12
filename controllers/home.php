<?php 
class home extends controller {
    function index() {
        $datos = $this->model->fetchWhere();
        print_r($datos);
        $this->render();
    }

    function error() {
        
    }
}
?> 