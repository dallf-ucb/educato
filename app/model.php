<?php

class model
{ 
    public static $_db; 
    protected static $_stmt = array(); 
    private static $_tableColumns = array(); 
    protected static $_primary_column_name = 'id'; 
    protected static $_tableName = '_the_db_table_name_';  

    public function __construct()
    {
        static::getFieldnames(); 
    }
    
    public static function connectDb()
    {
        static::$_db = db::getDb();
    }
    
    protected static function getFieldnames()
    {
        $class = get_called_class();
        static::$_tableName = $class;
        if (!isset(self::$_tableColumns[$class])) {
            $st = static::execute('DESCRIBE ' . $class);
            self::$_tableColumns[$class] = $st->fetchAll(\PDO::FETCH_COLUMN);
        }
        return self::$_tableColumns[$class];
    }
    
    public function toArray()
    {
        $a = array();
        foreach (get_class_vars(static::$_tableName) as $fieldname) {
            $a[$fieldname] = $this->$fieldname;
        }
        return $a;
    }

    static public function getById($id)
    {
        return static::fetchOneWhere(static::$_primary_column_name . ' = ?', array($id));
    }
    
    static public function count()
    {
        $st = static::execute('SELECT COUNT(*) FROM ' . static::$_tableName);
        return (int)$st->fetchColumn(0);
    }
    
    static public function countAllWhere($SQLfragment = '', $params = array())
    {
        $SQLfragment = $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $st = static::execute('SELECT COUNT(*) FROM ' . static::$_tableName . $SQLfragment, $params);
        return (int)$st->fetchColumn(0);
    }
    
    static public function fetchWhere($SQLfragment = '', $params = array(), $limitOne = false)
    {
        $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $select = implode(", ", static::$_tableColumns[static::$_tableName]);
        $st = static::execute(
            'SELECT ' . $select . ' FROM ' . static::$_tableName . $SQLfragment . ($limitOne ? ' LIMIT 1' : ''),
            $params
        );
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        if ($limitOne) {
            $instance = new static::$_tableName();
            foreach($st->fetch() as $key => $value) {
                if (property_exists(static::$_tableName, $key))
                    $instance->$key = $value;
            }
            return $instance;
        }
        $results = [];
        while ($row = $st->fetch()) {
            $instance = new static::$_tableName();
            foreach($st->fetch() as $key => $value) {
                if (property_exists(static::$_tableName, $key))
                    $instance->$key = $value;
            }
            $results[] = $instance;
        }
        return $results;
    }

    static public function fetchPagedWhere($SQLfragment = '', $page = 1, $records_per_page = 10)
    {
        $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
        $select = implode(", ", static::$_tableColumns[static::$_tableName]);
        $params = array(($page - 1) * $records_per_page, $records_per_page);
        $st = static::execute(
            'SELECT ' . $select . ' FROM ' . static::$_tableName . $SQLfragment . ' LIMIT ?,?',
            $params
        );
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        $results = [];
        while ($row = $st->fetch()) {
            $instance = new static::$_tableName();
            foreach($st->fetch() as $key => $value) {
                if (property_exists(static::$_tableName, $key))
                    $instance->$key = $value;
            }
            $results[] = $instance;
        }
        return $results;
    }
    
    static public function fetchAllWhere($SQLfragment = '', $params = array())
    {
        return static::fetchWhere($SQLfragment, $params, false);
    }
    
    static public function fetchOneWhere($SQLfragment = '', $params = array())
    {
        return static::fetchWhere($SQLfragment, $params, true);
    }
    
    static public function deleteById($id)
    {
        $st = static::execute(
            'DELETE FROM ' . static::$_tableName . ' WHERE ' . static::$_primary_column_name . ' = ? LIMIT 1',
            array($id)
        );
        return ($st->rowCount() == 1);
    }
    
    public function delete()
    {
        return self::deleteById($this->{static::$_primary_column_name});
    }
    
    static public function deleteAllWhere($where, $params = array())
    {
        $st = static::execute(
            'DELETE FROM ' . static::$_tableName . ' WHERE ' . $where,
            $params
        );
        return $st;
    }

    public function insert($autoTimestamp = true)
    {
        $pk = static::$_primary_column_name;
        $timeStr = gmdate('Y-m-d H:i:s');
        if ($autoTimestamp && in_array('created_at', self::$_tableColumns[static::$_tableName])) {
            $this->created_at = $timeStr;
        }
        if ($autoTimestamp && in_array('updated_at', self::$_tableColumns[static::$_tableName])) {
            $this->updated_at = $timeStr;
        }
        
        $set = $this->setString();
        $query = 'INSERT INTO ' . static::$_tableName . ' SET ' . $set['sql'];
        $st    = static::execute($query, $set['params']);
        if ($st->rowCount() == 1) {
            $this->{static::$_primary_column_name} = static::$_db->lastInsertId();
        }
        return ($st->rowCount() == 1);
    }
    
    public function update($autoTimestamp = true)
    {
        if ($autoTimestamp && in_array('updated_at', static::getFieldnames())) {
            $this->updated_at = gmdate('Y-m-d H:i:s');
        }
        
        $set = $this->setString();
        $query = 'UPDATE ' . static::$_tableName . ' SET ' . $set['sql'] . ' WHERE ' . static::$_primary_column_name . ' = ? LIMIT 1';
        $set['params'][] = $this->{static::$_primary_column_name};
        $st = static::execute(
            $query,
            $set['params']
        );
        return ($st->rowCount() == 1);
    }
    
    public static function execute($query, $params = array())
    {
        $st = static::_prepare($query);
        $st->execute($params);
        return $st;
    }
    
    protected static function _prepare($query)
    {
        if (!isset(static::$_stmt[$query])) { 
            static::$_stmt[$query] = static::$_db->prepare($query);
        }
        return static::$_stmt[$query];
    }
    
    public function save()
    {
        if ($this->{static::$_primary_column_name}) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }
    
    protected function setString()
    {
        $fragments = array();
        $params = [];
        foreach (static::$_tableColumns[static::$_tableName] as $field) {
            if ($field == static::$_primary_column_name) {
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