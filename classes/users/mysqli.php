<?php
namespace Rt\Storage\Tables;

function &users(\Rt\Storage\Driver &$db)
{
    $dbi = &$db->connection();
    $ret = array(
        "classname" => 'Users',
        "table" => $db->prefix().'users',
        "db" => &$dbi,
        "properties" => array(
            "id" => 'id',
            "login" => 'login',
            "password" => 'password',
            "groups" => 'groups',
            "mainGroup" => 'mainGroup',
            "date" => 'date',
            "locked" => 'locked',
            "checked" => 'checked'
        )
    );
    return $ret;
}
