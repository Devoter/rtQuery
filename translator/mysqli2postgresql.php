<?php
require_once dirname(__FILE__)."/../translator/translator.php";
require dirname(__FILE__)."/../translator/mysqli.php";
require dirname(__FILE__)."/../translator/postgresql.php";

\Rt\Translator\translate($mysql, $postgresql);
