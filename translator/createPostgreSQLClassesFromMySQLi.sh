#!/bin/bash
find ../ -name 'mysqli.php' -exec sh -c './checkPostgreSQLOfMySQLi.sh $0' {} \;
