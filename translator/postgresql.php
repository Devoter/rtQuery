<?php
require dirname(__FILE__)."../drivers/postgresql/env.php";

$postgresql['connect'] = pg_connect($storage['connectLine']);
$postgresql['prefix'] = $storage['prefix'];
$postgresql['type'] = "postgresql";
$postgresql['canonical'] = "PostgreSQL";
if(!$postgresql['connect']) {
	echo "ERROR: Cannot connect to PostgreSQL!\n";
    exit();
}
unset($storage);
