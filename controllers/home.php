<?php 
class home extends controller {
    function index() {
        $this->model->truncate();
        $model = new m_home();
        $model->sitio = 'Educato UCB';
        $model->tema = 'default';
        $model->url = 'educato.org';
        $model->copyright = 'UCB "San Pablo" Tarija';
        $model->save();//Insert
        $model = new m_home();
        $model->sitio = 'Chaguaya';
        $model->tema = 'chaguaya';
        $model->url = 'chaugaya.org';
        $model->copyright = 'Santuario de Chaguaya';
        $model->save();//Insert
        $model = new m_home();
        $model->sitio = 'Test 1';
        $model->tema = 'Test 1';
        $model->url = 'Test 1';
        $model->copyright = 'Test 1';
        $model->save();//Insert
        $model = new m_home();
        $model->sitio = 'Test 2';
        $model->tema = 'Test 2';
        $model->url = 'Test 2';
        $model->copyright = 'Test 2';
        $model->save();//Insert
        echo $this->model->id;
        echo "<br>";
        $model->id = 3;
        $model->sitio = 'Test 3';
        $model->tema = 'Test 3';
        $model->url = 'Test 3';
        $model->copyright = 'Test UPDATE';
        $model->save();//Update
        $model->id = 4;
        $model->delete();
        echo $this->model->deleteAllWhere(' id > 5 ');
        echo "<br>";
        echo $this->model->count();
        echo "<br>";
        echo $this->model->countAllWhere(' id > 0 ');
        echo "<br>";
        echo json_encode($this->model->fetchWhere(' id > 0 '));
        echo "<br>";
        echo json_encode($this->model->fetchPagedWhere(' id > 0 '));
        echo "<br>";
        echo json_encode($this->model->fetchOneWhere(' id > 0 '));
        echo "<br>";
        
        $this->render();
    }

    function error() {    
    }
}
?> 