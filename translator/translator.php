<?php
namespace Rt\Translator
{
	/**
	 * 
	 * Функция выполняет последовательный перенос базы
	 * данных из одной СУБД($from) в другую ($to)
	 * @param array $from СУБД - источник
	 * @param array $to СУБД - приемник
	 */
	function translate(array &$from, array &$to)
	{
		$dirList = scandir("modules");
		for($i = 0; $i < sizeof($dirList); $i++) {
			if(is_dir("modules/".$dirList[$i]) && ($dirList[$i] != ".") && ($dirList[$i] != "..")) {
				include_once "modules/".$dirList[$i]."/install/".$from['type'].".php";
				$dump = array();
				eval("\$dump = \\Rt\\Install\\".$from['canonical']."\\".mb_strtoupper($dirList[$i][0], "UTF-8").mb_substr($dirList[$i], 1, mb_strlen($dirList[$i], "UTF-8"), "UTF-8")."\\dumpTables(\$from);");
				include_once "modules/".$dirList[$i]."/install/".$to['type'].".php";
				eval("\\Rt\\Install\\".$to['canonical']."\\".mb_strtoupper($dirList[$i][0], "UTF-8").mb_substr($dirList[$i], 1, mb_strlen($dirList[$i], "UTF-8"), "UTF-8")."\\createTables(\$to);");
				eval("\\Rt\\Install\\".$to['canonical']."\\".mb_strtoupper($dirList[$i][0], "UTF-8").mb_substr($dirList[$i], 1, mb_strlen($dirList[$i], "UTF-8"), "UTF-8")."\\fillTables(\$to, \$dump);");
			}
		}
	}
}
?>