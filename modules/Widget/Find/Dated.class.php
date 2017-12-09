<?php
	namespace Widget\Find;

	require_once('Widget/Find.class.php');
	/**
	*	Widget representing the Front end of the Find application with Dates.
	*/
	class Dated extends \Widget\Find {
		
		/**
		*	Constructor.
		*	Properties
		*		See output of the Find Service for expected properties
		*	Additionally, one may include the following properties
		*		header (defaults to search options)
		*		footer (defaults to paging)
		*		no_box (no box is included)
		*/
		public function __construct($properties) {
			\Widget\Find::__construct($properties);
		}
		
		/**
		* Generate entries
		*/
		protected function generate_entries() {
			$items = $this->properties['items'];
			$items_section = "";
			$prev_date = 0;
			for( $i=0; $i<sizeof( $items ); $i++ ) {
				
				// Format the date and exclude any time reference
				$date = strtotime($items[$i]['added_date']);
				// Date check for comparison
				$check_date = date('m/d/Y', $date);

				//If the first item, display a header
				if($i == 0) {
					// Date to display to the user
					$formatted_date = date("n/j/y", $date);
					$items_section .= "<div class='find-date-box'><div class='find-date-box-title'>Added on ".$formatted_date."</div>";
					$prev_date = $check_date;
				}
				//If the previous date does not match the current date, display a header
				else if($check_date != $prev_date) {
					$formatted_date = date("n/j/y", $date);
					$items_section .= "</div><div class='find-date-box'><div class='find-date-box-title'>Added on ".$formatted_date."</div>";
					$prev_date = $check_date;
				}
				
				$items_section .= $this->generate_entry( $items[$i] );
			}
			$items_section .= "</div>";
			return $items_section;
		}
		
	}

?>