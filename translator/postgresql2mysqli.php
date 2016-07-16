<?php
chdir("..");
require_once "translator/translator.php";
require "translator/mysqli.php";
require "translator/postgresql.php";
\Rt\Translator\translate($postgresql, $mysql);
?>