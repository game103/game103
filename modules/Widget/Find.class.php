<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Entry/GameEntry.class.php');
	require_once('Widget/Entry/AppEntry.class.php');
	require_once('Widget/Entry/VideoEntry.class.php');
	require_once('Widget/Entry/DownloadEntry.class.php');
	require_once('Widget/Entry/ResourceEntry.class.php');
	require_once('Widget/Entry/GameEntry/DistributableEntry.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Find application.
	*/
	class Find extends \Widget {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Find Service for expected properties
		*	Additionally, one may include the following properties
		*		header (defaults to search options)
		*		footer (defaults to paging)
		*		no_box (no box is included)
		*	These are good to have here, since they generalize this
		*	rather high-level widget.
		* 	Note that there is only one Find class but many find services.
		*	This is because the Main find service supports 'Everything,' 
		* 	so easily supports just games or other subclasses.
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
			if( !$this->properties['no_box'] ) {
				$this->JS[] = "/javascript/find.js";
				$this->CSS[] = "/css/find.css";
			}
		}
		
		/**
		* Generate HTML
		* Note, if we want to use a web service, we should send to the
		* user what this function returns
		*/
		public function generate() {
			// Get the array with the result from fetching items from the db
			if( $this->properties['status'] == 'success' ) {
				$items_section = $this->generate_entries();
				# Default footer is paging
				if( !isset($this->properties['footer']) ) {
					$this->properties['footer'] = $this->generate_paging_controls();
				}
			}
			else {
				// Set the items to be the message
				$items_section = $this->properties['message'];
			}
			// generate a dummy entry for js and css
			$entry = new \Widget\Entry\AppEntry( array() );
			$this->JS = array_merge( $this->JS, $entry->get_JS() );
			$this->CSS = array_merge( $this->CSS, $entry->get_CSS() );
			
			// Default title is dropdowns and search
			if( !isset($this->properties['header']) ) {
				// Add dropdowns
				$this->properties['header'] = $this->generate_dropdowns();
			}
			
			$this->HTML = $items_section;
			if( !$this->properties['no_box'] ) {
				$box = new \Widget\Box( array(
					'content'		=> array( 
										array( 'title' => '-', 'content' => $items_section ),
										),
					'title'			=> $this->properties['header'],
					'footer'		=> $this->properties['footer'],
				) );
				$box->generate();
				$this->HTML = $box->get_HTML();
				$this->JS = array_merge( $this->JS, $box->get_JS() );
				$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			}
			
			return $this->HTML;
		}
		
		/**
		* Generate entries
		*/
		protected function generate_entries() {
			$items = $this->properties['items'];
			$items_section = "";
			for( $i=0; $i<sizeof( $items ); $i++ ) {
				$items_section .= $this->generate_entry( $items[$i] );
			}
			return $items_section;
		}
		
		/**
		* Generate entry
		*/
		protected function generate_entry( $item ) {
			if( $item['type'] == 'game' ) {
				if( $this->properties['category'] == 'distributable' ) {
					$entry = new \Widget\Entry\GameEntry\DistributableEntry( $item );
				}
				else {
					$entry = new \Widget\Entry\GameEntry( $item );
				}
				
			}
			else if( $item['type'] == 'download' ) {
				$entry = new \Widget\Entry\DownloadEntry( $item );
			}
			else if( $item['type'] == 'video' ) {
				$entry = new \Widget\Entry\VideoEntry( $item );
			}
			else if( $item['type'] == 'app' ) {
				$entry = new \Widget\Entry\AppEntry( $item );
			}
			// resource
			else {
				$entry = new \Widget\Entry\ResourceEntry( $item );
			}
			$entry->generate();
			return $entry->get_HTML();
		}

		/**
		* Generate paging controls
		*/
		protected function generate_paging_controls() {
			
			$keys = array_keys( $this->properties['linked_pages'] );
			
			if($this->properties['page'] == 1) {
				$previous_paging_style = "style='visibility:hidden'";
			}
			$first_link = $this->properties['linked_pages'][$keys[0]];
			$first_name = $keys[0];
			$prev_link = $this->properties['linked_pages'][$keys[1]];
			$prev_name = $keys[1];
			$paging_controls = "
				<div id='backwards-paging' class='find-word-paging' $previous_paging_style>
					<a href='$first_link' id='first-paging' class='button'>$first_name</a><a href='$prev_link' id='previous-paging' class='button'>$prev_name</a>
				</div>";
			$count = 0;
			
			$size = sizeof( $keys );
			for( $i=2; $i<$size-2; $i++ ) {
				$paging_controls .= $this->create_page_num( $keys[$i], $this->properties['page'], $this->properties['linked_pages'][$keys[$i]] );
			}
			
			if($this->properties['page'] == $keys[$size-3]) {
				$next_paging_style = 'style="visibility:hidden"';
			}
			
			$next_link = $this->properties['linked_pages'][$keys[$size-2]];
			$next_name = $keys[$size-2];
			$last_link = $this->properties['linked_pages'][$keys[$size-1]];
			$last_name = $keys[$size-1];
			$paging_controls .= "<div id='forwards-paging' class='find-word-paging' $next_paging_style>
					<a href='$next_link' id='next-paging' class='button'>$next_name</a><a href='$last_link' id='last-paging' class='button'>$last_name</a>
				</div>";
			
			return $paging_controls;
		}
		
		/**
		* Create the html for a page number button
		*/
		protected function create_page_num($page_num, $page, $link) {
			$paging_class = "";
			if($page_num == null) {
				$id = "";
				$page_num = "-";
				$style = "style='display: none'";
			}
			else {
				$id = "id='page-num-$page_num'";
				if($page_num == $page) {
					$paging_class = "find-paging-selected-page";
				}
				$style = "";
			}
			return "<a href='$link' $id class='button find-number-paging $paging_class' $style>$page_num</a>";
		}
		
		/**
		* Generate dropdowns.
		*/
		protected function generate_dropdowns() {
			$search = $this->generate_search_box();
			if( sizeof($this->properties['valid_categories']) > 0 ) {
				$categories = $this->generate_categories_dropdown();
			}
			$types = $this->generate_types_dropdown();
			$sort = $this->generate_sort_dropdown();
			if( sizeof($this->properties['valid_platforms']) > 0 ) {
				$platforms = $this->generate_platforms_dropdown();
			}
			return <<<DROPDOWNS
				<div class='find-refine'>
					$search
					$categories
					$platforms
					$types
					$sort
				</div>
DROPDOWNS;
			
			return $dropdowns;
			
		}
		
		/**
		* Generate search box.
		*/
		protected function generate_search_box() {
			return "<form method='GET'><input type='text' value='{$this->properties['search']}' name='search' placeholder='Search' id='search' autocomplete='off' /></form>";
		}
		
		// values is key - url, values are array with name and potentially description.
		protected function generate_dropdown( $values, $selected, $id_prefix ) {
			$options = "";
			foreach( $values as $url_name => $details ) {
				if( $url_name == $selected ) {
					$selected_class = 'find-dropdown-selected-in-list';
				}
				else {
					$selected_class = '';
				}
				
				if( $details['description'] ) {
					$description =  "data-description=\"{$details['description']}\"";
				}
				$options .= "
				<li class='find-dropdown-item $selected_class'>
					<a href=\"{$details['link']}\" id='cat-$category_url_name' class='find-dropdown-item-text' $description>
						{$details['name']}
					</a>
				</li>";
			}
			return <<<OPTIONS
			<input type='checkbox' class='find-checkbox' id='$id_prefix-checkbox'>
			<label for='$id_prefix-checkbox' class='find-dropdown' id='$id_prefix-dropdown'>
				<div class='find-dropdown-selected'>
					<span class='find-dropdown-selected-text'>
						{$values[$selected]['name']}
					</span>
				</div>
				<ul class='find-dropdown-menu' id='$id_prefix-dropdown-menu'>
					$options
				</ul>
			</label>
OPTIONS;
		}
		
		/**
		* Generate categories dropdown
		*/
		protected function generate_categories_dropdown() {
			return $this->generate_dropdown( $this->properties['valid_categories'], $this->properties['category'], 'cat' );
		}
		
		/**
		* Generate types dropdown
		*/
		protected function generate_types_dropdown() {
			return $this->generate_dropdown( $this->properties['valid_types'], $this->properties['type'], 'type' );
		}

		/**
		* Generate platforms dropdown
		*/
		protected function generate_platforms_dropdown() {
			return $this->generate_dropdown( $this->properties['valid_platforms'], $this->properties['platform'], 'subtype' );
		}
		
		/**
		* Generate sort dropdown
		*/
		protected function generate_sort_dropdown() {
			return $this->generate_dropdown( $this->properties['valid_sort'], $this->properties['sort'], 'sort' );
		}
		
	}
	
?>
