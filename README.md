# Source Code for Game 103
This repository is the source code for game103.net.
## Running the site
1. Clone this repo
2. `cd` into the directory for this repo
3. `git submodule update --init`
4. `cp modules/Constants.class.php.template modules/Constants.class.php`
5. Fill in the passwords in `modules/Constants.class.php`
6. `php scripts/generate_navbar.php > navbar.html` or `.\scripts\generate_navbar.php |Out-File -Encoding ascii .\navbar.html`
7. Start a web server in this repo (e.g. Apache, the PHP build-in test server seems to work too)
