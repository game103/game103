mysqldump -u root -pYellowTang704@ndPups --all-databases > /home/james/db_backup.sql
DATE=`date +%Y-%m-%d`
sshpass -p "YellowTang704@ndPups" scp /home/james/db_backup.sql hallaby@129.121.31.190:~/db_backups/db_backup_$DATE.sql
