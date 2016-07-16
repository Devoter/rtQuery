<?php
namespace Rt\Storage\Tables {
	function &groups(\Rt\Storage\Driver &$db)
	{
		$dbi = &$db->connection();
		$ret = array(
			"classname" => 'Groups',
			"table" => $db->prefix().'groups',
			"db" => &$dbi,
			"properties" => array(
				"id" => 'id',
				"name" => 'name'
			)
		);
		return $ret;
	}
}
?>