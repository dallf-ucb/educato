<?php
class model
{ 
    public $db; 
    private $columns = array(); 
    protected $stmt = array(); 
    protected $pk_column = 'id'; 
    protected $table = '_the_db_table_name_';  

    public function __construct()
    {
        $this->connectDb();
        $this->getFieldnames(); 
    }
    
    public function connectDb()
    {
        $this->db = db::getDb();
    }
    
    protected function getFieldnames()
    {
        $class = str_replace("m_", "", get_called_class());
        $this->table = $class;
        if (!isset($this->columns[$class])) {
            $st = $this->execute('DESCRIBE ' . $class);
            $this->columns[$class] = $st->fetchAll(\PDO::FETCH_COLUMN);
        }
        return $this->columns[$class];
    }
    
    public function count()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table;
        $st = $this->execute($sql);
        return (int)$st->fetchColumn(0);
    }
    
    public function countAllWhere($SQLfragment = '', $params = array())
    {
        $SQLfragment = $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $st = $this->execute('SELECT COUNT(*) FROM ' . $this->table. $SQLfragment, $params);
        return (int)$st->fetchColumn(0);
    }
    
    public function fetchWhere($SQLfragment = '', $params = array(), $limitOne = false)
    {
        $SQLfragment = $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $cols = implode(", ", $this->columns[$this->table]);
        $sql = 'SELECT ' . $cols . ' FROM ' . $this->table. $SQLfragment . ($limitOne ? ' LIMIT 1' : ''); 
        echo $sql;
        $st = $this->execute(
            $sql,
            $params
        );
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        if ($limitOne) {
            $instance = new $this->table();
            foreach($st->fetch() as $key => $value) {
                if (property_exists($this->table, $key))
                    $instance->$key = $value;
            }
            return $instance;
        }
        $results = [];
        while ($row = $st->fetch()) {
            $model = 'm_'. $this->table;
            $instance = new $model();
            foreach($st->fetch() as $key => $value) {
                if (property_exists($model, $key))
                    $instance->$key = $value;
            }
            $results[] = $instance;
        }
        return $results;
    }

    public function fetchPagedWhere($SQLfragment = '', $page = 1, $records_per_page = 10)
    {
        $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $cols = implode(", ", $this->columns[$this->table]);
        $params = array(($page - 1) * $records_per_page, $records_per_page);
        $st = execute(
            'SELECT ' . $cols . ' FROM ' . $this->table. $SQLfragment . ' LIMIT ?,?',
            $params
        );
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        $results = [];
        while ($row = $st->fetch()) {
            $instance = new $this->table();
            foreach($st->fetch() as $key => $value) {
                if (property_exists($this->table, $key))
                    $instance->$key = $value;
            }
            $results[] = $instance;
        }
        return $results;
    }
    
    public function fetchOneWhere($SQLfragment = '', $params = array())
    {
        return $this->fetchWhere($SQLfragment, $params, true);
    }
    
    public function deleteById($id)
    {
        $st = $this->execute(
            'DELETE FROM ' . $this->table. ' WHERE ' . $this->pk_column . ' = ? LIMIT 1',
            array($id)
        );
        return ($st->rowCount() == 1);
    }
    
    public function delete()
    {
        return $this->deleteById($this->{$pk_column});
    }
    
    public function deleteAllWhere($where, $params = array())
    {
        $st = $this->execute(
            'DELETE FROM ' . $this->table. ' WHERE ' . $where,
            $params
        );
        return $st;
    }

    public function insert($autoTimestamp = true)
    {
        $pk = $this->pk_column;
        $timeStr = gmdate('Y-m-d H:i:s');
        if ($autoTimestamp && in_array('created_at', $this->columns[$_tableName])) {
            $this->created_at = $timeStr;
        }
        if ($autoTimestamp && in_array('updated_at', $this->columns[$this->table])) {
            $this->updated_at = $timeStr;
        }
        
        $set = $this->setString();
        $query = 'INSERT INTO ' . $this->table. ' SET ' . $set['sql'];
        $st = $this->execute($query, $set['params']);
        if ($st->rowCount() == 1) {
            $this->{$pk} = $this->db->lastInsertId();
        }
        return ($st->rowCount() == 1);
    }
    
    public function update($autoTimestamp = true)
    {
        if ($autoTimestamp && in_array('updated_at', getFieldnames())) {
            $this->updated_at = gmdate('Y-m-d H:i:s');
        }
        
        $set = $this->setString();
        $query = 'UPDATE ' . $this->table. ' SET ' . $set['sql'] . ' WHERE ' . $this->pk_column . ' = ? LIMIT 1';
        $set['params'][] = $this->{$this->pk_column};
        $st = execute(
            $query,
            $set['params']
        );
        return ($st->rowCount() == 1);
    }
    
    public function execute($query, $params = array())
    {
        $st = $this->prepare($query);
        $st->execute($params);
        return $st;
    }
    
    protected function prepare($query)
    {
        if (!isset($this->stmt[$query])) { 
            $this->stmt[$query] = $this->db->prepare($query);
        }
        return $this->stmt[$query];
    }
    
    public function save()
    {
        if ($this->{$pk_column}) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }
    
    protected function setString()
    {
        $fragments = array();
        $params = [];
        foreach ($this->columns[$this->pk_column] as $field) {
            if ($field == $this->pk_column) {
                continue;
            }
            if (isset($this->$field)) { 
                if ($this->$field === null) { 
                    $fragments[] = $field . ' = NULL';
                } else {
                    $fragments[] = $field . ' = ?';
                    $params[]    = $this->$field;
                }
            }
        }
        $sqlFragment = implode(", ", $fragments);
        return [
            'sql'    => $sqlFragment,
            'params' => $params
        ];
    } 
}
?>