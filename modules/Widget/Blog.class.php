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
            $this->CSS[] = "https://alexgorbatchev.com/pub/sh/current/styles/shCore.css";
            $this->CSS[] = "https://alexgorbatchev.com/pub/sh/current/styles/shThemeEmacs.css";
            $this->JS[] = "/javascript/blog.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shCore.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushAS3.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushBash.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushCss.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushJScript.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushJava.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushPhp.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushPython.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushSql.js";
            $this->JS[] = "https://alexgorbatchev.com/pub/sh/current/scripts/shBrushXml.js";
        }
		
		/**
		* Generate HTML
		*/
		public function generate() {
            $string = file_get_contents("https://game103blog.blogspot.com/feeds/posts/default?alt=json&max-results=10000");
            $json = json_decode($string, true);
            $feed = $json['feed'];
            $entries = $feed['entry'];
            $limit = 10000;
            $max_characters = 1000;
            $i = 0;
            while( $entries[$i] ) {
                $title = str_replace(' & ', ' &amp; ', $entries[$i]['title']['$t']);
                $description = $entries[$i]['content']['$t'];
                $date = date('l, F d, Y', strtotime($entries[$i]['published']['$t']));
                $author = $entries[$i]['author'][0]['name']['$t'];
                $comments_link = $entries[$i]['link'][1];
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
                $html .= '<div class="blog-post-content">'.$description.'</div>';
                $html .= '<div class="blog-post-actions">' . $show_all_link . '<a target="_blank" rel="noopener" href="'.$comments_link['href'].'">'.$comments_link['title'].'</a></div>';
                $html .= "<div class='blog-post-end'></div></div>";
                $i++;
            }

            $html .= "<script language='javascript' type='text/javascript'>
                SyntaxHighlighter.config.bloggerMode = true;
                SyntaxHighlighter.all();
            </script>";

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
