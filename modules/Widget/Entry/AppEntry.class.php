<?php
	namespace Widget\Entry;
	
	require_once('Widget/Entry.class.php');

	/**
	*	Entry for a app.
	*/
	class AppEntry extends \Widget\Entry {
		
		/**
		*	Constructor.
		*	Required properties
		*		app_type			string	the type of app ("iOS", "Android", or "Both")
		*	Optional properties
		*		store_url_apple		string	the link to the Apple app store
		*		store_url_android	string	the link to the Google Play store
		*/
		public function __construct($properties) {
			$properties['type'] = 'app';
			$properties['count_verb'] = 'visit';
			$properties['count_verb_plural'] = $properties['count_verb'] . 's';
			unset( $properties['rating'] );
			
			// If we have a Game 103 page for the app
			if( $properties['url_name'] ) {
				$properties['link'] = '/' . $properties['type'] . '/' . $properties['url_name'];
			}
			else {
				// Default to android since most devices in the world are Android
				$properties['link'] = $properties['store_url_android'];
			}
			
			$properties['app_store_logo'] = "";
			if( $properties['app_type'] == 'iOS' || $properties['app_type'] == "Both" ) {
				$properties['app_store_logo'] .= "<span class='entry-ios-link' data-store-url='{$properties["store_url_apple"]}'></span>";
			}
			if( $properties['app_type'] == "Android" || $properties['app_type'] == "Both") {
				$properties['app_store_logo'] .= "<span class='entry-android-link' data-store-url='{$properties["store_url_android"]}'>
					<img alt='Get it on Google Play' src='https://play.google.com/intl/en_us/badges/images/generic/en_badge_web_generic.png'
					style ='height:100%;width:100%'/>
				</span>";
			}

			\Widget\Entry::__construct($properties);
		}
		
	}

?>