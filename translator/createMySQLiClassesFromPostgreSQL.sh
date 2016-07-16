#!/bin/bash
find ../ -name 'postgresql.php' -exec sh -c './checkMySQLiOfPostgreSQL.sh $0' {} \;
