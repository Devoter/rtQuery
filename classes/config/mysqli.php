<?php
namespace Rt\Storage\Tables;

function &config(\Rt\Storage\Driver &$db)
{
    $dbi = &$db->connection();
    $ret = array(
        "classname" => 'Config',
        "table" => $db->prefix().'config',
        "db" => &$dbi,
        "properties" => array(
            "id" => 'id',
            "val" => 'val',
            "param" => 'param'
        )
    );
    return $ret;
}
