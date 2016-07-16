<?php
namespace Rt\Storage\PostgreSQL {
	function escapeVar(&$connect, &$value)
	{
		if($value !== NULL)
			return "'".pg_escape_string($connect, $value)."'";
		return "NULL";
	}
}
?>