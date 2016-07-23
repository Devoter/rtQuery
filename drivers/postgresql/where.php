<?php
namespace Rt\Storage;

class QueryWhere {
    
    public function __construct($val = NULL)
    {
        $this->_query = " ";
        if($val != NULL)
            $this->_query .= $val;
    }
    
    public function &aand($val = NULL)
    {
        $this->_query .= " AND ";
        if($val != NULL)
            $this->_query .= $val;
        return $this;
    }
    
    public function &aor($val = NULL)
    {
        $this->_query .= " OR ";
        if($val != NULL)
            $this->_query .= $val;
        return $this;
    }
    
    public function &axor($val = NULL)
    {
        $this->_query .= " XOR ";
        if($val != NULL)
            $this->_query .= $val;
        return $this;
    }
    
    public function &equals($val)
    {
        $this->_query .= " = ".$val;
        return $this;
    }
    
    public function &abeg($val)
    {
        $this->_query .= " ( ".$val;
        return $this;
    }
    
    public function &aend()
    {
        $this->_query .= " ) ";
        return $this;
    }
    
    public function getQuery()
    {
        return $this->_query;
    }

    protected $_query;
}
