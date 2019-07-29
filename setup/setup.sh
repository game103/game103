# script to set up Game 103 on a fresh OS
# this script includes the installation of necessary files
# currently works on debian

# install required modules
echo "GAME 103: INSTALLING REQUIRED MODULES"
apt-get update
apt-get -y install git cron gcc php7.0 php-mysql php7.0-fpm python2.7 curl apache2 mysql-server bind9 imagemagick certbot python-certbot-apache
curl -sL https://deb.nodesource.com/setup_12.x | sh
apt-get install nodejs

# cd to the directory of game103 setup
git clone https://github.com/game103/game103
mv game103 /var/www/game103
cd /var/www/game103/setup

# enable necessary apache2 mods
echo "GAME 103: ENABLING APACHE MODULES"
a2enmod core so watchdog http log_config logio version unixd access_compat alias auth_basic authn_code authn_file authz_core authz_groupfile authz_host authz_user autoindex cgi deflate dir env expires filter headers http2 mime mpm_event negotiation proxy proxy_fcgi req_timeout rewrite setenvif ssl status fastcgi

# install js and css minifiers
echo "GAME 103: INSTALLING MINIFIERS"
npm install -g uglify-js
npm install -g uglifycss

# disribute the setup files
/var/www/game103/setup/distribute.sh

# load the submodules
git submodule update --init

# create the game103_private directory
cd ..
mkdir game103_private
mkdir game103_private/cache
mkdir game103_private/modules
mkdir game103_private/stock
mkdir game103_private/stock/games
cp game103/modules/Constants.class.php.template game103_private/modules/Constants.class.php

# set up the defaults for mysql authentication
sed 's/<mysql host>/localhost/g' game103_private/modules/Constants.class.php
sed 's/<mysql user>/root/g' game103_private/modules/Constants.class.php
sed 's/<mysql password>//g' game103_private/modules/Constants.class.php 

# make sure permissions are correct for directories that we need to upload to
chown -R www-data:www-data game103_private/cache-user
chown -R www-data:www-data game103_private/stock
chown -R www-data:www-data game103/images/icons

# copy the private files to game 103
cp -R game103_private/* game103

# generate the navbar
cd game103
php scripts/generate_navbar.php > navbar.html

# install instagram poster
cd scripts/instagram-poster
npm install

# restart everything
service apache2 restart
service php7.0-fpm restart
service bind9 restart