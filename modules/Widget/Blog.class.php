<?php
	namespace Widget;

    require_once('Widget.class.php');
    require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Game 103 Blog.
	*/
	class Blog extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct($properties) {
			\Widget::__construct($properties);
            $this->CSS[] = "/css/blog.css";
            $this->JS[] = "/javascript/blog.js";
        }
		
		/**
		* Generate HTML
		*/
		public function generate() {
            $string = file_get_contents("https://game103.net/wp?rest_route=/wp/v2/posts&per_page=100&_embed");
            $entries = json_decode($string, true);
            $limit = 10000;
            $max_characters = 1000;
            $i = 0;
            while( $entries[$i] ) {
                $title = str_replace(' & ', ' &amp; ', $entries[$i]['title']['rendered']);
                $description = $entries[$i]['content']['rendered'];
                $description = preg_replace( "/<img([^>]*) src=\"http:/", "<img$1 src=\"https:", $description);
                $date = date('l, F d, Y', strtotime($entries[$i]['date']));
                $author = $entries[$i]["_embedded"]["author"][0]["name"];
                $comments_link = $entries[$i]['link'];
                $tags = "";
                $categories = $entries[$i]['_embedded']['wp:term'][0]; 
		$new_categories = array();
		for( $j=0; $j<count($categories); $j++ ) {
			if( $categories[$j]['name'] != "Uncategorized" ) array_push( $new_categories, $categories[$j] );
		}
                // Generate the tags
		if( $categories && count($new_categories) > 0 ) {
                    $tags = "<div class='blog-post-tags'>Tags: ";
                    $tags_arr = array();
                    foreach( $categories as $category ) {
			if( $category[$name] == "Uncategorized" ) continue;
		    	#if( $category["taxonomy"] != "category" ) continue;
                        array_push( $tags_arr, $category['name'] );
                    }
                    $tags .= implode( ", ", $tags_arr );
                    $tags .= "</div>";
                }
                $show_all_link = "";
                // We might need to hide some
                if( strlen($description) > $max_characters ) {
                    $break_position = strpos( $description, "<br />", $max_characters ); 
                    // If we found a position
                    if( $break_position && $break_position < strlen($description) ) {
                        $next_end_tag = strpos( $description, "</", $break_position );
                        // Don't break inside pre tags
                        if( substr( $description, $next_end_tag, 5 ) == "</pre" ) {
                            $break_position = strpos( $description, "<br />", $next_end_tag ); 
                        }
                        // Make sure the position is still good
                        if( $break_position && $break_position < strlen($description) ) {
                            $description_start = substr( $description, 0, $break_position );
                            $description_end = substr( $description, $break_position );
                            $description = $description_start . "<span class='blog-post-contents-hidden'>" . $description_end . "</span>";
                            $show_all_link .= "<a href='javascript:;' class='blog-post-contents-show'>Show all</a> ";
                        }
                    }
                }
                $html .= '<div class="blog-post"><div class="blog-post-title">'.$title.'</div>';
                $html .= '<div class="blog-post-date">Posted on '.$date.' by '.$author.'</div>';
                $html .= $tags;
                $html .= '<div class="blog-post-content">'.$description.'</div>';
                $html .= '<div class="blog-post-actions">' . $show_all_link . '<a target="_blank" rel="noopener" href="'.$comments_link.'">Comments</a></div>';
                $html .= "<div class='blog-post-end'></div></div>";
                $i++;
            }

            $box = new \Widget\Box( array(
                'content'		=> array( array( 'title' => 'Blog', 'content' => $html ) ),
                'title'			=> "Blog",
                'footer'		=> "",
                'id'			=> "blog"
            ) );
            $box->generate();
            
            // Get box the find JS and CSS (includes box and entry)
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
            $this->HTML = $box->get_HTML();
            
			return $this->HTML;
		}
		
	}

?>
