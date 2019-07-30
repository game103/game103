# Source Code for Game 103
This repository is the source code for game103.net. Game 103 creates and hosts family-friendly games and entertainment.
## Development
The easiest way to set up Game 103 for development is to use the [Game 103 Development](https://github.com/game103/game103development) repository.
## Running the Site without the Development Repository
1. Clone this repo
2. `cd` into the directory for this repo
3. `git submodule update --init`
4. `cp modules/Constants.class.php.template modules/Constants.class.php`
5. Fill in the passwords in `modules/Constants.class.php`
6. `php scripts/generate_navbar.php > navbar.html` or `.\scripts\generate_navbar.php |Out-File -Encoding ascii .\navbar.html`
7. Start a web server in this repo (e.g. Apache, the PHP build-in test server seems to work too)