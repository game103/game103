# Source Code for Game 103
This repository is the source code for game103.net. Game 103 creates and hosts family-friendly games and entertainment.
## Running the Site
1. Clone this repo
2. `cd` into the directory for this repo
3. `git submodule update --init`
4. `cp modules/Constants.class.php.template modules/Constants.class.php`
5. Fill in the passwords in `modules/Constants.class.php`
6. `php scripts/generate_navbar.php > navbar.html` or `.\scripts\generate_navbar.php |Out-File -Encoding ascii .\navbar.html`
7. Start a web server in this repo (e.g. Apache, the PHP build-in test server seems to work too)
## Detailed Installation Instructions
With a fresh box, you will need to do the following:
1. Make sure git is installed
2. Make sure cron is installed
3. Install PHP7 (Confirmed working on v. PHP 7.0.33)
4. Install php7.0-fpm 
5. Install Python 2.7 (Confirmed working on v. 2.7.13 - make sure it is aliased as python)
6. Install Node (Confirmed working on v. 11.1.0)
7. Install npm (Confirmed working on v. 6.4.1) and the following modules:
  * `npm install -g uglify-js`
  * `npm install -g uglifycss`
8. Install Apache2 (Confirmed working on v. 2.4.25) and enable the following modules:
  * `a2enmod core so watchdog http log_config logio version unixd access_compat alias auth_basic authn_code authn_file authz_core authz_groupfile authz_host authz_user autoindex cgi deflate dir env expires filter headers http2 mime mpm_event negotiation proxy proxy_fcgi req_timeout rewrite setenvif ssl status fastcgi`
9. Install MySQL/MariaDB (Confirmed working on V. 15.1 Distrib 10.1.38-MariaDB)
10. Install BIND (Confirmed working on v. 9.10.3)
11. Install ImageMagick (Confirmed working on v. 6.9.7-4)
12. Install certbot (Confirmed working on v. 0.28.0)
13. Clone this repository
14. Place it in your cgi directory (e.g. `/var/www/`)
15. Setup cron (More to come)
16. Setup the MySQL schema (More to come)
17. Setup the BIND zone files and values (More to come)
18. Setup the apache2 configuration (More to come)
19. Setup the fpm php.ini appriately, including to send to gmail's servers (More to come)
20. `cd` into the directory for this repo
21. Run `git submodule update --init`
22. Run `cp modules/Constants.class.php.template modules/Constants.class.php`
23. Fill in the passwords in `modules/Constants.class.php`
24. Run `php scripts/generate_navbar.php > navbar.html` or `.\scripts\generate_navbar.php |Out-File -Encoding ascii .\navbar.html`
25. Create a `game103_private` directory in the same directory the repo lives. This directory will contain the files/directories that are not in the repo. The reboot script will merge this directory with the repo. This directory should have the following file structure:
  * `cache`
  * `cache-user`
  * `modules`
    * `Constants.class.php` - optionally include your filled out version of the Constants file here.
  * `stock`
    * `games`
      * `flash` - optionally place any swfs in this directory, so they will be available on full reboot
26. Optionally, run `/site-dir/scripts/full_reboot.sh` - this will minify css and js, generate webp images, bordered images for social media, and cached pages. You may not want to do this in development. 
  * If you only want webp images, just run `scripts/web_maper.sh`
27. Visit `/site-dir/scripts/instagram-poster/` and run `npm install`
28. You may also want to make sure certbot has fetched an ssl certificate for you
29. Start apache
