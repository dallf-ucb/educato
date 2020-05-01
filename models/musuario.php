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
    public $id_rol;
    public $rol;

    /**
     * Obtiene la instancia de usuario a validar obteniendolo por nombre y clave
     *
     * @param string $user Nombre del usuario a validar 
     * @param string $pwd  Clave del usuario a validar encriptado usando SHA1
     *
     * @return Musuario una instancia del modelo usuario validado 
     *                  o null si no se encuentra 
     */
    public function fetchForValidation($user, $pwd)
    {
        $sql = "SELECT id, u.nombre, clave, id_rol, r.nombre as rol  
            FROM usuario u join ROL r on 
                u.id_rol = r.id
            WHERE nombre = ? and clave = ? LIMIT 1";
        $st = $this->execute($sql, array($user, $pwd));
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $st->fetch();
        if ($row) {
            $instance = new Musuario();
            $instance->id = $row["id"];
            $instance->nombre = $row["nombre"];
            $instance->clave = $row["clave"];
            $instance->id_rol = $row["id_rol"];
            $instance->rol = $row["rol"];
            return $instance;
        } else {
            return null;
        }
        return $results;
    }

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