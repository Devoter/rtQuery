<?php
namespace Rt\Storage;

class QueryGroup {
    
    public function __construct($val)
    {
        $this->_query = " ".$val;
    }
    
    public function group($val)
    {
        $this->_query .= ", ".$val;
        return $this;
    }
    
    public function getQuery()
    {
        return $this->_query;
    }

    protected $_query;
}
