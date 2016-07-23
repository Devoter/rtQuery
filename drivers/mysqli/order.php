<?php
namespace Rt\Storage;

class QueryOrder {

    public function __construct($val, $order = true, $aggregate = NULL)
    {
        $agr = $this->checkAggregate($aggregate);
        if($agr != NULL)
            $rval = $agr."(".$val.")";
        else
            $rval = $val;
        $this->_query = " ".$rval." ".($order ? "ASC" : "DESC");
    }

    public function &order($val, $order = true, $aggregate = NULL)
    {
        $agr = $this->checkAggregate($aggregate);
        if($agr != NULL)
            $rval = $agr."(".$val.")";
        else
            $rval = $val;
        $this->_query .= ", ".$rval." ".($order ? "ASC" : "DESC");
        return $this;
    }

    public function getQuery()
    {
        return $this->_query;
    }

    private function checkAggregate($aggregate)
    {
        $agr = NULL;
        switch($aggregate) {
            case "MIN":
                $agr = "MIN";
                break;
            case "MAX":
                $agr = "MAX";
                break;
            case "AVG":
                $agr = "AVG";
                break;
        }
        return $agr;
    }

    private $_query;
}
