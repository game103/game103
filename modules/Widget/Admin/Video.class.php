<?php
	namespace Widget\Admin;

	require_once('Widget/Admin.class.php');

	/**
	*	Widget representing the Video Admin page.
	*/
	class Video extends \Widget\Admin {
		
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

            // Repopulate all the fields if there was an error so we don't have to type them again
            // (On error or on load edit form)
            $type = 'Video'; // Default type for videos
            if( $this->properties['status'] != 'success' && $this->properties['name'] ) {
                $id = $this->properties['id'];
                $name = $this->properties['name'];
                $string = $this->properties['string'];
                $image_url = $this->properties['image_url'];
                $description = $this->properties['description'];
                $cat1 = $this->properties['cat1'];
                $cat2 = $this->properties['cat2'];
                $type = $this->properties['type'];
            }

			$html = <<<HTML
<script>
    var cat1 = '$cat1';
    var cat2 = '$cat2';
    var type = '$type';
</script>
<form class="admin" action = "/administration/video" method = "POST" enctype = "multipart/form-data">
$error_message
<label for="name"><span class='admin-label-text'>Name: </span><input value="$name" required id="name" type = "text" name = "name"></label>
<label for="string"><span class='admin-label-text'>String: </span><input value="$string" required id="string" type = "text" name = "string"></label>
<label class="admin-close" for="image_url"><span class='admin-label-text'>Image URL: </span><input value="$image_url" id="image_url" type = "text" name = "image_url"></label>
<label for="imagefile"><span class='admin-label-text'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;or Image File: </span><input id="imagefile" type = "file" name = "imagefile_upload"></label>
<label for="description"><span class='admin-label-text'>Description: </span><textarea required id="description" name = "description">$description</textarea></label>
<label for = "cat1">
<span class='admin-label-text'>Category 1: </span>
<select required id="cat1" name = "cat1">
	<option value = ""></option>
	<option value = "1">Comedy</option>
	<option value = "2">Games</option>
	<option value = "3">Instructional</option>
	<option value = "4">Interesting</option>
	<option value = "5">Musical</option>
	<option value = "6">Nature</option>
	<option value = "7">Story</option>
</select>
</label>
<label for = "cat2">
<span class='admin-label-text'>Category 2: </span>
<select id="cat2" name = "cat2">
    <option value = ""></option>
	<option value = "1">Comedy</option>
	<option value = "2">Games</option>
	<option value = "3">Instructional</option>
	<option value = "4">Interesting</option>
	<option value = "5">Musical</option>
	<option value = "6">Nature</option>
	<option value = "7">Story</option>
</select>
</label>
<input type='hidden' name='id' value='$id'/>
<label for = "type">
<span class='admin-label-text'>Video Type: </span>
<select required id="type" name = "type">
    <option value = "Video">Video</option>
	<option value = "Playlist">Playlist</option>
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
                'title'			=> "Video Admin",
                'tight'         => 1
			) );
            $box->generate();
            $this->HTML = $box->get_HTML() . $this->generate_listing( "video", "Current Videos" );
            $this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
        }
		
	}

?>
