<?php
/**
 * Archivo de base de datos base para configurar el acceso a la base de datos
 *
 * Singleton para configurar y conectar a la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/db.php
 */

 /**
  * Archivo de base de datos base para configurar el acceso a la base de datos
  *
  * Singleton para configurar y conectar a la base de datos
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/app/db.php
  */
class Db
{
    private static $_dbi = null;
    /**
     * Constructor padre privado para el patrón singleton
     *
     * @return void
     */
    private function __construct()
    {
    }
    /**
     * Getter para obtener la conexión a base de datos
     *
     * @return void
     */
    public static function getDb()
    {
        if (is_null(self::$_dbi)) {
            $DATABASE_HOST = 'localhost';
            $DATABASE_USER = 'root';
            $DATABASE_PASS = 'system32';
            $DATABASE_NAME = 'educato';
            try {
                self::$_dbi = new PDO(
                    'mysql:host=' . $DATABASE_HOST .
                    ';dbname=' . $DATABASE_NAME . ';charset=utf8',
                    $DATABASE_USER,
                    $DATABASE_PASS
                );
            } catch (PDOException $exception) {
                // Si hay un error detener la ejecución, mostrarlo y salir
                exit('No se puede conectar a la DB!');
            }
        }
        return self::$_dbi;
    }
}
