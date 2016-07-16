#!/bin/bash
MYSQLFILE=`echo $1 | sed -e 's/postgresql.php$/mysqli.php/ig'`
INCLASSES=`expr match "$MYSQLFILE" ".*\(classes\)"`
if [ "${INCLASSES}" == "classes" ]; then
	CONTENT="`sed -e 's/postgresql/mysqli/ig' $1 | sed -e 's/PostgreSQL/MySQL/ig'`"
	echo "${CONTENT}" > $MYSQLFILE
fi
