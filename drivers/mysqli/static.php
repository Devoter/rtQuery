<?php
namespace Rt\Storage\MySQLi {
	function escapeVar(&$connect, &$value)
	{
		if($value !== NULL)
			return "'".mysqli_real_escape_string($connect, $value)."'";
		return "NULL";
	}
}
?>