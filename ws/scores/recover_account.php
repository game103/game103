<?php
	error_reporting(0);
	
	$email = strip_tags($_POST['email']);
	
	set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");
	
	// Require modules
	require_once( 'Constants.class.php');
		
	$mysqli = new mysqli(Constants::DB_HOST, Constants::DB_USER, Constants::DB_PASSWORD, "hallaby_scores");
	
	$select_str = "SELECT id, username FROM users where email = ?";
		
	$statement = $mysqli->prepare($select_str);
	$statement->bind_param("s", $email);
	$statement->execute();
	$statement->bind_result($id, $username);
	$statement->fetch();
    
	if( $id ) {
        $to_email = $email;
        $subject = "Game 103 Account Recovery";
        $headers = "From: james@game103.net\r\n";
        $headers .= "Reply-To: james@game103.net\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        // key += days since 1970 + 1
        // encrypt id with key
        // when decrypting > check for both the current day and the day + 1
        // this will allow a window of 24-48 hours to reset the password
        // the chances of a bad one accidently matching another one are miniscule.
        // they are the same as having an expired token match a live one
        // See Account.class.php for decryption
        date_default_timezone_set('America/New_York');
        $cipher_method = 'aes-128-ctr';
        $key_string = \Constants::ENCRYPTION_KEY;
        $days_since_epoch = (new DateTime())->diff(new DateTime('0001-01-01'))->format('%a');
        $key_string .= ($days_since_epoch + 1);
        $enc_key = openssl_digest($key_string, 'SHA256', TRUE);
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        $crypted_token = bin2hex(openssl_encrypt($id, $cipher_method, $enc_key, 0, $enc_iv)) . "_" . bin2hex($enc_iv);
        $link = "https://game103.net/account?token=" . $crypted_token;

        $message = <<<HTML
<html>
    <body style="padding: 10px;font-family:sans-serif;">
    <img style="padding:5px;width:200px;margin-left:auto;margin-right:auto;display:block;border:5px solid #285cae;" src="https://game103.net/images/logo2016.png"/>
    Dear $username,<br><br>
    We have received a request for you to reset your Game 103 password. Please click the link below to do so. If you did not request this, please ignore this message.<br><br>
    <a href="$link">$link</a><br><br>
    This link will expire in 24-48 hours<br><br>
    Sincerely,<br><br>
    Game 103
    </body>
</html>
HTML;

        mail($to_email,$subject,$message,[$headers],[]);
    }
    
    // We will always show success, even if no ID
    // so people can't check if emails were successful or not
    echo json_encode ( array(
        'status'   => 'success'
    ) );
	
	$mysqli->close();
?>