<?php
	$mysqli = new mysqli("game103.net", "hallaby", "***REMOVED***", "hallaby_games");
	//////////////
	// CONTROLS //
	//////////////
	// Get the controls
	$controls_str = "SELECT controls.id, controls.key FROM controls ORDER BY controls.key";
	$controls_statement = $mysqli->prepare($controls_str);
	$controls_statement->execute();
	$controls_statement->bind_result($controls_id, $controls_key);
	$controls_ids_arr = array();
	$controls_keys_arr = array();
	while($controls_statement->fetch()) {
		$controls_ids_arr[] = $controls_id;
		$controls_keys_arr[] = "'" . $controls_key . "'";
	}
	$controls_statement->close();
	
	
	$actions_str = "SELECT id, name FROM actions ORDER BY name";
	$actions_statement = $mysqli->prepare($actions_str);
	$actions_statement->execute();
	$actions_statement->bind_result($actions_id, $actions_key);
	$actions_ids_arr = array();
	$actions_names_arr = array();
	while($actions_statement->fetch()) {
		$actions_ids_arr[] = $actions_id;
		$actions_names_arr[] = "'" . $actions_key . "'";
	}
	
	$controls_ids = '[' . implode(',',$controls_ids_arr) . ']';
	$controls_keys = '[' . implode(',',$controls_keys_arr) . ']';
	$actions_ids = '[' . implode(',',$actions_ids_arr) . ']';
	$actions_names = '[' . implode(',',$actions_names_arr) . ']';
	
	$actions_statement->close();
?>

<html>
<head>
<script>
	var controlCount = 0;
	var controlsIds = <?php echo $controls_ids; ?>;
	var controlsKeys = <?php echo $controls_keys; ?>;
	var actionsIds = <?php echo $actions_ids; ?>;
	var actionsNames = <?php echo $actions_names; ?>;
	function addControl(event) {
		event.preventDefault();
		var br = document.createElement("br");
		br.setAttribute('id', 'br_' + controlCount);
		document.getElementById('controls_area').appendChild(br);
		document.getElementById('controls_area').appendChild(createNewKeyList());
		document.getElementById('controls_area').appendChild(createNewActionList());
		controlCount ++;
		document.getElementById('remove_control').style.display = 'inline-block';
	}
	function removeControl(event) {
		event.preventDefault();
		var keyList = document.getElementById('key_' + (controlCount - 1));
		var actionList = document.getElementById('action_' + (controlCount - 1));
		var br = document.getElementById('br_' + (controlCount - 1));
		document.getElementById('controls_area').removeChild(keyList);
		document.getElementById('controls_area').removeChild(actionList);
		document.getElementById('controls_area').removeChild(br);
		controlCount --;
		if(controlCount == 0) {
			document.getElementById('remove_control').style.display = 'none';
		}
	}
	function createNewKeyList() {
		var select = document.createElement("select");
		select.setAttribute('name', 'key_' + controlCount);
		select.setAttribute('id', 'key_' + controlCount);
		select.setAttribute('class', 'controls_list');
		for(var i = 0; i < controlsKeys.length; i++) {
			var key = controlsKeys[i];
			var option = document.createElement("option");
			option.text = key;
			option.value = controlsIds[i];
			select.appendChild(option);
		}
		return select;
	}
	function createNewActionList() {
		var select = document.createElement("select");
		select.setAttribute('name', 'action_' + controlCount);
		select.setAttribute('id', 'action_' + controlCount);
		select.setAttribute('class', 'actions_list');
		for(var i = 0; i < actionsNames.length; i++) {
			var action = actionsNames[i];
			var option = document.createElement("option");
			option.text = action;
			option.value = actionsIds[i];
			select.appendChild(option);
		}
		return select;
	}
	function addAControl(event) {
		event.preventDefault();
		var controlValue = document.getElementById('control_field').value;
		if(controlValue && !controlsKeys.includes(controlValue)) {
			var controlsLists = document.getElementsByClassName('controls_list');
			controlsKeys.push(controlValue);
			controlsIds.push(controlValue);
			for(var i = 0; i < controlsLists.length; i ++) {
				var option = document.createElement("option");
				option.text =controlValue;
				// In the php, you can check to see if value isn't a number. if its not, insert it.
				option.value = controlValue;
				controlsLists[i].add(option);
			}
		}
		document.getElementById('control_field').value = '';
	}
	function addAnAction(event) {
		event.preventDefault();
		var actionValue = document.getElementById('action_field').value;
		if(actionValue && !actionsNames.includes(actionValue)) {
			var actionsLists = document.getElementsByClassName('actions_list');
			actionsNames.push(actionValue);
			actionsIds.push(actionValue);
			for(var i = 0; i < actionsLists.length; i ++) {
				var option = document.createElement("option");
				option.text =actionValue;
				// In the php, you can check to see if value isn't a number. if its not, insert it.
				option.value = actionValue;
				actionsLists[i].add(option);
			}
		}
		document.getElementById('action_field').value = '';
	}
