<?php
require dirname(__FILE__)."../drivers/mysqli/env.php";

$mysql['connect'] = mysqli_connect($storage['host'], $storage['username'], $storage['password'], $storage['database']);
$mysql['prefix'] = $storage['prefix'];
$mysql['type'] = "mysqli";
$mysql['canonical'] = "MySQLi";
if(!$mysql['connect']) {
	echo "ERROR: Cannot connect to MySQL!\n";
	exit(); 
}
unset($storage);
