<?php

namespace Rt\Storage;

require_once dirname(__FILE__).'/../../abstractdriver.php';
require_once dirname(__FILE__).'/storageobject.php';
require_once dirname(__FILE__).'/where.php';
require_once dirname(__FILE__).'/group.php';
require_once dirname(__FILE__).'/order.php';
require_once dirname(__FILE__).'/join.php';

/**
 *
 * MySQL driver class
 * @author nay
 *
 */
class Driver extends \Rt\Storage\AbstractDriver {
    /**
     *
     * Конструктор
     * @param array $args - список аргументов для инициализации объекта
     */
    public function __construct(array $args = array())
    {
        $this-> initialized = false;
        $this->_debug = false;
        $this->_connection = 0;
        $this->initialize($args);
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Rt\Storage.AbstractDriver::__destruct()
     */
    public function __destruct()
    {
        if($this->_initialized)
            @mysqli_close($this->_connection);
    }

    /**
     *
     * Возвращает ссылку на идентификатор соединения с СУБД
     *
     * @return unknown_type
     */
    public function &connection()
    {
        return $this->_connection;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::initialize()
     */
    public function initialize(array $args)
    {
        if(!$this->_initialized) {
            $this->_host = $args['host'];
            $this->_database = $args['database'];
            $this->_username = $args['username'];
            $this->_password = $args['password'];
            $this->prefix = $args['prefix'];
            $this->_connection = 0;
            $this->connect();
        }
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::isInitialized()
     */
    public function initialized()
    {
        return $this->_initialized;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::select()
     */
    public function select($outType, $subquery, \Rt\Storage\QueryJoin &$from, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryGroup &$group = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL)
    {
        $query = "SELECT ";
        if($subquery)
            $query .= $from->getFields(true);
        else
            $query .= $from->getFields();
        $query .= " FROM ".$from->getLine()." ";
        if($where != NULL)
            $query .= " WHERE ".$where->getQuery();
        if($group != NULL)
            $query .= " GROUP BY ".$group->getQuery();
        if($order != NULL)
            $query .= " ORDER BY ".$order->getQuery();
        if(($start != NULL) && ($limit != NULL))
            $query .= " LIMIT ".$start.", ".$limit;
        elseif($limit != NULL)
            $query .= " LIMIT ".$limit;
        $answer = array();
        if($this->_debug)
            echo "<span style=\"font-family: monospace; font-size: 10pt;\">".htmlentities($query, ENT_QUOTES, "UTF-8")."</span><br />\n";
        if($subquery)
            return $query;
        if(($result = @mysqli_query($this->_connection, $query)) !== false) {
            $answer = array();
            $beg = true;
            while($line = @mysqli_fetch_assoc($result)) {
                $access = true;
                if((!$outType) && $beg) {
                    $beg = false;
                    foreach($line as $ind => $el)
                        $answer[$ind] = array();
                }
                if($outType)
                        $answer[count($answer)] = $line;
                else {
                    foreach($line as $ind => $el)
                        $answer[$ind][count($answer[$ind])] = $el;
                }
            }
            @mysqli_free_result($result);
        }
        else
            return false;
        return $answer;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::insert()
     */
    public function insert(\Rt\Storage\StorageObject &$table)
    {
        $query = "INSERT INTO `".$table->table()."`";
        $params = "";
        $values = "";
        foreach($table->toSet() as $value) {
            $params .= ($params == "" ? "" : ", ")."`".$value[0]."`";
            if($value[1] != "NULL")
                $values .= ($values == "" ? "" : ", ")."'".$value[1]."'";
            else
                $values .= ($values == "" ? "" : ", ")."NULL";
        }
        $query .= " ( ".$params." ) "." VALUES ( ".$values." )";
        if($this->_debug)
            echo "<span style=\"font-family: monospace; font-size: 10pt;\">".htmlentities($query, ENT_QUOTES, "UTF-8")."</span><br />\n";
        if($result = @mysqli_query($this->_connection, $query))
            return @mysqli_insert_id($this->_connection);
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::update()
     */
    public function update(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL)
    {
        $query = "UPDATE `".$table->table()."` SET ";
        $first = true;
        $setLine = "";
        foreach($table->toSet() as $value) {
            if($first)
                $first = false;
            else
                $setLine .= ",";
            $setLine .= " `".$value[0]."`";
            if($value[1] != "NULL")
                $setLine .= "='".$value[1]."'";
            else
                $setLine .= "= NULL";
        }
        $query .= $setLine;
        if($where != NULL)
            $query .= " WHERE ".$where->getQuery();
        if($order != NULL)
            $query .= " ORDER BY ".$order->getQuery();
        if(($start != NULL) && ($limit != NULL))
            $query .= " LIMIT ".$start.", ".$limit;
        elseif($limit != NULL)
            $query .= " LIMIT ".$limit;
        if($this->_debug)
            echo "<span style=\"font-family: monospace; font-size: 10pt;\">".htmlentities($query, ENT_QUOTES, "UTF-8")."</span><br />\n";
        if(($result = @mysqli_query($this->_connection, $query)) !== false) {
            @mysqli_free_result($result);
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::delete()
     */
    public function delete(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL)
    {
        $query = "DELETE FROM `".$table->table()."`";
        if($where != NULL)
            $query .= " WHERE ".$where->getQuery();
        if($order != NULL)
            $query .= " ORDER BY ".$order->getQuery();
        if(($start != NULL) && ($limit != NULL))
            $query .= " LIMIT ".$start.", ".$limit;
        elseif($limit != NULL)
            $query .= " LIMIT ".$limit;
        if($this->_debug)
            echo "<span style=\"font-family: monospace; font-size: 10pt;\">".htmlentities($query, ENT_QUOTES, "UTF-8")."</span><br />\n";
        if(($result = @mysqli_query($this->_connection, $query)) !== false) {
            @mysqli_free_result($result);
            return true;
        }
        return false;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::where()
     */
    public function &where($val)
    {
        $some = new \Rt\Storage\QueryWhere($val);
        return $some;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::group()
     */
    public function &group($val)
    {
        $some = new \Rt\Storage\QueryGroup($val);
        return $some;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::order()
     */
    public function &order($val, $order = true, $agregate = NULL)
    {
        $some = new \Rt\Storage\QueryOrder($val, $order, $agregate);
        return $some;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::noJoin()
     */
    public function &noJoin(\Rt\Storage\StorageObject &$obj)
    {
        $some = new \Rt\Storage\QueryJoin($obj);
        return $some;
    }

    /**
     * (non-PHPdoc)
     * @see Rt\Storage.AbstractDriver::native()
     */
    public function native($query)
    {
        return @mysqli_query($this->_connection, $query);
    }

    /**
     *
     * Соединение с сервером MySQL
     */
    private function connect()
    {
        if(isset($this->_host) && isset($this->_database) &&
            isset($this->_username) && isset($this->_password)) {
            if($this->_connection = mysqli_connect($this->_host, $this->_username, $this->_password, $this->_database))
                $this->_initialized = true;
        }
        if((!$this->_initialized) && $this->_connection) {
            @mysqli_close($this->_connection);
            $this->_connection = 0;
        }
    }

    /**
     *
     * Адрес сервера MySQL
     * @var string
     */
    private $_host;

    /**
     *
     * Имя базы данных MySQL
     * @var string
     */
    private $_database;

    /**
     *
     * Имя пользователя MySQL
     * @var string
     */
    private $_username;

    /**
     *
     * Пароль MySQL
     * @var string
     */
    private $_password;

    /**
     *
     * Идентификатор соединения с MySQL
     * @var int
     */
    private $_connection;
}