</script>
</head>
<body>

<h1>Add a game</h1>

<form action = "game.php" method = "POST" enctype = "multipart/form-data">
Name: <input type = "text" name = "name"><br>
URL (if not in gamefile below): <input type = "text" name = "url"><br>
or Game file: <input type = "file" name = "gamefile_upload"><br><br>
Width: <input type = "text" name = "width"><br>
Height: <input type = "text" name = "height"><br>
Description: <br><textarea name = "description"></textarea><br>
Controls:<br>
<input type='text' id='control_field'/><button onclick='addAControl(event)'>Add this new Control</button>
<input type='text' id='action_field'/><button onclick='addAnAction(event)'>Add this new Action</button><br>
<div id='controls_area'><button onclick='addControl(event)'>Add Controls/Actions</button><button id='remove_control' style='display: none' onclick='removeControl(event)'>Remove Controls/Actions</button></div><br><br>
Image URL: <input type = "text" name = "image_url"><br>
or Image file <input type = "file" name = "imagefile_upload"><br><br>
Category 1: 
<select name = "cat1">
	<option value = ""></option>
	<option value = "7">Adventure</option>
	<option value = "1">Arcade</option>
	<option value = "3">Driving</option>
	<option value = "8">Idle</option>
	<option value = "4">Platformer</option>
	<option value = "2">Puzzle</option>
	<option value = "5">Simulation</option>
	<option value = "9">Sports</option>
	<option value = "10">Tower Defense</option>
	<option value = "6">Upgrade</option>
</select><br><br>
Category 2:
<select name = "cat2">
	<option value = ""></option>
	<option value = "7">Adventure</option>
	<option value = "1">Arcade</option>
	<option value = "3">Driving</option>
	<option value = "8">Idle</option>
	<option value = "4">Platformer</option>
	<option value = "2">Puzzle</option>
	<option value = "5">Simulation</option>
	<option value = "9">Sports</option>
	<option value = "10">Tower Defense</option>
	<option value = "6">Upgrade</option>
</select><br><br>
<select name = "type">
	<option value = "Flash">Flash</option>
	<option value = "JavaScript">JavaScript</option>
</select>
<input type = "submit" value = "submit" name = "submit"><br>
</form>

