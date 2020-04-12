<?php
/**
 * Home Controller clase controladora para mostrar la página de inicio,
 * y mensajes de error en caso de problemas de navegación
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/home.php
 */

/**
 * Home Controller clase controladora para mostrar la página de inicio,
 * y mensajes de error en caso de problemas de navegación
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/controllers/home.php
 */
class Home extends Controller
{
    /**
     * Función que muestra la página de bienvenida
     *
     * @return void
     */
    public function index()
    {
        $this->model->truncate();
        $model = new Mhome();
        $model->sitio = "Educato UCB";
        $model->tema = "default";
        $model->url = "educato.org";
        $model->copyright = "UCB \"San Pablo\" Tarija";
        $model->save();//Insert
        $model = new Mhome();
        $model->sitio = "Chaguaya";
        $model->tema = "chaguaya";
        $model->url = "chaugaya.org";
        $model->copyright = "Santuario de Chaguaya";
        $model->save();//Insert
        $model = new Mhome();
        $model->sitio = "Test 1";
        $model->tema = "Test 1";
        $model->url = "Test 1";
        $model->copyright = "Test 1";
        $model->save();//Insert
        $model = new Mhome();
        $model->sitio = "Test 2";
        $model->tema = "Test 2";
        $model->url = "Test 2";
        $model->copyright = "Test 2";
        $model->save();//Insert
        $id = $model->id;
        $model->id = 3;
        $model->sitio = "Test 3";
        $model->tema = "Test 3";
        $model->url = "Test 3";
        $model->copyright = "Test UPDATE";
        $model->save();//Update
        $model->id = 4;
        $model->delete();
        $this->model->deleteAllWhere(" id > 5 ");
        $this->model->count();
        $this->model->countAllWhere(" id > 0 ");
        $where = json_encode($this->model->fetchWhere(" id > 0 "));
        $paged = json_encode($this->model->fetchPagedWhere(" id > 0 "));
        $one = json_encode($this->model->fetchOneWhere(" id > 0 "));
        $this->render(
            array(
                "id" => $id,
                "where" => $where,
                "paged" => $paged,
                "one" => $one
            )
        );
    }

    /**
     * Función para mostrar la página de error en casos que no se encuentre
     * un controlador o vista solicitada
     *
     * @return void
     */
    public function error()
    {
        $this->render();
    }
}
