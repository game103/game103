/etc/init.d/cron stop
cd /var/www/game103
git pull
git submodule foreach git pull origin master
find . -name "*.fla" -type f | xargs rm -f
find . -name "*.as" -type f|xargs rm -f
find . -name "*.ipa" -type f|xargs rm -f
find . -name "*.apk" -type f|xargs rm -f
find /var/www/game103/css ! -name "*.min.css" -not -path '/var/www/game103/css' -exec sh -c 'uglifycss {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min.css'"'"')' \;
find /var/www/game103/javascript ! -name "*.min.js" -not -path '/var/www/game103/javascript' -exec sh -c 'uglifyjs {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min.js'"'"')' \;
/etc/init.d/cron start
