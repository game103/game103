/etc/init.d/crond/stop
cd /var/www/game103
git pull
git submodule update --init --recursive
find . -name "*.fla" -type f | xargs rm -f
find . -name "*.as" -type f|xargs rm -f
find . -name "*.ipa" -type f|xargs rm -f
find . -name "*.apk" -type f|xargs rm -f
find /var/www/game103_new/css ! -name "*.min.css" -not -path '/var/www/game103_new/css' -exec sh -c 'uglifycss {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min.css'"'"')' \;
find /var/www/game103_new/js ! -name "*.min.js" -not -path '/var/www/game103_new/js' -exec sh -c 'uglifyjs {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min.css'"'"')' \;
/etc/init.d/crond/start
