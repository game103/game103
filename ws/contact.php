<?php
    error_reporting(0);
    
    set_include_path($_SERVER['DOCUMENT_ROOT']  . "/" . "modules");

    // Require modules
    require_once( 'Constants.class.php');
	
    $email = strip_tags($_POST['email']);
    $valid_domains = array(
        "game103.net",
        "evenlode.dev",
        "makingdorecipes.com",
        "smartsteplavs.com",
        "guystation.net",
        "jamesgrams.com"
    );
    $http_origin = $_SERVER['HTTP_ORIGIN'];
    $http_domain = parse_url($http_origin)['host'];
    $domain = array_pop(explode('@', $email));
    $status = "failure";
    if( $domain == $http_domain && in_array($domain, $valid_domains) ) {
        header("Access-Control-Allow-Origin: $http_origin");
        $to_email = $email;
        $subject = $_POST['subject'];
        $headers = "From: james@game103.net\r\n";
        $headers .= "Reply-To: james@game103.net\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $message = $_POST['body'];
        $result = mail($to_email,$subject,$message,$headers);
        if( $result ) $status = "success";
    }
    
    // We will always show success, even if no ID
    // so people can't check if emails were successful or not
    echo json_encode ( array(
        'status'   => $status
    ) );
?>
