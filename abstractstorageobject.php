<?php
namespace Rt\Storage;

/**
 *
 * Abstract storage object class, should be inherited
 * with the current storage object class
 * @author nay
 *
 */
abstract class AbstractStorageObject {
    /**
     *
     * Constructor
     * @param array $properties
     */
    abstract public function __construct(array &$properties = array());

    /**
     *
     * Returns an object state
     * @return bool - true if object has been initialized, false - if not
     */
    public function initialized()
    {
        return $this->_initialized;
    }

    /**
     *
     * Excludes the property from selection result
     * @param string $propertyName - property name
     * @return bool - true - return, false - do not return
     */
    abstract public function disabled($propertyName);

    /**
     *
     * Sets the property aggregate function from list:
     * MIN, MAX, AVG, excludes the aggregate function when
     * the second argument is NULL
     *
     * @param string $propertyName property name
     * @param string $func aggregate function name
     */
    abstract public function agregate($propertyName, $func = NULL);

    /**
     *
     * Updates the array indexes after the removal of the element
     * @param string $propertyName - property name
     */
    abstract public function compressArray($propertyName);

    /**
     *
     * Returns the property
     * @param string $propertyName - property name
     * @param int $index - array index (default - 0)
     * @param bool $queryLine - return as query line (default - true)
     * @return array or unknown_type - data array or a part of thq query line
     */
    abstract public function get($propertyName, $index = 0, $queryLine = true);

    /**
     *
     * Sets the property value
     * @param string $propertyName - property name
     * @param string $expression - an expression from this list:
     * "=", "<>", ">", "<", ">=", "<=", "IS", "IS NOT", "IN", "NOT IN", "LIKE" и "REGEXP"
     * @param unknown_type $value - value
     * @param int index - array index (default - 0)
     */
    abstract public function set($propertyName, $expression, $value, $index = 0);

    /**
     *
     * Clears target property at index
     * @param string $propertyName - property name
     * @param int $index - array index (default - 0)
     */
    abstract public function blank($propertyName, $index = 0);

    /**
     *
     * Sets the property value from the array returned with the get() method
     * @param string $propertyName - property name
     * @param array $data - data array
     * @param int $index - array index (default - 0)
     */
    abstract public function setAuto($propertyName, array $data, $index = 0);

    /**
     *
     * Returns real field name (result can be different in MySQL and PostgreSQL)
     *
     * If you have to cast to digital or binary datatype you should
     * set the typecast parameter as "int" or "bin"
     *
     * @param string $propertyName - property name
     * @param mixed $typecast - cast to int or bin
     * @return string - field name
     */
    abstract public function rget($propertyName, $typecast = false);

    /**
     *
     * Object initializer
     * @param array $properties - the list of arguments
     */
    abstract public function initialize(array &$properties = array());

    /**
     *
     * Don not return property from selection
     * @param string $propertyName - property name
     */
    abstract public function disable($propertyName);

    /**
     *
     * Return property from selection
     * @param string $propertyName - property name
     */
    abstract public function enable($propertyName);

    /**
     *
     * Returns properties for selection
     * @return unknown_type - query part
     */
    abstract public function fields();

    /**
     *
     * Return DB table name
     * @return unknown_type
     */
    abstract public function table();

    /**
     *
     * Returns an array with data prepared for DBMS
     * @return array
     */
    abstract public function toSet();

    /**
     *
     * Reset all settings
     */
    abstract public function clear();

    /**
     *
     * Do not return any property in selection
     */
    abstract public function disableAll();

    /**
     *
     * Enable all properties in selection
     */
    abstract public function enableAll();

    /**
     *
     * Enable just the one property in selection
     * @param string $propertyName - property name
     */
    abstract public function enableOnly($propertyName);

    /**
     *
     * Returns the classname (virtual table name)
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     *
     * Returns an array with the names of the fields
     * @return array
     */
    abstract public function getList();

    /**
     *
     * Init flag
     * @var bool
     */
    protected $_initialized;

    /**
     *
     * Array of properties
     * @var array
     */
    protected $_properties;

    /**
     *
     * Table name
     * @var unknown_type
     */
    protected $_table;

    /**
     *
     * Classname (virtual table name)
     * @var string
     */
    protected $_className;
}
