<?php
	namespace Widget\Admin;

	require_once('Widget/Admin.class.php');

	/**
	*	Widget representing the Game Admin page.
	*/
	class Game extends \Widget\Admin {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			\Widget\Admin::__construct( $properties );
		}
		
		/**
		* Generate Content
		*/
		public function generate() {

            // Create the error message
            $error_message = $this->generate_error_message();

            // Get the values for the list of all actions and keys
            $controls_ids = '[' . implode(',',$this->properties['controls_ids']) . ']';
            $controls_keys = '[' . implode(',',$this->properties['controls_keys']) . ']';
            $actions_ids = '[' . implode(',',$this->properties['actions_ids']) . ']';
            $actions_names = '[' . implode(',',$this->properties['actions_names']) . ']';

            // Repopulate all the fields if there was an error so we don't have to type them again
            // (On error or on load edit form)
            $type = 'Flash'; // Default type for games
            if( $this->properties['status'] != 'success' && $this->properties['name'] ) {
                $id = $this->properties['id'];
                $name = $this->properties['name'];
                $url = $this->properties['url'];
                $image_url = $this->properties['image_url'];
                $width = $this->properties['width'];
                $height = $this->properties['height'];
                $description = $this->properties['description'];
                $cat1 = $this->properties['cat1'];
                $cat2 = $this->properties['cat2'];
                $type = $this->properties['type'];

                // These just contain what the use submitted
                $current_actions = '[' . implode(',',$this->properties['actions']) . ']';
                $current_keys = '[' . implode(',',$this->properties['keys']) . ']';
            }
            else {
                $current_actions = "[]";
                $current_keys = "[]";
            }

			$html = <<<HTML
<script>
    var controlCount = 0;
    var controlsIds = $controls_ids;
    var controlsKeys = $controls_keys;
    var actionsIds = $actions_ids;
    var actionsNames = $actions_names;
    var currentActions = $current_actions;
    var currentKeys = $current_keys;
    var cat1 = '$cat1';
    var cat2 = '$cat2';
    var type = '$type';
</script>
<form class="admin" action = "/admin/game" method = "POST" enctype = "multipart/form-data">
$error_message
<label for="name"><span class='admin-label-text'>Name: </span><input value="$name" required id="name" type = "text" name = "name"></label>
<label class="admin-close" for="url"><span class='admin-label-text'>URL (if not in file): </span><input value="$url" id="url" type = "text" name = "url"></label>
<label for="file"><span class='admin-label-text'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or Game File: </span><input id="file" type = "file" name = "gamefile_upload"></label>
<label class="admin-close" for="image_url"><span class='admin-label-text'>Image URL: </span><input value="$image_url" id="image_url" type = "text" name = "image_url"></label>
<label for="imagefile"><span class='admin-label-text'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or Image File: </span><input id="imagefile" type = "file" name = "imagefile_upload"></label>
<label for="width"><span class='admin-label-text'>Width: </span><input value="$width" required id="width" type = "number" name = "width"></label>
<label for="height"><span class='admin-label-text'>Height: </span><input value="$height" required id="height" type = "number" name = "height"></label>
<label for="description"><span class='admin-label-text'>Description: </span><textarea required id="description" name = "description">$description</textarea></label>
<label id="admin-controls-section"><span class='admin-label-text'>Controls: </span><br>
<input type='text' id='control_field'/><button onclick='addAControl(event)'>Add this new Control</button>
<input type='text' id='action_field'/><button onclick='addAnAction(event)'>Add this new Action</button><br>
<div id='controls_area'><button onclick='addControl(event)'>Add Controls/Actions</button><button id='remove_control' style='display: none' onclick='removeControl(event)'>Remove Controls/Actions</button></div>
</label>
<input type='hidden' name='id' value='$id'/>
<label for = "cat1">
<span class='admin-label-text'>Category 1: </span>
<select required id="cat1" name = "cat1">
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
</select>
</label>
<label for = "cat2">
<span class='admin-label-text'>Category 2: </span>
<select id="cat2" name = "cat2">
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
</select>
</label>
<label for = "type">
<span class='admin-label-text'>Game Type: </span>
<select required id="type" name = "type">
    <option value = "Flash">Flash</option>
    <option value = "JavaScript">JavaScript</option>
</select>
</label>
<input id='submit' type = "submit" value = "Submit" name = "submit" class="button"><br>
<div class='clear'></div>
</form>
HTML;

            // Place the HTML in a box
            $box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
                'title'			=> "Game Admin",
                'tight'         => 1
			) );
            $box->generate();
            $this->HTML = $box->get_HTML() . $this->generate_listing("game", "Current Games");
            $this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
        }
		
	}

?>
