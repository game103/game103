P=`php -r "include '/var/www/game103/modules/Constants.class.php'; echo Constants::DB_PASSWORD;"`
mysqldump -u root -p$P --all-databases > /home/james/db_backup.sql
DATE=`date +%Y-%m-%d`
scp /home/james/db_backup.sql james@backup.game103.net:/home/james/backup/mysql/
