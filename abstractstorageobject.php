<?php
namespace Rt\Storage;

/**
 *
 * Абстрактный класс объектов БД, используется для реализации
 * классов объектов БД для работы с конкретными БД/СУБД
 * @author nay
 *
 */
abstract class AbstractStorageObject {
    /**
     *
     * Конструктор
     * @param array $properties
     */
    abstract public function __construct(array &$properties = array());

    /**
     *
     * Инициализирован ли объект
     * @return bool - true - если инициализирован, false - в противном случае
     */
    public function initialized()
    {
        return $this->_initialized;
    }

    /**
     *
     * Не возвращать указанное свойство при запросе
     * @param string $propertyName - имя поля
     * @return bool - true - если возвращать, false - в противном случае
     */
    abstract public function disabled($propertyName);

    /**
     *
     * Задать агрегатную функцию для свойства
     *
     * Задает агрегатную функцию для свойства из списка:
     * MIN, MAX, AVG, в случае, если второй параметр не задан
     * - удаляет наложенную функцию
     *
     * @param string $propertyName имя свойста
     * @param string $func имя агрегатной функции
     */
    abstract public function agregate($propertyName, $func = NULL);

    /**
     *
     * Сдвигает индексы значений после удаления
     * @param string $propertyName - имя свойства
     */
    abstract public function compressArray($propertyName);

    /**
     *
     * Вернуть свойство
     * @param string $propertyName - имя свойства
     * @param int $index - адрес в массиве (по умолчанию - 0)
     * @param bool $queryLine - возвращать как часть строки запроса (по умолчанию)
     * @return array or unknown_type - массив с данными или часть строки запроса
     */
    abstract public function get($propertyName, $index = 0, $queryLine = true);

    /**
     *
     * Задает значение свойства
     * @param string $propertyName - имя свойства
     * @param string $expression - выражение, может принимать следубщие значения:
     * "=", "<>", ">", "<", ">=", "<=", "IS", "IS NOT", "IN", "NOT IN", "LIKE" и "REGEXP" - подробнее описание читайте
     * в документации
     * @param unknown_type $value - значение
     * @param int index - адрес в массиве (по умолчанию - 0)
     */
    abstract public function set($propertyName, $expression, $value, $index = 0);

    /**
     *
     * Очищает указанное значение заданного свойства
     * @param string $propertyName - имя свойства
     * @param int $index - адрес в массиве (по умолчанию - 0)
     */
    abstract public function blank($propertyName, $index = 0);

    /**
     *
     * Задает значение свойства на основании массива, возвращаемого методом get()
     * @param string $propertyName - имя свойства
     * @param array $data - массив с данными
     * @param int $index - адрес в массиве (по умолчанию - 0)
     */
    abstract public function setAuto($propertyName, array $data, $index = 0);

    /**
     *
     * Возвращает настоящее имя поля (в зависимости от типа БД/СУБД может отличаться)
     *
     * Если необходимо преобразовать к числовому или бинарному типу данных - необходимо
     * ввести дополнительный параметр typcast - "int" или "bin"
     *
     * @param string $propertyName - имя свойства
     * @param mixed $typecast - преобразовать в int или bin
     * @return string - имя поля
     */
    abstract public function rget($propertyName, $typecast = false);

    /**
     *
     * Инициализация объекта
     * @param array $properties - массив с данными для инициализации
     */
    abstract public function initialize(array &$properties = array());

    /**
     *
     * Не возвращать свойство при выборке
     * @param string $propertyName - имя свойства
     */
    abstract public function disable($propertyName);

    /**
     *
     * Возвращать свойство при выборке
     * @param string $propertyName - имя свойства
     */
    abstract public function enable($propertyName);

    /**
     *
     * Вернуть свойства объекта для выборки
     * @return unknown_type - часть запроса
     */
    abstract public function fields();

    /**
     *
     * Возвращает имя таблицы (зависит от типа БД/СУБД)
     * @return unknown_type - имя таблицы
     */
    abstract public function table();

    /**
     *
     * Возвращает массив с данными для записи в БД
     * @return array
     */
    abstract public function toSet();

    /**
     *
     * Очищает значения полей
     */
    abstract public function clear();

    /**
     *
     * Не возвращать при выборке ни одного свойства
     */
    abstract public function disableAll();

    /**
     *
     * Возвращать при выборке все свойства
     */
    abstract public function enableAll();

    /**
     *
     * Возвращать при выборке только свойство с указанными именем
     * @param string $propertyName - имя свойства
     */
    abstract public function enableOnly($propertyName);

    /**
     *
     * Возвращает имя класса
     * @return string
     */
    public function className()
    {
        return $this->className;
    }

    /**
     *
     * Возвращает массив с именами полей
     * @return array
     */
    abstract public function getList();

        /**
     *
     * Инициализирован ли объект
     * @var bool
     */
    protected $_initialized;

    /**
     *
     * Массив свойств
     * @var array
     */
    protected $_properties;

    /**
     *
     * Имя таблицы
     * @var unknown_type
     */
    protected $_table;

    /**
     *
     * Имя класса (или виртуальное имя таблицы)
     * @var string
     */
    protected $_className;
}
