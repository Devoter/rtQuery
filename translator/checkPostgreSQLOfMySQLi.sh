#!/bin/bash
POSTGRESFILE=`echo $1 | sed -e 's/mysqli.php$/postgresql.php/ig'`
INCLASSES=`expr match "$POSTGRESFILE" ".*\(classes\)"`
if [ "${INCLASSES}" == "classes" ]; then
	CONTENT="`sed -e 's/mysqli/postgresql/ig' $1 | sed -e 's/MySQL/PostgreSQL/ig'`"
	echo "${CONTENT}" > $POSTGRESFILE
fi
