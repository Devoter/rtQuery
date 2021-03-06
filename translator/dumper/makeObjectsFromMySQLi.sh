#!/bin/bash
php -f mysqlidumper.php admin admin.sql 32 "../../modules/admin/install/mysqli.php" "../../modules/admin/install/postgresql.php"
php -f mysqlidumper.php appointment appointment.sql 2 "../../modules/appointment/install/mysqli.php" "../../modules/appointment/install/postgresql.php"
php -f mysqlidumper.php contacts contacts.sql 2 "../../modules/contacts/install/mysqli.php" "../../modules/contacts/install/postgresql.php"
php -f mysqlidumper.php counter counter.sql 3 "../../modules/counter/install/mysqli.php" "../../modules/counter/install/postgresql.php"
php -f mysqlidumper.php image image.sql 2 "../../modules/image/install/mysqli.php" "../../modules/image/install/postgresql.php"
php -f mysqlidumper.php menu menu.sql 2 "../../modules/menu/install/mysqli.php" "../../modules/menu/install/postgresql.php"
php -f mysqlidumper.php page page.sql 1 "../../modules/page/install/mysqli.php" "../../modules/page/install/postgresql.php"
php -f mysqlidumper.php search search.sql 4 "../../modules/search/install/mysqli.php" "../../modules/search/install/postgresql.php"
php -f mysqlidumper.php skype skype.sql 2 "../../modules/skype/install/mysqli.php" "../../modules/skype/install/postgresql.php"
php -f mysqlidumper.php standard standard.sql 1 "../../modules/standard/install/mysqli.php" "../../modules/standard/install/postgresql.php"
php -f mysqlidumper.php table table.sql 2 "../../modules/table/install/mysqli.php" "../../modules/table/install/postgresql.php"
