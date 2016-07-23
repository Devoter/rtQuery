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
     * Конструктор
     * @param array $args - список аргументов для инициализации объекта
     */
    abstract public function __construct(array $args = array());
    
    /**
     * 
     * Деструктор
     */
    abstract public function __destruct();
    
    /**
     * 
     * Метод для инициализаци объекта
     * @param array $args - список аргументов для инициализации объекта
     */
    abstract public function initialize(array $args);
    
    /**
     * 
     * Возвращает статус инициализации объекта: true - если инициализирован,
     * false, если не инициализирован
     * @return bool
     */
    public function initialized()
    {
        return $this->_initialized;
    }
    
    /**
     * 
     * Возвращает статус отладки: true - если отладка включена, false - если
     * выключена
     * @return bool
     */
    public function debug()
    {
        return $this->_debug; 
    }
    
    /**
     * 
     * Включает/выключает отладку
     * @param bool $use - false - для выключения, true - для включения 
     */
    public function setDebug($use = false)
    {
        $this->_debug = (bool)$use;
    }

    /**
     * 
     * Возвращает префикс таблиц БД
     * @return string
     */
    public function prefix()
    {
        return $this->_prefix;
    }

    /**
     * 
     * Выполнение запроса SELECT к БД
     * @param bool $outType - тип составления таблицы ответа по значениям (false) или по столбцам (true)
     * @param bool $subquery - использовать результат в качестве подзапроса
     * @param \Rt\Storage\QueryJoin $from - список таблиц, из которых следует выполнить запрос
     * @param \Rt\Storage\QueryWhere $where - условия выборки
     * @param \Rt\Storage\QueryGroup $group - группировка при выборке
     * @param \Rt\Storage\QueryOrder $order - сортировка при выборке
     * @param int $limit - ограничение при выборке
     * @param int $start - возвращать, начиная со строки, номер которой указан в параметре
     * @return array or string or bool - в случае успешного выполнения возвращает массив, в случае ошибки - false,
     * случае подзапроса - подзапрос
     */
    abstract public function select($outType, $subquery, \Rt\Storage\QueryJoin &$from, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryGroup &$group = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);

    /**
     * 
     * Выполнение запроса INSERT к БД
     * @param \Rt\Storage\StorageObject $table - таблица, в которую будет добавлена запись со списком значений
     * @return bool - false - в случае ошибки, true - в случае успеха
     */
    abstract public function insert(\Rt\Storage\StorageObject &$table);
    
    /**
     * 
     * Выполнение запроса UPDATE к БД
     * @param \Rt\Storage\StorageObject $table - таблица, в которой будут обновлены записи со списком значений
     * @param \Rt\Storage\QueryWhere $where
     * @param \Rt\Storage\QueryOrder $order
     * @param int $limit - ограничение выборки
     * @param int $start - начальная строка выборки
     * @return bool - false - в случае ошибки, true - в случае успеха
     */
    abstract public function update(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);
    
    /**
     * 
     * Выполнение запроса DELETE к БД
     * @param \Rt\Storage\StorageObject $table - таблица, из которой следует удалить записи
     * @param \Rt\Storage\QueryWhere $where - условия выборки
     * @param \Rt\Storage\QueryOrder $order - сортировка выборки
     * @param int $limit - ограничение выборки
     * @param int $start - начальная строка выборки
     * @return bool - false - в случае ошибки, true - в случае успеха
     */
    abstract public function delete(\Rt\Storage\StorageObject &$table, \Rt\Storage\QueryWhere &$where = NULL, \Rt\Storage\QueryOrder &$order = NULL, $limit = NULL, $start = NULL);
    
    /**
     * 
     * Добавить условия WHERE
     * @param unknown_type $val - значение
     * @return \Rt\Storage\QueryWhere - часть запроса
     */
    abstract public function &where($val);

    /**
     * 
     * Добавить условия для GROUP BY
     * @param unknown_type $val - значение
     * @return \Rt\Storage\QueryGroup
     */
    abstract public function &group($val);

    /**
     * 
     * Добавить условия для ORDER BY
     * 
     * Порядок сорткировки true для сортировки по алфавиту, false - в обратном порядке.
     * Параметр $argregate задает агрегатную функцию (MIN, MAX, AVG), по умолчанию - NULL -
     * агрегатная функция не задана
     * 
     * @param unknown_type $val значение
     * @param bool $order порядок сортировки
     * @param string $agregate агрегатная функция
     * @return \Rt\Storage\QueryOrder - часть запроса
     */
    abstract public function &order($val, $order = true, $agregate = NULL);

    /**
     * 
     * Добавить условия для FROM
     * @param \Rt\Storage\StorageObject $obj - таблица
     * @return \Rt\Storage\QueryJoin - часть запроса
     */
    abstract public function &noJoin(\Rt\Storage\StorageObject &$obj);


    /**
     * 
     * Выполнить прямой запрос к СУБД (зависимо от типа БД)
     * @param unknown_type $query
     */
    abstract public function native($query);

    /**
     * 
     * Инициализирован ли объект
     * @var bool
     */
    protected $_initialized;
    
    /**
     * 
     * Префикс таблиц СУБД
     * @var string
     */
    protected $_prefix;

    /**
     * 
     * Использовать отладку (флаг)
     * @var bool
     */
    protected $_debug;
}
