<?php
	$error_val = 'Sorry, there was an error loading similar games. Please try again later.';

	try {
		if(!isset($mysqli)) {
			throw new Exception($error_val);
		}
		// If the connection is closed
		if (!$mysqli->ping()) {
			throw new Exception($error_val);
		}
		
		$random_games = '';
		$random_category_str = "SELECT categories_entries.category_id FROM entries
				JOIN categories_entries ON entries.id = categories_entries.entry_id
				WHERE entries.id = ?
				LIMIT 2";
		$random_category_statement = $mysqli->prepare($random_category_str);
		$random_category_statement->bind_param("i", $id);
		$random_category_statement->execute();
		if(mysqli_stmt_error($random_category_statement) != "") {
			$mysqli->close();
			throw new Exception($error_val);
		}
		$random_category_statement->bind_result($categories_entries_id);
		$category_ids = array();
		while($random_category_statement->fetch()) {
			$category_ids[] = $categories_entries_id;
		}
		$random_category_statement->close();
		
		$random_select_str = "SELECT entries.name, entries.description, entries.url_name, entries.image_url, entries.rating, FORMAT(entries.plays, 0)
						FROM entries JOIN categories_entries ON entries.id = categories_entries.entry_id
						WHERE (categories_entries.category_id = ? ";
		if(count($category_ids) == 2) {
			$random_select_str .= " OR categories_entries.category_id = ? ";
		}
		$random_select_str .= ") AND entries.id != ? ";
		$random_select_str .= "GROUP BY entries.id
						ORDER BY RAND()
						LIMIT 6";
		$random_select_statement = $mysqli->prepare($random_select_str);
		if(count($category_ids) == 2) {
			$random_select_statement->bind_param("iii", $category_ids[0], $category_ids[1], $id);
		}
		else {
			$random_select_statement->bind_param("ii", $category_ids[0], $id);
		}
		$random_select_statement->execute();
		if(mysqli_stmt_error($random_select_statement) != "") {
			$mysqli->close();
			throw new Exception($error_val);
		}
		$random_select_statement->bind_result($random_name, $random_description, $random_url_name, $random_image_url, $random_rating, $random_plays);
		while($random_select_statement->fetch()) {
			
			// Escape the quotes in the name of the entry
			$random_name = htmlentities($random_name, ENT_QUOTES);
			
			if($random_plays == 1) {
				$random_plays_str = 'play';
			}
			else  {
				$random_plays_str = 'plays';
			}
			
			$random_rating_width = ($random_rating * 22) . 'px';
			
			$random_games .= "<a href = '/game/$random_url_name' class = 'entry-link'>
			<span class = 'entry-item'>
			<img alt = '$random_name' src = '$random_image_url'><br>
			<span class = 'entry-title'>$random_name</span>
			<span class='stars entry-stars'><span style='width: $random_rating_width'></span></span>";
			$random_games .= "<span class = 'entry-description'> $random_description</span>
			<span class = 'entry-plays'> $random_plays $random_plays_str</span>
			</span>
			</a>";
		}
	
		$random_select_statement->close();
	}
	catch(Exception $e) {
		$random_games = $e->getMessage();
	}
?>