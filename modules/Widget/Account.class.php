<?php
	namespace Widget;

    require_once('Constants.class.php');
	require_once('Widget.class.php');

	/**
    *	Widget representing the account page.
    *   Unlike a lot of pages on Game 103, this page makes heavy use of
    *   JavaScript and ajax. The reason for this is that we already have
    *   pre-existing web services
	*/
	class Account extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
            \Widget::__construct( $properties );
            $this->CSS[] = '/css/admin.css';
            $this->CSS[] = '/css/account.css';
            $this->JS[] = '/javascript/account.js';
		}
		
		/**
		* Generate Content
		*/
		public function generate() {

            $id_section = "";

            // Get the two possible ids from the token and place them on the page
            // for the js to take care of finding the user
            if( $_GET['token'] ) {
                // See recover_account.php for encryption
                list($crypted_token, $enc_iv) = explode("_", $_GET['token']);;
                $cipher_method = 'aes-128-ctr';
                $days_since_epoch = (new \DateTime())->diff(new \DateTime('0001-01-01'))->format('%a');
                $key_string = \Constants::ENCRYPTION_KEY . $days_since_epoch;
                $enc_key = openssl_digest($key_string, 'SHA256', TRUE);
                $id = openssl_decrypt(hex2bin($crypted_token), $cipher_method, $enc_key, 0, hex2bin($enc_iv));
                $id_section = "<input type='hidden' name='id' id='id' value='$id'/>";
                $key_string = \Constants::ENCRYPTION_KEY . ($days_since_epoch + 1);
                $enc_key = openssl_digest($key_string, 'SHA256', TRUE);
                $id = openssl_decrypt(hex2bin($crypted_token), $cipher_method, $enc_key, 0, hex2bin($enc_iv));
                $id_section .= "<input type='hidden' name='id2' id='id2' value='$id'/>";
            }
            
            $html = <<<HTML
<div class="account-description">Welcome to the Game 103 account management page. Several of our games use 
accounts for high scores. Your account is automatically created for you when you play these games and is used
to associate you with your high scores. In these games, you have the option to change your username and create a password, so that you can log into 
your account from other devices and games.<br><br>On this page, you can change your account username and password, set an email so that you can recover your account
should you forget your password, and recover your account if you have forgetten your password and added an email.</div>
<div class="admin login">
    <div class="admin-error-message"></div>
    <label for="username"><span class='admin-label-text'>Username: </span><input required id="username" type = "text" name = "username" minlength="5" maxlength="15"></label>
    <label for="password"><span class='admin-label-text'>Password: </span><input required id="password" type = "password" name = "password" minlength="5" maxlength="15"></label>
    $id_section
    <input id='login' type = "submit" value = "Login" name = "submit" class="button">
    <label for="email"><span class='admin-label-text'>Email: </span><input required id="email" type = "email" name = "email" maxlength="300"></label>
    <input id='logout' type = "submit" value = "Logout" name = "submit" class="button">
    <input id='update' type = "submit" value = "Update Account" name = "submit" class="button">
    <input id='recover' type = "submit" value = "Recover Account" name = "submit" class="button">
    <div class='clear'></div>
</div>
HTML;
            
            // Place the HTML in a box
            $box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
                'title'			=> "Account",
                "tight"         => 1
			) );
            $box->generate();

            $this->HTML = $box->get_HTML();
            $this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
        }
		
	}

?>
