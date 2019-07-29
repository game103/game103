# script to set up Game 103 on a fresh OS
# this script includes the installation of necessary files
# currently works on debian
# environment variables required

# install required modules
echo "Installing required modules..."
apt-get update
apt-get -y install git cron gcc php7.0 php-mysql php7.0-fpm python2.7 curl apache2 mysql-server bind9 imagemagick openssl
curl -sL https://deb.nodesource.com/setup_12.x | sh
apt-get -y install nodejs

# cd to the directory of game103 setup
echo "Cloning Game 103..."
git clone https://github.com/game103/game103
mv game103 /var/www/game103
cd /var/www/game103/setup

# enable necessary apache2 mods
echo "Enabling Apache modules..."
service apache2 stop
a2dismod php7.0 mpm_prefork # disable prefork to use fastcgi proxy instead
a2enmod core so watchdog http log_config logio version unixd access_compat alias auth_basic authn_code authn_file authz_core authz_groupfile authz_host authz_user autoindex cgi deflate dir env expires filter headers http2 mime negotiation proxy proxy_fcgi reqtimeout rewrite setenvif ssl status mpm_event
service php7.0-fpm start 
service apache2 start

# install js and css minifiers
echo "Installing minifiers..."
npm install -g uglify-js
npm install -g uglifycss

# load the submodules
echo "Loading submodules..."
git submodule update --init

# create the game103_private directory
echo "Create the private directory..."
cd ../..
mkdir game103_private
mkdir game103_private/cache
mkdir game103_private/modules
mkdir game103_private/stock
mkdir game103_private/stock/games
cp game103/modules/Constants.class.php.template game103_private/modules/Constants.class.php

# set up the defaults for mysql authentication
echo "Updating MySQL Defaults..."
sed -i 's/<mysql host>/localhost/g' game103_private/modules/Constants.class.php
sed -i 's/<mysql user>/root/g' game103_private/modules/Constants.class.php
sed -i 's/<mysql password>/cocoa/g' game103_private/modules/Constants.class.php

# fix MySQL user 
echo "Updating MySQL user..."
service mysql start
mysql -u root -f < /var/www/game103/setup/setup.sql

# start mysql and set the password
echo "Changing MySQL password..."
mysqladmin --user=root password "cocoa"

# make sure permissions are correct for directories that we need to upload to
echo "Changing admin directories' owner..."
chown -R www-data:www-data game103_private/cache-user
chown -R www-data:www-data game103_private/stock
chown -R www-data:www-data game103/images/icons

# copy the private files to game 103
echo "Copying private files..."
cp -R game103_private/* game103

# distribute the setup files
echo "Distributing setup files..."
/var/www/game103/setup/distribute.sh
mkdir -p /etc/apache2/conf.d/
echo "Restarting services..."
service apache2 restart
service bind9 restart
service php7.0-fpm restart
service mysql restart

# generate the navbar
echo "Generating the navbar..."
cd game103
php scripts/generate_navbar.php > navbar.html

# install instagram poster
echo "Installing Instagram poster..."
cd scripts/instagram-poster
npm install

# confirm apache configuration
echo "Confirming Apache Configuration..."
rm /etc/apache2/sites-available/000-default.conf 
rm /etc/apache2/sites-available/default-ssl.conf
rm /etc/apache2/sites-enabled/000-default.conf
ln -s ../sites-available/default default

# remove ssl certificate
echo "Remove default SSL configuration..."
sed -i 's/Include.*//g' /etc/apache2/sites-available/default
sed -i 's/SSLCertificateFile.*/SSLCertificateFile \/etc\/apache2\/ssl\/localhost.crt/g' /etc/apache2/sites-available/default
sed -i 's/SSLCertificateKeyFile.*/SSLCertificateKeyFile \/etc\/apache2\/ssl\/localhost.key/g' /etc/apache2/sites-available/default

# install test ssl certificate 
echo "Generating development SSL certificate..."
mkdir /etc/apache2/ssl
cd /etc/apache2/ssl
openssl req -x509 -out /etc/apache2/ssl/localhost.crt -keyout localhost.key \
  -newkey rsa:2048 -nodes -sha256 \
  -subj '/CN=localhost' -extensions EXT -config <( \
   printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

echo "Restarting services..."
service apache2 restart
service bind9 restart
service php7.0-fpm restart
service mysql restart