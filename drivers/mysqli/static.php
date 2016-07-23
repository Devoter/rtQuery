<?php
namespace Rt\Storage\MySQLi;

/**
 *
 * Escape value using connection
 * @param unknown_type $connection  - DBMS connection
 * @param string $value - value
 */
function escapeVar(&$connect, &$value)
{
    if($value !== NULL)
        return "'".mysqli_real_escape_string($connect, $value)."'";
    return "NULL";
}
