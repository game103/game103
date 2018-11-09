/etc/init.d/cron stop
cd /var/www/game103
git reset --hard
git pull
git submodule foreach git pull origin master
find . -name "*.fla" -type f | xargs rm -f
find . -name "*.as" -type f|xargs rm -f
find . -name "*.ipa" -type f|xargs rm -f
find . -name "*.apk" -type f|xargs rm -f
find /var/www/game103/css ! -name "*.min.css" -not -path '/var/www/game103/css' -exec sh -c 'uglifycss {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min'"'"')' \;
cd /var/www/game103/css
rename -f "s/min/css/" *.min
cd ../
find /var/www/game103/javascript -maxdepth 1 -iname "*.js" -exec uglifyjs --compress --mangle -o {} -- {} \;
/etc/init.d/cron start
