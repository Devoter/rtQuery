<?php
$moduleName = mb_strtoupper($argv[1][0]).mb_substr($argv[1], 1);;
$filename = $argv[2];
$content = file_get_contents($filename);
$patterns = array(
	"/(?<=\S)(,)/",
	"/(\s+)/"
);
$replace = array(
	" ,",
	" "
);
$content = preg_replace($patterns, $replace, $content);
$content = trim($content);
$contentList = explode(" ", $content);
$tablesCount = intval($argv[3]);

$mysqli = "<?php\n".
"namespace Rt\\Install\\MySQLi\\".$moduleName." {\n".
"\trequire_once \"core/storage/drivers/mysqli/static.php\";\n".
"\tuse \\Rt\\Storage\\MySQLi;\n\n".
"\tfunction createTables(array &\$storage)\n".
"\t{\n";

$postgresql= "<?php\n".
"namespace Rt\\Install\\PostgreSQL\\".$moduleName." {\n".
"\trequire_once \"core/storage/drivers/postgresql/static.php\";\n".
"\tuse \\Rt\\Storage\\PostgreSQL;\n\n".
"\tfunction createTables(array &\$storage)\n".
"\t{\n";

$lex = 0;

$mysqliDumpTables = "\tfunction dumpTables(array &\$storage)\n".
	"\t{\n".
	"\t\t\$dump = array();\n";
$postgresqlDumpTables = "\tfunction dumpTables(array &\$storage)\n".
	"\t{\n".
	"\t\t\$dump = array();\n";

$mysqliFillTables = "\tfunction fillTables(array &\$storage, array &\$dump)\n".
	"\t{\n";
$postgresqlFillTables = "\tfunction fillTables(array &\$storage, array &\$dump)\n".
	"\t{\n"; 

