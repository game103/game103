<html>
<head>
	<title>Survey of Arabic Sentences</title>
</head>
<body>

<h1>Survey of Arabic Sentences</h1>

<?php

	$submit=$_POST['submit'];

	if($submit) {
		
		$gender= $_POST['gender'];
		$age= $_POST['age'];
		$lived_history= $_POST['lived_history'];
		$picture = $_POST['picture'];
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$target_dir = "./uploads/";
		
		$filename = $_FILES['ali_airplane_upload']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$ali_airplane_target_file = $target_dir . 'ali_airplane_' . uniqid() . '.' . $ext;
		
		$filename = $_FILES['ali_omar_upload']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$ali_omar_target_file = $target_dir . 'ali_omar_' . uniqid() . '.' . $ext;
		
		$filename = $_FILES['ali_sara_upload']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$ali_sara_target_file = $target_dir . 'ali_sara_' . uniqid() . '.' . $ext;
		
		$filename = $_FILES['amr_diab_upload']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$amr_diab_target_file = $target_dir . 'amr_diab_' . uniqid() . '.' . $ext;
		
		$filename = $_FILES['computer_upload']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$computer_target_file = $target_dir . 'computer_' . uniqid() . '.' . $ext;
		
		//move the files to the correct location
		//if the upload is successful that is the url
		if(move_uploaded_file($_FILES["ali_airplane_upload"]["tmp_name"],$ali_airplane_target_file)) {
			$ali_airplane_url = 'https://game103.net/forms' . ltrim($ali_airplane_target_file,'.');
		}
		if(move_uploaded_file($_FILES["ali_omar_upload"]["tmp_name"],$ali_omar_target_file)) {
			$ali_omar_url = 'https://game103.net/forms' . ltrim($ali_omar_target_file,'.');
		}
		if(move_uploaded_file($_FILES["ali_sara_upload"]["tmp_name"],$ali_sara_target_file)) {
			$ali_sara_url = 'https://game103.net/forms' . ltrim($ali_sara_target_file,'.');
		}
		if(move_uploaded_file($_FILES["amr_diab_upload"]["tmp_name"],$amr_diab_target_file)) {
			$amr_diab_url = 'https://game103.net/forms' . ltrim($amr_diab_target_file,'.');
		}
		if(move_uploaded_file($_FILES["computer_upload"]["tmp_name"],$computer_target_file)) {
			$computer_url = 'https://game103.net/forms' . ltrim($computer_target_file,'.');
		}
		
		$message = "
<html>
	<body>
		Hi sweet Kasey,<br><br>
		There are some results from the survey.<br>
		Here they are:<br><br>
		Gender: $gender <br><br>
		Age: $age <br><br>
		Geographical History: $lived_history <br><br>
		Ali Airplane question: <a href='$ali_airplane_url'>$ali_airplane_url</a> <br><br>
		Ali Omar question: <a href='$ali_omar_url'>$ali_omar_url</a> <br><br>
		Ali Sara question: <a href='$ali_sara_url'>$ali_sara_url</a> <br><br>
		Amr Diab question: <a href='$amr_diab_url'>$amr_diab_url</a> <br><br>
		Computer question: <a href='$computer_url'>$computer_url</a> <br><br>
		Well, that's it.<br>
		P.S. I love you,<br>
		James
	</body>
</html>
";
		
		$to = "kasey.mann@gordon.edu";
		$subject = "Arabic survey from $ip";
		$headers = "From: james@game103.net\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
		$send=mail($to,$subject,$message,$headers);
		
		echo <<<EOD
	<h2>Thank you</h2>
	<p>Your responses have been successfully recorded.</p>
EOD;
	}
	else {
		echo <<<EOD
	
<form action = "arabicsurvey.php" method = "POST" enctype = "multipart/form-data">

<div class='questions'>
	<h2>Basic Questions</h2>
	What is your gender?
	<br><label for='male'>Male:</label><input type='radio' id='male' name='gender' value='male' required=1/>
	<br><label for='male'>Female:</label><input id='female' type='radio' name='gender' value='female'/>
	<br><br>What is your age?
	<br><input type='text' name='age' id='age' required=1/>
	<br><br>Where have you lived and at what ages?
	<br><textarea name='lived_history' id='lived_history' required=1></textarea>
</div>
<div class='questions'>
	<h2>Survey</h2>
	Sentence 1: <i>Ali saw an airplane above himself.</i>
	<br><label for='ali_airplane_upload'>Audio translation: </label><input required=1 type = "file" name = "ali_airplane_upload" id = "ali_airplane_upload">
	<br><br>Sentence 2: <i>Ali gave Omar a picture of himself.</i>
	<br><label for='ali_omar_upload'>Audio translation: </label><input required=1 type = "file" name = "ali_omar_upload" id = "ali_omar_upload">
	<br><br>In the Arabic translation of sentence 2, who is the picture of?
	<br><label for='ali'>Ali:</label><input type='radio' id='ali' name='picture' value='ali' required=1/>
	<br><label for='omar'>Omar:</label><input id='omar' type='radio' name='picture' value='omar'/>
	<br><label for='either'>Either:</label><input id='either' type='radio' name='picture' value='either'/>
	<br><br>Sentence 3: <i>Ali was excited that Sara invited Omar and himself over for a drink.</i>
	<br><label for='ali_sara_upload'>Audio translation: </label><input required=1 type = "file" name = "ali_sara_upload" id = "ali_sara_upload">
	<br><br>For the next sentence, assume that Amr Diab is
	at a wax museum along with a wax figurine of himself.
	<br>Sentence 4: <i>Amr Diab washed himself carefully so as not to damage the wax.</i>
	<br><label for='amr_diab_upload'>Audio translation: </label><input required=1 type = "file" name = "amr_diab_upload" id = "amr_diab_upload">
	<br><br>Sentence 5: <i>The computer restarted itself.</i>
	<br><label for='computer_upload'>Audio translation: </label><input required=1 type = "file" name = "computer_upload" id = "computer_upload">
</div>

<br><input type='submit' value='Submit' name='submit'/>

</form>
	
EOD;
	}

	?>

</body>
</html>