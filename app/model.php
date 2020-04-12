<?php
/**
 * Modelo base que contiene la funcionalidad general de acceso a la base de datos
 *
 * Clase que contiene todos los métodos necesarios para realizar
 * consultas, modificaciones, borrado e inserción de datos a la base de datos
 *
 * PHP version 7
 *
 * @category PHP_MVC
 * @package  App
 * @author   Daniel Llano <daniel.llano@outlook.com>
 * @license  licence.txt GNU GPLv3
 * @link     https://github.com/dallf-ucb/educato/app/model.php
 */

 /**
  * Modelo base que contiene la funcionalidad general de acceso a la base de datos
  *
  * Clase que contiene todos los métodos necesarios para realizar
  * consultas, modificaciones, borrado e inserción de datos a la base de datos
  *
  * PHP version 7
  *
  * @category PHP_MVC
  * @package  App
  * @author   Daniel Llano <daniel.llano@outlook.com>
  * @license  licence.txt GNU GPLv3
  * @link     https://github.com/dallf-ucb/educato/app/model.php
  */
class Model
{
    protected $db;
    private static $_columns = array();
    private static $_stmt = array();
    protected $pk_column = "id";
    protected $table = "_the_db_table_name_";
    /**
     * Creación de un nuevo modelo conectando a la base de datos
     * y obteniendo las columnas de la tabla desde la base de datos
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = db::getDb();
        $this->getFieldnames();
    }
    
    /**
     * Usando el comando DESCRIBE obtenie los campos de la table que
     * corresponde al nombre de un modelo y los devuelve como un arreglo
     *
     * @return array Arreglo con las columnas de la tabla
     */
    protected function getFieldnames()
    {
        $class = substr(get_called_class(), 1);
        $this->table = $class;
        if (!isset(self::$_columns[$class])) {
            $st = $this->execute("DESCRIBE " . $class);
            self::$_columns[$class] = $st->fetchAll(\PDO::FETCH_COLUMN);
        }
        return self::$_columns[$class];
    }
    
    /**
     * Contar las filas de la tabla cuyó nombre corresponde al modelo actual
     *
     * @return int Cantidad de filas en la tabla
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM " . $this->table;
        $st = $this->execute($sql);
        return (int)$st->fetchColumn(0);
    }
    
    /**
     * Contar las filas de la tabla cuyó nombre corresponde al modelo actual
     * y además que cumplen cierta condición sujeta a parámetros
     *
     * @param string $SQLfragment Cadena con la condición where y parámetros
     * @param array  $params      Arreglo opcional de valores para los parámetros
     *                            pasados en la condición where
     *
     * @return int Cantidad de filas en la tabla
     */
    public function countAllWhere($SQLfragment = "", $params = array())
    {
        $SQLfragment = $SQLfragment ? " WHERE " . $SQLfragment : $SQLfragment;
        $st = $this->execute(
            "SELECT COUNT(*) FROM " . $this->table. $SQLfragment,
            $params
        );
        return (int)$st->fetchColumn(0);
    }
    
    /**
     * Seleccionar las filas de la tabla cuyó nombre corresponde al modelo actual
     * y además que cumplen cierta condición sujeta a parámetros
     *
     * @param string $SQLfragment Cadena con la condición where y parámetros
     * @param array  $params      Arreglo opcional de valores para los parámetros
     *                            pasados en la condición where
     * @param bool   $limitOne    Bandera que indica si solo se va retornará una fila
     *
     * @return array Arreglo con uno o más objetos de la clase del modelo que hereda
     *               de este clase base y llamó al método
     */
    public function fetchWhere(
        $SQLfragment = "",
        $params = array(),
        $limitOne = false
    ) {
        $SQLfragment = $SQLfragment ? " WHERE " . $SQLfragment : $SQLfragment;
        $cols = implode(", ", self::$_columns[$this->table]);
        $sql = "SELECT " . $cols . " FROM " .
            $this->table. $SQLfragment . ($limitOne ? " LIMIT 1" : "");
        $st = $this->execute($sql, $params);
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        if ($limitOne) {
            if ($st->rowCount() == 1) {
                $row = $st->fetch();
                $model = "M". $this->table;
                $instance = new $model();
                foreach (self::$_columns[$this->table] as $key) {
                    $instance->$key = $row[$key];
                }
                return $instance;
            } else {
                return null;
            }
        }
        $results = [];
        while ($row = $st->fetch()) {
            $model = "M". $this->table;
            $instance = new $model();
            foreach (self::$_columns[$this->table] as $key) {
                $instance->$key = $row[$key];
            }
            $results[] = $instance;
        }
        return $results;
    }

