<?php
namespace Rt\Storage;

require_once dirname(__FILE__).'/storageobject.php';
require_once dirname(__FILE__).'/where.php';

class QueryJoin {

    public function __construct(\Rt\Storage\StorageObject &$obj)
    {
        $this->_count = false;
        $this->_tables = array($obj->table());
        $this->_line = " \"".$obj->table()."\"";
        $this->_fields = ($obj->fields() != "" ? " ".$obj->fields() : "");
    }

    public function &leftJoin($eqOrig, $eqJoin, \Rt\Storage\StorageObject &$obj, \Rt\Storage\QueryWhere &$where = NULL)
    {
        $this->_tables[count($this->_tables)] = $obj->table();
        $this->_line .= " LEFT JOIN \"".$obj->table()."\" ON (".$eqOrig."=".$eqJoin.")";
        if($obj->fields() != "") {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " ".$obj->fields();
        }
        if($where != NULL)
            $this->_line .= " AND (".$where->getQuery().")";
        return $this;
    }

    public function &rightJoin($eqOrig, $eqJoin, \Rt\Storage\StorageObject &$obj, \Rt\Storage\QueryWhere &$where = NULL)
    {
        $this->_tables[count($this->_tables)] = $obj->table();
        $this->_line .= " RIGHT JOIN \"".$obj->table()."\" ON (".$eqOrig."=".$eqJoin.")";
        if($obj->fields() != "") {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " ".$obj->fields();
        }
        if($where != NULL)
            $this->_line .= " AND (".$where->getQuery().")";
        return $this;
    }

    public function &innerJoin($eqOrig, $eqJoin, \Rt\Storage\StorageObject &$obj, \Rt\Storage\QueryWhere &$where = NULL)
    {
        $this->_tables[count($this->_tables)] = $obj->table();
        $this->_line .= " INNER JOIN \"".$obj->table()."\" ON (".$eqOrig."=".$eqJoin.")";
        if($obj->fields() != "") {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " ".$obj->fields();
        }
        if($where != NULL)
            $this->_line .= " AND (".$where->getQuery().")";
        return $this;
    }

    public function &fullJoin($eqOrig, $eqJoin, \Rt\Storage\StorageObject &$obj, \Rt\Storage\QueryWhere &$where)
    {
        $this->_tables[count($this->_tables)] = $obj->table();
        $this->_line .= " FULL OUTER JOIN \"".$obj->table()."\" ON (".$eqOrig."=".$eqJoin.")";
        if($obj->fields() != "") {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " ".$obj->fields();
        }
        if($where != NULL)
            $this->_line .= " AND (".$where->getQuery().")";
        return $this;
    }

    public function &crossJoin(\Rt\Storage\StorageObject &$obj)
    {
        $this->_tables[count($this->_tables)] = $obj->table();
        $this->_line .= " CROSS JOIN \"".$obj->table();
        if($obj->fields() != "") {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " ".$obj->fields();
        }
        return $this;
    }

    public function &count()
    {
        if(!$this->_count) {
            if($this->_fields != "")
                $this->_fields .= ",";
            $this->_fields .= " COUNT(*) AS \"count\"";
            $this->_count = true;
        }
        return $this;
    }

    public function getTables()
    {
        return $this->_tables;
    }

    public function getFields($system = false)
    {
        return $this->_fields;
    }

    public function getLine()
    {
        return $this->_line;
    }

    protected $_tables;
    protected $_line;
    protected $_fields;
    protected $_count;
}
