mysqldump -u root -p***REMOVED*** --all-databases > /home/james/db_backup.sql
DATE=`date +%Y-%m-%d`
sshpass -p "***REMOVED***" scp /home/james/db_backup.sql hallaby@129.121.31.190:~/db_backups/db_backup_$DATE.sql
