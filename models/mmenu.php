<?php
/**
 * Modelo menu contiene el mapeo de campos de la table menu de la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/models/mhome.php
 */

/**
 * Modelo menu contiene el mapeo de campos de la table menu de la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/models/mhome.php
 */
class Mmenu extends Model
{
    public $id;
    public $texto;
    public $tipo;
    public $href;
    public $html_id;
    public $id_rol;
    public $id_menu;
}