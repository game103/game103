/etc/init.d/cron stop
cd /var/www/game103
git reset --hard
git pull
git submodule foreach git pull origin master
find . -name "*.fla" -type f | xargs rm -f
find . -name "*.as" -type f|xargs rm -f
find . -name "*.ipa" -type f|xargs rm -f
find . -name "*.apk" -type f|xargs rm -f
find /var/www/game103/css -maxdepth 1 -iname "*.css" -exec uglifycss --compress --mangle -o {} -- {} \;
find /var/www/game103/javascript -maxdepth 1 -iname "*.js" -exec uglifyjs --compress --mangle -o {} -- {} \;
/etc/init.d/cron start
