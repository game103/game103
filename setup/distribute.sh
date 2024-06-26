# distribute the files in the setup directory to their proper locations on the server
# note: this script will not reboot services.

/etc/init.d/cron stop

cd /var/www/game103/setup

# set up the crontab
cp crontab /var/spool/cron/crontabs/root

# load mysql
# this should be idempotent
P=`php -r "include '/var/www/game103/modules/Constants.class.php'; echo Constants::DB_PASSWORD;"`
mysql -u root -p$P -f < schema.sql

# load named.conf.local (the zone files will be generated by ip_updater.sh)
cp named.conf.local /etc/bind/

# load apache config files
cp apache2.conf /etc/apache2/
cp default /etc/apache2/sites-available/

# load php configuration files
# you may need to enable the device on google security settings to send mail
cp php.ini /etc/php/7.0/fpm/php.ini
cp www.conf /etc/php/7.0/fpm/pool.d/www.conf

cd /var/www/game103

/etc/init.d/cron start