    /**
     * Seleccionar una página de filas de la tabla cuyó nombre corresponde
     * al modelo actual y además que cumplen cierta condición sujeta a parámetros
     *
     * @param string $SQLfragment      Cadena con la condición where y parámetros
     * @param array  $params           Arreglo opcional de valores para los
     *                                 parámetros pasados en la condición where
     * @param int    $page             Número de página de datos a retornar
     * @param int    $records_per_page Cantidad de filas por página retornar
     * @param string $sort             Campo por el cual ordenar
     * @param string $dir              Dirección de ordenación ASC. DESC
     *
     * @return array Arreglo con uno o más objetos de la clase del modelo que hereda
     *               de este clase base y llamó al método
     */
    public function fetchPagedWhere(
        $SQLfragment = "",
        $params = array(),
        $page = 1,
        $records_per_page = 10,
        $sort = "id",
        $dir = "DESC"
    ) {
        $SQLfragment = $SQLfragment ? " WHERE " . $SQLfragment : $SQLfragment;
        $cols = implode(", ", self::$_columns[$this->table]);
        $offset = ($page - 1) * $records_per_page;
        $limit = $records_per_page;
        $sql = "SELECT " . $cols . " FROM " . $this->table . $SQLfragment .
            " ORDER BY " . $sort . " " . $dir . " LIMIT " . $offset . "," . $limit;
        $st = $this->execute($sql, $params);
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        $results = [];
        while ($row = $st->fetch()) {
            $model = "M". $this->table;
            $instance = new $model();
            foreach (self::$_columns[$this->table] as $key) {
                $instance->$key = $row[$key];
            }
            $results[] = $instance;
        }
        return $results;
    }
    
    /**
     * Seleccionar una sola fila de la tabla cuyó nombre corresponde al modelo
     * actual y además que cumplen cierta condición sujeta a parámetros
     *
     * @param string $SQLfragment Cadena con la condición where y parámetros
     * @param array  $params      Arreglo opcional de valores para los parámetros
     *                            pasados en la condición where
     *
     * @return array Arreglo con uno objeto de la clase del modelo que hereda
     *               de este clase base y llamó al método
     */
    public function fetchOneWhere($SQLfragment = "", $params = array())
    {
        return $this->fetchWhere($SQLfragment, $params, true);
    }
    
    /**
     * Seleccionar las filas de la tabla cuyó nombre corresponde al modelo actual
     * y además que cumplen cierta condición sujeta a parámetros
     *
     * @param string $id Llave primaria usada en la condición para borrar la fila
     *
     * @return bool Valor lógico indicando si se pudo borrar o no la fila
     */
    public function deleteById($id)
    {
        $st = $this->execute(
            "DELETE FROM " . $this->table. " WHERE " .
            $this->pk_column . " = ? LIMIT 1",
            array($id)
        );
        return ($st->rowCount() == 1);
    }
    
    /**
     * Borrar la instancia del modelo actual que hace la llamada al método
     *
     * @return bool Valor lógico que indica si se pudo borrar el objeto
     */
    public function delete()
    {
        return $this->deleteById($this->{$this->pk_column});
    }
    
    /**
     * Borrar todas las filas que cumplen cierta condición sujeta a parámetros
     *
     * @param string $where  Cadena con la condición where y parámetros
     * @param array  $params Arreglo opcional de valores para los parámetros
     *                       pasados en la condición where
     *
     * @return bool Valor lógico indicando si se pudieron borrar o no la filas
     */
    public function deleteAllWhere($where, $params = array())
    {
        $st = $this->execute(
            "DELETE FROM " . $this->table. " WHERE " . $where,
            $params
        );
        return ($st->rowCount() >= 1);
    }
    
    /**
     * Borra de manera obligatoria todas las filas de una tabla
     *
     * @return void
     */
    public function truncate()
    {
        $st = $this->execute("TRUNCATE TABLE " . $this->table);
    }

