<?php
/**
 * Modelo usuario contiene el mapeo de campos de la tabla usuario de la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/models/musuario.php
 */

/**
 * Modelo usuario contiene el mapeo de campos de la tabla usuario de la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/models/musuario.php
 */
class Musuario extends Model
{
    public $id;
    public $nombre;
    public $clave;
    public $rol;

    /**
     * Actualiza los datos de un usuario sin cambiar la clave
     *
     * @param array $params Arreglo con los datos del usuario a actualizar
     *
     * @return bool Bandera que indica si se actualizo correctamente o no la fila
     */
    public function updateNoPwd($params)
    {
        $query = "UPDATE usuario 
            SET nombre = ?, rol = ?, updated_at = now() 
            WHERE id = ? LIMIT 1";
        $st = $this->execute($query, $params);
        return ($st->rowCount() == 1 || $st !== false);
    }
}