<?php

	$name = $_POST['name'];
	$url = $_POST['url'];
	$width = $_POST['width'];
	$height = $_POST['height'];

	$description = $_POST['description'];
	$image_url = $_POST['image_url'];
	$game_type = $_POST['type'];
	$submit = $_POST['submit'];
	$url_name = str_replace(' ','',$name);
	$url_name = str_replace('&','',$url_name);
	$url_name = str_replace("'","",$url_name);
	$url_name = strtolower($url_name);

	$cat1 = $_POST['cat1'];
	$cat2 = $_POST['cat2'];
	
	if(!$cat1) {
		die("Please select a category");
	}
	if($cat2 == "") {
		unset($cat2);
	}

	$keys = array();
	$index = 0;
	if(isset($_POST['key_0'])) {
		while(isset($_POST['key_' . $index])) {
			$keys[] = $_POST['key_' . $index];
			$index ++;
		}
		//var_dump($keys);
	}

	$actions = array();
	$index = 0;
	if(isset($_POST['action_0'])) {
		while(isset($_POST['action_' . $index])) {
			$actions[] = $_POST['action_' . $index];
			$index ++;
		}
		//var_dump($actions);
	}
	
	// CHECK FOR DUPLICATES
	for($i=0;$i<count($keys)-1;$i++) {
		$key_check = $keys[$i];
		for($j=$i+1; $j<count($keys);$j++) {
			if($keys[$i] == $keys[$j] && $actions[$i] == $actions[$j]) {
				die("Please do not enter duplicate key action pairs");
			}
		}
	}

	// Create unique arrays of keys and actions - remove number
	$keys_set = [];
	$actions_set = [];
	for($i=0;$i<count($keys);$i++) {
		if(!is_numeric($keys[$i]) && !in_array($keys[$i], $keys_set)) {
			$keys_set[] = $keys[$i];
		}
	}
	for($i=0;$i<count($actions);$i++) {
		if(!is_numeric($actions[$i]) && !in_array($actions[$i], $actions_set)) {
			$actions_set[] = $actions[$i];
		}
	}
	
	// Insert new rows and get new id
	for($i=0;$i<count($keys_set);$i++) {
		$key = $keys_set[$i];
		// Ok to not use bind params here since protected..
		$sql = "INSERT INTO controls(controls.key) VALUES ('$key')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		for($j=0;$j<count($keys);$j++) {
			if($keys[$j] == $key) {
				$keys[$j] = $id;
			}
		}
	}
	for($i=0;$i<count($actions_set);$i++) {
		$action_name = $actions_set[$i];
		// Ok to not use bind params here since protected..
		$sql = "INSERT INTO actions(name) VALUES ('$action_name')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		for($j=0;$j<count($actions);$j++) {
			if($actions[$j] == $action_name) {
				$actions[$j] = $id;
			}
		}
	}
	//var_dump($keys);
	//var_dump($actions);
	
	$actions_controls_arr = [];
	// Now, you have to do a lookup to see if each pair/value already exists in actions_controls
	// If it does, get the id, otherwise insert and get id. Put in actions_controls arr.
	for($i=0;$i<count($keys);$i++) {
		$key_id = $keys[$i];
		$action_id = $actions[$i];
		$sql = "SELECT id FROM actions_controls WHERE action_id = '$action_id' AND control_id = '$key_id'";
		$result = $mysqli->query($sql);
		$num_rows = $result->num_rows;
		if($num_rows > 0) {
			$row = $result->fetch_array();
			$actions_controls_arr[] = $row['id'];
			$result->close();
		}
		else {
			$result->close();
			$sql = "INSERT INTO actions_controls(control_id, action_id) VALUES ('$key_id','$action_id')";
			$mysqli->query($sql);
			$id = $mysqli->insert_id;
			$actions_controls_arr[] = $id;
		}
		
	}
	
	//var_dump($actions_controls_arr);

	if($submit) {
		$gamefile_target_dir = "../stock/games/flash/";
		$gamefile_target_file = $gamefile_target_dir . basename($_FILES["gamefile_upload"]["name"]);

		$imagefile_target_dir = "../images/icons/games/";
		$imagefile_target_file = $imagefile_target_dir. basename($_FILES["imagefile_upload"]["name"]);

		
		//move the files to the correct location
		//if the upload is successful that is the url
		if(move_uploaded_file($_FILES["gamefile_upload"]["tmp_name"],$gamefile_target_file)) {
			$url = $gamefile_target_file;
			echo "uploading the game was a success.<br>";
		}
		if(move_uploaded_file($_FILES["imagefile_upload"]["tmp_name"],$imagefile_target_file)) {
			$image_url = $imagefile_target_file;
			echo "uploading the image was a success.<br>";
		}
		
		$url = substr($url, 2);
		$image_url = substr($image_url, 2);
		
		$sql = "INSERT INTO entries(name, url, width, height, description, image_url, url_name, type) VALUES ('$name','$url','$width','$height','$description','$image_url','$url_name','$game_type')";
		$mysqli->query($sql);
		$id = $mysqli->insert_id;
		for($i=0;$i<count($actions_controls_arr);$i++) {
			$ac_id = $actions_controls_arr[$i];
			$sql = "INSERT INTO actions_controls_entries(action_control_id, entry_id) VALUES ('$ac_id','$id')";
			$mysqli->query($sql);
		}
		
		$sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES ('$cat1','$id')";
		$mysqli->query($sql);
		if(isset($cat2)) {
			$sql = "INSERT INTO categories_entries(category_id, entry_id) VALUES ('$cat2','$id')";
			$mysqli->query($sql);
		}
		
		//$query = mysql_query("INSERT INTO entries VALUES ('$gameid','$embedcode','$description','$controls','$image_url','$date','0','0','0','$urlid','$cat1','$cat2')");
		
		/*$file = '../sitemap.xml';
		$xml = simplexml_load_file($file);

		$urlset = $xml;

		$url = $urlset->addChild('url');
		$url->addChild('loc', 'http://game103.net/game.php?urlid=' . $urlid);
		$url->addChild('changefreq', 'daily');
		$url->addChild('priority', '0.7');

		$xml->asXML($file);
		
		echo "game added :-)";*/
	}
		$mysqli->close();

	?>

</body>
</html>