    /**
     * Insertar una nueva fila en la base de datos usando los campos del modelo que
     * realizo la llamada al metodo
     *
     * @param bool $autoTimestamp Bandera que indica si se intentará generar
     *                            automaticamente valores para las columnas
     *                            created_at y updated_at si existieran en la tabla
     *
     * @return bool Bandera que indica si se inserto correctamente o no la fila
     */
    public function insert($autoTimestamp = true)
    {
        $pk = $this->pk_column;
        $timeStr = date("Y-m-d H:i:s", time());
        if ($autoTimestamp
            && in_array("created_at", self::$_columns[$this->table])
        ) {
            $this->created_at = $timeStr;
        }
        if ($autoTimestamp
            && in_array("updated_at", self::$_columns[$this->table])
        ) {
            $this->updated_at = $timeStr;
        }
        
        $set = $this->setString();
        $query = "INSERT INTO " . $this->table. " SET " . $set["sql"];
        $st = $this->execute($query, $set["params"]);
        if ($st->rowCount() == 1) {
            $this->{$pk} = $this->db->lastInsertId();
        }
        return ($st->rowCount() == 1);
    }
    
    /**
     * Actualiza una fila en la base de datos usando los campos del modelo que
     * realizo la llamada al metodo
     *
     * @param bool $autoTimestamp Bandera que indica si se intentará generar
     *                            automaticamente valores para la columna
     *                            updated_at si existiera en la tabla
     *
     * @return bool Bandera que indica si se actualizo correctamente o no la fila
     */
    public function update($autoTimestamp = true)
    {
        if ($autoTimestamp
            && in_array("updated_at", self::$_columns[$this->table])
        ) {
            $this->updated_at = gmdate("Y-m-d H:i:s");
        }
        
        $set = $this->setString();
        $query = "UPDATE " . $this->table. " SET " . $set["sql"] . " WHERE " .
            $this->pk_column . " = ? LIMIT 1";
        $set["params"][] = $this->{$this->pk_column};
        $st = $this->execute($query, $set["params"]);
        return ($st->rowCount() == 1 || $st !== false);
    }
    
    /**
     * Prepara y ejecuta una sentencia SQL en la base de datos
     *
     * @param string $query  Cadena con query SQL que se desea ejecutar
     * @param array  $params Arreglo opcional de valores para los parámetros
     *                       pasados en la consulta SQL
     *
     * @return PDOStatment Devuelve una sentencia preparada y
     *                     su correpondiente conjunto de resultados
     */
    public function execute($query, $params = array())
    {
        $st = $this->prepare($query);
        $st->execute($params);
        return $st;
    }
    
    /**
     * Prepera una sentencia SQL para ejectuarla en la base de datos
     *
     * @param string $query Cadena con la sentencia SQL a preparar
     *
     * @return PDOStatment Objeto de PHP con la sentencia SQL preparada
     */
    protected function prepare($query)
    {
        if (!isset(self::$_stmt[$query])) {
            self::$_stmt[$query] = $this->db->prepare($query);
        }
        return self::$_stmt[$query];
    }
    
    /**
     * Método que toma el modelo actual que realizo la llamada y decide si
     * tiene que insertarlo a la base de datos o actualizarlo
     *
     * @return bool Retorna un valor lógico indicando si se puedo guardar
     *              exitosamente los datos a la base de datos
     */
    public function save()
    {
        if ($this->{$this->pk_column} && $this->{$this->pk_column} > 0) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }
    
    /**
     * Construye una cadena SQL para ser usada en las sentencias udpate e insert a
     * la base de datos con el formato SET column1 = ?, column2 = ?, ..., columnN = ?
     * y adicionalmente devuelve un arreglo con los valores a ser pasados a método
     * que ejecuta la correspondiente sentencia
     *
     * @return array Arreglo con dos indices, en el primero la cadena SQL con el
     *               SET de las columnas para el correspondiente objeto y el
     *               segundo indice un arreglo con los valores para los parámetros
     *               mismo que se van pasar al momento de ejecutar la sentencia final
     */
    protected function setString()
    {
        $fragments = array();
        $params = [];
        foreach (self::$_columns[$this->table] as $field) {
            if ($field == $this->pk_column) {
                continue;
            }
            if (isset($this->$field)) {
                if ($this->$field === null) {
                    $fragments[] = $field . " = NULL";
                } else {
                    $fragments[] = $field . " = ?";
                    $params[]    = $this->$field;
                }
            }
        }
        $sqlFragment = implode(", ", $fragments);
        return [
            "sql"    => $sqlFragment,
            "params" => $params
        ];
    }
}
