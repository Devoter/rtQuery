<?php
namespace Rt\Storage;

require_once dirname(__FILE__).'../../abstractstorageobject.php';

/**
 *
 * PostgreSQL storage object class
 * @author nay
 *
 */
class StorageObject extends \Rt\Storage\AbstractStorageObject {

    /**
     *
     * Constructor
     * @param array $properties
     */
    public function __construct(array &$properties = array())
    {
        $this->_initialized = false;
        $this->initialize($properties);
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::disabled()
     */
    public function disabled($propertyName)
    {
        if(isset($this->_properties[$propertyName]))
            return $this->_properties[$propertyName]['disable'];
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::agregate()
     */
    public function agregate($propertyName, $func = NULL)
    {
        if(isset($this->_properties[$propertyName])) {
            $fnc = "";
            switch($func) {
                case "MIN":
                    $fnc = "MIN";
                    break;
                case "MAX":
                    $fnc = "MAX";
                    break;
                case "AVG":
                    $fnc = "AVG";
                    break;
                default:
                    $fnc = NULL;
                    break;
            }
            $this->_properties[$propertyName]['agregate'] = $fnc;
        }
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::compressArray()
     */
    public function compressArray($propertyName)
    {
        if(isset($this->_properties[$propertyName])) {
            $i = 0;
            foreach($this->_properties[$propertyName]['values'] as $el) {
                $this->_properties[$propertyName]['values'][$i] = $el;
                $i++;
            }
        }
    }

    /**
     *
     * Bounds an array of arguments in a row
     * @param array $arg
     * @return string
     */
    public function arrimplode(array $arg)
    {
        $ret = "";
        $first = true;
        foreach($arg as $el) {
            if($first) {
                $first = false;
            }
            else
                $ret .= ", ";
            $ret .= "E'".pg_escape_string($this->_db, $el)."'";
        }
        return $ret;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::get()
     */
    public function get($propertyName, $index = 0, $queryLine = true)
    {
        if(isset($this->_properties[$propertyName]) && isset($this->_properties[$propertyName]['values'][$index])) {
            if($queryLine) {
                $exp = $this->_properties[$propertyName]['values'][$index]['exp'] != "REGEXP" ? $this->_properties[$propertyName]['values'][$index]['exp'] : "~";
                $ret = " \"".$this->_table."\".\"".$this->_properties[$propertyName]['realName']."\" ".$exp." ";
                if(($this->_properties[$propertyName]['values'][$index]['exp'] == "IN") || ($this->_properties[$propertyName]['values'][$index]['exp'] == "NOT IN"))
                    $ret .= "( ".$this->_properties[$propertyName]['values'][$index]['val']." ) ";
                elseif(($this->_properties[$propertyName]['values'][$index]['exp'] == "IS") || ($this->_properties[$propertyName]['values'][$index]['exp'] == "IS NOT"))
                    $ret .= $this->_properties[$propertyName]['values'][$index]['val'];
                else
                    $ret .= "E'".$this->_properties[$propertyName]['values'][$index]['val']."'";
                return $ret;
            }
            $ret = array(
                "expression" => $this->_properties[$propertyName]['values'][$index]['exp'],
                "value" => $this->_properties[$propertyName]['values'][$index]['val']
            );
            return $ret;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::set()
     */
    public function set($propertyName, $expression, $value, $index = 0)
    {
        if(isset($this->_properties[$propertyName]) && (
            ($expression == "=") || ($expression == "<>") ||
            ($expression == ">") || ($expression == "<") ||
            ($expression == ">=") || ($expression == "<=") ||
            ($expression == "IS") || ($expression == "IN") ||
            ($expression == "IS NOT") || ($expression == "NOT IN") ||
            ($expression == "LIKE") || ($expression == "REGEXP"))) {
            $this->_properties[$propertyName]['values'][$index]['exp'] = $expression;
            if(($expression == "IN") || ($expression == "NOT IN")) {
                if(is_array($value))
                    $this->_properties[$propertyName]['values'][$index]['val'] = $this->arrimplode($value);
                else
                    $this->_properties[$propertyName]['values'][$index]['val'] = $value;
            }
            elseif(($expression == "LIKE") || ($expression == "REGEXP"))
                $this->_properties[$propertyName]['values'][$index]['val'] = $value;
            else
                $this->_properties[$propertyName]['values'][$index]['val'] = addslashes(pg_escape_string($this->_db, $value));
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::setAuto()
     */
    public function setAuto($propertyName, array $data, $index = 0)
    {
        if(is_array($data) && isset($data['expression']) &&
            isset($data['value']) && isset($this->_properties[$propertyName])) {
            if(($data['expression'] == "=") || ($data['expression'] == "<>") ||
                ($data['expression'] == ">") || ($data['expression'] == "<") ||
                ($data['expression'] == ">=") || ($data['expression'] == "<=") ||
                ($data['expression'] == "IS") || ($data['expression'] == "IN") ||
                ($data['expression'] == "IS NOT") || ($data['expression'] == "NOT IN") ||
                ($data['expression'] == "LIKE") || ($data['expression'] == "REGEXP")) {
                $this->_properties[$propertyName]['values'][$index]['exp'] = $data['expression'];
                if(($data['expression'] == "IN") || ($data['expression'] == "NOT IN")) {
                    if(is_array($data['value']))
                        $this->_properties[$propertyName]['values'][$index]['val'] = $this->arrimplode($data['value']);
                    else
                        $this->_properties[$propertyName]['values'][$index]['val'] = $data['value'];
                }
                elseif(($data['expression'] == "LIKE") || ($data['expression'] == "REGEXP"))
                    $this->_properties[$propertyName]['values'][$index]['val'] = $data['value'];
                else
                    $this->_properties[$propertyName]['values'][$index]['val'] = pg_escape_string($this->_db, $data['value']);
            }
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::blank()
     */
    public function blank($propertyName, $index = 0)
    {
        if(isset($this->_properties[$propertyName]) && isset($this->_properties[$propertyName]['values'][$index])) {
            unset($this->_properties[$propertyName]['values'][$index]);
            $this->compressArray($propertyName);
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::rget()
     */
    public function rget($propertyName, $typecast = false)
    {
        $cast = $typecast;
        switch($typecast) {
            case "int":
                $cast = "INTEGER";
                break;
            case "bin":
                $cast = "BITEA";
                break;
            default:
                $cast = false;
                break;
        }
        if(isset($this->_properties[$propertyName])) {
            if($cast)
                return "CAST(\"".$this->_table."\".\"".$this->_properties[$propertyName]['realName']."\" AS ".$cast.")";
            else
                return "\"".$this->_table."\".\"".$this->_properties[$propertyName]['realName']."\"";
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::initialize()
     */
    public function initialize(array &$properties = array())
    {
        $this->_properties = array();
        $this->_table = "";
        if(isset($properties['table']) && isset($properties['properties']) &&
            isset($properties['classname']) && isset($properties['db'])) {
            $this->_db = &$properties['db'];
            $this->_className = $properties['classname'];
            $this->_table = $properties['table'];
            foreach($properties['properties'] as $key => $value)
                $this->_properties[$key] = array(
                    "realName" => $value,
                    "values" => array(
                        array(
                            "exp" => "=",
                            "val" => NULL
                        )
                    ),
                    "disable" => false,
                    "agregate" => NULL
                );
            $this->_initialized = true;
        }
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::disable()
     */
    public function disable($propertyName)
    {
        if(isset($this->_properties[$propertyName])) {
            $this->_properties[$propertyName]['disable'] = true;
        }
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::enable()
     */
    public function enable($propertyName)
    {
        if(isset($this->_properties[$propertyName])) {
            $this->_properties[$propertyName]['disable'] = false;
        }
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::disableAll()
     */
    public function disableAll()
    {
        foreach($this->_properties as $key => $value)
            $this->_properties[$key]['disable'] = true;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::enableAll()
     */
    public function enableAll()
    {
        foreach($this->_properties as $key => $value)
            $this->_properties[$key]['disable'] = false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::enableOnly()
     */
    public function enableOnly($propertyName)
    {
        $this->disableAll();
        if(isset($this->_properties[$propertyName]))
            $this->_properties[$propertyName]['disable'] = false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject:Enter description here ...:fields()
     */
    public function fields()
    {
        $ret = "";
        $first = true;
        foreach($this->_properties as $key => $value) {
            if(!$value['disable']) {
                if($first)
                    $first = false;
                else
                    $ret .= ",";
                if($value['agregate'] != NULL)
                    $ret .= " ".$value['agregate']."(\"".$this->_table."\".\"".$value['realName']."\")";
                else
                    $ret .= " \"".$this->_table."\".\"".$value['realName']."\"";
                 $ret .= " AS \"".$this->_className."_".$key."\"";
            }
        }
        return $ret;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::table()
     */
    public function table()
    {
        return $this->_table;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::toSet()
     */
    public function toSet()
    {
        $ret = array();
        foreach($this->_properties as $key => $value) {
            if(!$value['disable'])
                $ret[count($ret)] = array($value['realName'], $value['values'][0]['val']);
        }
        return $ret;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::clear()
     */
    public function clear()
    {
        foreach($this->_properties as $key => $value)
            $this->_properties[$key] = array(
                "realName" => $value['realName'],
                "values" => array(
                    array(
                        "exp" => "=",
                        "val" => NULL
                    )
                ),
                "disable" => false,
                "agregate" => NULL
            );
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractStorageObject::getList()
     */
    public function getList()
    {
        $propertiesList = array();

        foreach($this->_properties as $key => $val)
            $propertiesList[] = $key;
        return $propertiesList;
    }

    /**
     *
     * DBMS connection id
     * @var unknown_type
     */
    private $_db;
}
