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
        $id = $this->model->id;
        $model->id = 3;
        $model->sitio = 'Test 3';
        $model->tema = 'Test 3';
        $model->url = 'Test 3';
        $model->copyright = 'Test UPDATE';
        $model->save();//Update
        $model->id = 4;
        $model->delete();
        $this->model->deleteAllWhere(' id > 5 ');
        $this->model->count();
        $this->model->countAllWhere(' id > 0 ');
        $where = json_encode($this->model->fetchWhere(' id > 0 '));
        $paged = json_encode($this->model->fetchPagedWhere(' id > 0 '));
        $one = json_encode($this->model->fetchOneWhere(' id > 0 '));
        $this->set(array('id' => $id, 'where' => $where, 'paged' => $paged, 'one' => $one));
        $this->render();
    }

    function error() {    
    }
}
?> 