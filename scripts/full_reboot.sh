etc/init.d/cron stop
cd /var/www/
git clone https://github.com/game103/game103.git game103_new
cd game103_new
git submodule update --init --recursive
find . -name "*.fla" -type f | xargs rm -f
find . -name "*.as" -type f|xargs rm -f
find . -name "*.ipa" -type f|xargs rm -f
find . -name "*.apk" -type f|xargs rm -f
cd ..
cp -R game103_private/* game103_new
find /var/www/game103_new/css ! -name "*.min.css" -not -path '/var/www/game103_new/css' -exec sh -c 'uglifycss {} > $(echo {} | cut -f 1 -d "." | xargs -L1 -I '"'"'$'"'"' echo '"'"'$.min'"'"')' \;
cd /var/www/game103_new/css
rename -f "s/min/css/" *.min
cd ../
find /var/www/game103_new/javascript -maxdepth 1 -iname "*.js" -exec uglifyjs --compress --mangle -o {} -- {} \;
mv game103_new game103
python /var/www/game103/scripts/cacher.py
/var/www/game103/scripts/border_maker.sh
/etc/init.d/cron start
