<?php
$storageType = "mysqli";

require_once dirname(__FILE__)."/drivers/".$storageType."/driver.php";
require_once dirname(__FILE__)."/drivers/".$storageType."/env.php";

require_once dirname(__FILE__)."/classes/config/".$storageType.".php";

use \Rt\Storage\Tables;
use \Rt\Storage\Driver;
use \Rt\Storage\StorageObject;

$db = new Driver($storage);
if(!$db->initialized()) {
    echo "Sorry, cannot connect to database.\n";
    exit();
}

$config = new StorageObject(Tables\config($db));
$config->set("param", "=", "libname");

$result = $db->select(
      true,
      false,
      $db->noJoin($config),
      $db->where($config->get("param"))
    );
if($result === false) {
    echo("Select query failed.\n");
    exit();
}

var_dump($result);

$config->set("param", "=", "version");
$config->set("val", "=", "0.01");

$result = $db->insert($config);
if($result === false) {
    echo("Insert query failed.\n");
    exit();
}

$config->clear();

$result = $db->select(true, false, $db->noJoin($config));
if($result === false) {
    echo("Select query 2 failed.\n");
    exit();
}

var_dump($result);

$config->set("param", "=", "version");
$result = $db->select(
    true,
    false,
    $db->noJoin($config),
    $db->where($config->get("param"))
    );
if($result === false) {
    echo("Select query 3 failed.\n");
    exit();
}

echo ("Library version: ".$result[0]['Config_val']."\n");