for($i = 0; $i < $tablesCount; $i++) {
	$lex += 2;
	$tableName = str_replace("`", "", $contentList[$lex]);
	$mysqli .= "\t\tmysqli_query(\$storage['connect'], \"DROP TABLE IF EXISTS `\".\$storage['prefix'].\"".$tableName."`\");\n".
		"\t\tmysqli_query(\$storage['connect'], \"CREATE TABLE `\".\$storage['prefix'].\"".$tableName."` (\".\n";
	
	$postgresql .= "\t\tpg_query(\$storage['connect'], \"DROP TABLE IF EXISTS \\\"\".\$storage['prefix'].\"".$tableName."\\\"\");\n".
		"\t\tpg_query(\$storage['connect'], \"CREATE TABLE \\\"\".\$storage['prefix'].\"".$tableName."\\\" (\".\n";

	$mysqliDumpTables .= "\t\t\$dump['".$tableName."'] = array();\n".
		"\t\tif((\$result = mysqli_query(\$storage['connect'], \"SELECT * FROM `\".\$storage['prefix'].\"".$tableName."`\")) !== false) {\n".
		"\t\t\tfor(\$i = 0; \$line = mysqli_fetch_assoc(\$result); \$i++)\n".
		"\t\t\t\t\$dump['".$tableName."'][\$i] = \$line;\n".
		"\t\t}\n";
	$postgresqlDumpTables .= "\t\t\$dump['".$tableName."'] = array();\n".
		"\t\tif((\$result = pg_query(\$storage['connect'], \"SELECT * FROM \\\"\".\$storage['prefix'].\"".$tableName."\\\"\")) !== false) {\n".
		"\t\t\tfor(\$i = 0; \$line = pg_fetch_assoc(\$result); \$i++)\n".
		"\t\t\t\t\$dump['".$tableName."'][\$i] = \$line;\n".
		"\t\t}\n";
	
	$mysqliFillTables .= "\t\tif(isset(\$dump['".$tableName."']) && is_array(\$dump['".$tableName."']) && sizeof(\$dump['".$tableName."'])) {\n".
		"\t\t\t\$query = \"INSERT INTO `\".\$storage['prefix'].\"".$tableName."` VALUES\";\n".
		"\t\t\tfor(\$i = 0; \$i < sizeof(\$dump['".$tableName."']); \$i++) {\n".
		"\t\t\t\tif(\$i)\n".
		"\t\t\t\t\t\$query .= \",\";\n".
		"\t\t\t\t\$query .= \" (\".\n";
	$postgresqlFillTables .= "\t\tif(isset(\$dump['".$tableName."']) && is_array(\$dump['".$tableName."']) && sizeof(\$dump['".$tableName."'])) {\n".
		"\t\t\t\$query = \"INSERT INTO \\\"\".\$storage['prefix'].\"".$tableName."\\\" VALUES\";\n".
		"\t\t\tfor(\$i = 0; \$i < sizeof(\$dump['".$tableName."']); \$i++) {\n".
		"\t\t\t\tif(\$i)\n".
		"\t\t\t\t\t\$query .= \",\";\n".
		"\t\t\t\t\$query .= \" (\".\n";
	$lex += 2;
	$first = true;
	while($contentList[$lex][0] == "`") {
		$fieldName = str_replace("`", "", $contentList[$lex]);
		
		if(!$first) {
			$mysqliFillTables .= ",\".\n";
			$postgresqlFillTables .= ",\".\n";
		}
		$mysqliFillTables .= "\t\t\t\t\tMySQLi\\escapeVar(\$storage['connect'], \$dump['".$tableName."'][\$i]['".$fieldName."']).\"";
		$postgresqlFillTables .= "\t\t\t\t\tPostgreSQL\\escapeVar(\$storage['connect'], \$dump['".$tableName."'][\$i]['".$fieldName."']).\"";
		$lex++;
		$type = $contentList[$lex];
		$lex++;
		$otherParams = "";
		$serial = false;
		
		for(;$contentList[$lex] != ","; $lex++) {
			$otherParams .= " ".$contentList[$lex];
			if($contentList[$lex] == "AUTO_INCREMENT")
				$serial = true;
		}
		$mysqli .= "\t\t\t\t\"`".$fieldName."` ".$type.$otherParams.",\".\n";
		if($serial) {
			$postgresql .= "\t\t\t\t\"\\\"".$fieldName."\\\" SERIAL,\".\n";
		}
		else {
			$postgreType = "";
			$realTypeName = preg_replace("/([\(]{1}.+)/", "", $type);
			switch($realTypeName) {
				case "int":
				case "mediumint":
					$postgreType = "integer";
					break;
				case "tinyint":
				case "smallint":
				case "zerofill":
					$postgreType = "smallint";
					break;
				case "bigint":
					$postgreType = "bigint";
					break;
				case "float":
					$postgreType = "real";
					break;
				case "boolean":
					$postgreType = "boolean";
					break;
				case "char":
				case "varchar":
					$postgreType = $type;
					break;
				case "tinytext":
				case "text":
				case "mediumtext":
				case "longtext":
					$postgreType = "text";
					break;
				case "binary":
				case "varbinary":
				case "tinyblob":
				case "mediumblob":
				case "longblob":
					$postgreType = "bitea";
					break;
				
			}
			$postgresql .= "\t\t\t\t\"\\\"".$fieldName."\\\" ".$postgreType.$otherParams.",\".\n";
		}
		$lex++;
		$first = false;
	}
	$mysqliFillTables .= ")\";\n\t\t\t}\n".
		"\t\t\tmysqli_query(\$storage['connect'], \$query);\n".
		"\t\t}\n";
	$postgresqlFillTables .= ")\";\n\t\t\t}\n".
		"\t\t\tpg_query(\$storage['connect'], \$query);\n".
		"\t\t\tif((\$result = pg_query(\$storage['connect'], \"SELECT \\\"id\\\" FROM \\\"\".\$storage['prefix'].\"".$tableName."\\\" ORDER BY \\\"id\\\" DESC LIMIT 1\")) !== false) {\n".
		"\t\t\t\tif(\$line = pg_fetch_assoc(\$result)) {\n".
		"\t\t\t\t\t\$line[\"id\"] = intval(\$line[\"id\"]) + 1;\n".
		"\t\t\t\t\tpg_query(\$storage['connect'], \"ALTER SEQUENCE \\\"\".\$storage['prefix'].\"".$tableName."_id_seq\\\" RESTART WITH \".\$line[\"id\"]);\n".
		"\t\t\t\t}\n".
		"\t\t\t}\n".
		"\t\t}\n";
	
	$lex += 2;
	$mysqli .= "\t\t\t\t\"PRIMARY KEY ".$contentList[$lex]."\".\n".
		"\t\t\t\") ENGINE=MyISAM DEFAULT CHARSET=utf8\"\n".
		"\t\t);\n";
	$postgresql .= "\t\t\t\t\"PRIMARY KEY ".preg_replace("/(`)/", "\\\"", $contentList[$lex])."\".\n".
		"\t\t\t\")\"\n".
		"\t\t);\n";
	$lex += 2;
}

$mysqliDumpTables .= "\t\treturn \$dump;\n".
	"\t}\n";
$postgresqlDumpTables .= "\t\treturn \$dump;\n".
	"\t}\n";

$mysqliFillTables .= "\t}\n";
$postgresqlFillTables .= "\t}\n";

$mysqli .= "\t}\n\n".$mysqliDumpTables."\n".$mysqliFillTables."\n}\n?>";
$postgresql .= "\t}\n\n".$postgresqlDumpTables."\n".$postgresqlFillTables."\n}\n?>";
unset($mysqliDumpTables);
unset($mysqliFillTables);
unset($postgresqlDumpTables);
unset($postgresqlFillTables);
unset($content);
unset($contentList);

if(isset($argv[4]) && isset($argv[5])) {
	$file = fopen($argv[4], "w");
	fwrite($file, $mysqli);
	fclose($file);
	$file = fopen($argv[5], "w");
	fwrite($file, $postgresql);
	fclose($file);
}
?>
