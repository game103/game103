# update the files in the setup directory based on the current server configuration,
# includes
# php configuration
# bind configuration
# apache configuration
# mysql schema

# you should commit changes to git after running this script

# cd to the directory of game103 setup
cd /var/www/game103/setup

# get the php configuration
cp /etc/php/7.0/fpm/php.ini php.ini
cp /etc/php/7.0/fpm/pool.d/www.conf www.conf

# get the bind configuration
cp /etc/bind/named.conf.local named.conf.local

# get the crontab
cp /var/spool/cron/crontabs/root crontab

# get the apache configuration
cp /etc/apache2/apache2.conf apache2.conf
cp /etc/apache2/sites-available/default default

# get the mysql
P=`php -r "include '/var/www/game103/modules/Constants.class.php'; echo Constants::DB_PASSWORD;"`
mysqldump -d -u root -p$P --all-databases --skip-dump-date --skip-comments --skip-add-drop-table > schema.sql

PHP_DIFF=$(git diff php.ini) 
BIND_DIFF=$(git diff named.conf.local) 
CRONTAB_DIFF=$(git diff crontab) 
APACHE_DIFF=$(git diff apache2.conf) 
DEFAULT_DIFF=$(git diff default) 
SCHEMA_DIFF=$(git diff schema.sql) 

if [ "$PHP_DIFF" != "" ]
then
    echo "php.ini was modified."
fi
if [ "$BIND_DIFF" != "" ]
then
    echo "named.conf.local was modified."
fi
if [ "$CRONTAB_DIFF" != "" ]
then
    echo "crontab was modified."
fi
if [ "$APACHE_DIFF" != "" ]
then
    echo "apache2.conf was modified."
fi
if [ "$DEFAULT_DIFF" != "" ]
then
    echo "default was modified."
fi
if [ "$SCHEMA_DIFF" != "" ]
then
    echo "schema.sql was modified."
fi