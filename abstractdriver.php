<?php
namespace Rt\Storage;

/**
 *
 * DB/DBMS interface class
 * @author nay
 *
 */
abstract class AbstractDriver {
    /**
     *
     * Constructor
     * @param array $args - the list of arguments
     */
    abstract public function __construct(array $args = array());

    /**
     *
     * Destructor
     */
    abstract public function __destruct();

    /**
     *
     * Object initializer
     * @param array $args - the list of arguments
     */
    abstract public function initialize(array $args);

    /**
     *
     * Returns an object state: true if object has been initialized,
     * false - if not
     * @return bool
     */
    public function initialized()
    {
        return $this->_initialized;
    }

    /**
     *
     * Returns debug state: true - debug on, false - debug off
     * @return bool
     */
    public function debug()
    {
        return $this->_debug;
    }

    /**
     *
     * Debug on/off
     * @param bool $use - false - turn debug mode off, true - turn debug mode on
     */
    public function setDebug($use = false)
    {
        $this->_debug = (bool)$use;
    }

    /**
     *
     * Returns the database prefix
     * @return string
     */
    public function prefix()
    {
        return $this->_prefix;
    }

    /**
     *
     * DB SELECT query
     * @param bool $outType - return result as map (true) or as array (false)
     * @param bool $subquery - prepare as subquery
     * @param \Rt\Storage\QueryJoin $from - table list
     * @param \Rt\Storage\QueryWhere $where - conditions
     * @param \Rt\Storage\QueryGroup $group - group
     * @param \Rt\Storage\QueryOrder $order - sort by
     * @param int $limit - selection limit
     * @param int $start - start row shift
     * @return array or string or bool - returns an array if correct, false if error,
     * string if $subquery == true
     */
    abstract public function select($outType, $subquery, \Rt\Storage\QueryJoin &$from, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryGroup &$group = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);

    /**
     *
     * DB INSERT query
     * @param \Rt\Storage\StorageObject $table - target table
     * @return bool - returns false if error, true if ok
     */
    abstract public function insert(\Rt\Storage\StorageObject &$table);

    /**
     *
     * DB UPDATE query
     * @param \Rt\Storage\StorageObject $table - target table
     * @param \Rt\Storage\QueryWhere $where - conditions
     * @param \Rt\Storage\QueryOrder $order - sort by
     * @param int $limit - selection limit
     * @param int $start - start row shift
     * @return bool - returns false if error, true if ok
     */
    abstract public function update(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);

    /**
     *
     * DB DELETE query
     * @param \Rt\Storage\StorageObject $table - target table
     * @param \Rt\Storage\QueryWhere $where - conditions
     * @param \Rt\Storage\QueryOrder $order - sort by
     * @param int $limit - selection limit
     * @param int $start - start row shift
     * @return bool - returns false if error, true if ok
     */
    abstract public function delete(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);

    /**
     *
     * Add WHERE conditions
     * @param unknown_type $val - value
     * @return \Rt\Storage\QueryWhere - query part
     */
    abstract public function &where($val);

    /**
     *
     * Add GROUP BY conditions
     * @param unknown_type $val - value
     * @return \Rt\Storage\QueryGroup - query group part
     */
    abstract public function &group($val);

    /**
     *
     * Add ORDER BY conditions
     *
     * Sort alphabetically (true), reverse (false)
     * $aggregate sets an aggregate function (MIN, MAX, AVG), NULL by default -
     * aggregate function is not set
     *
     * @param unknown_type $val value
     * @param bool $order sort order
     * @param string $aggregate aggregate function
     * @return \Rt\Storage\QueryOrder - query order part
     */
    abstract public function &order($val, $order = true, $aggregate = NULL);

    /**
     *
     * Add FROM conditions
     * @param \Rt\Storage\StorageObject $obj - table
     * @return \Rt\Storage\QueryJoin - query join part
     */
    abstract public function &noJoin(\Rt\Storage\StorageObject &$obj);


    /**
     *
     * Do native DBMS query (different syntax for different DBMS)
     * @param unknown_type $query
     */
    abstract public function native($query);

    /**
     *
     * Init flag
     * @var bool
     */
    protected $_initialized;

    /**
     *
     * Table prefix
     * @var string
     */
    protected $_prefix;

    /**
     *
     * Debug flag
     * @var bool
     */
    protected $_debug;
}
