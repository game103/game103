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
            $i = 0;
            while( $entries[$i] ) {
                $title = str_replace(' & ', ' &amp; ', $entries[$i]['title']['$t']);
                $description = $entries[$i]['content']['$t'];
                $date = date('l, F d, Y', strtotime($entries[$i]['published']['$t']));
                $author = $entries[$i]['author'][0]['name']['$t'];
                $comments_link = $entries[$i]['link'][1];
                $html .= '<div class="blog-post"><div class="blog-post-title">'.$title.'</div>';
                $html .= '<div class="blog-post-date">Posted on '.$date.' by '.$author.'</div>';
                $html .= '<div class="blog-post-content">'.$description.'</div>';
                $html .= '<div class="blog-post-comments"><a target="_blank" rel="noopener" href="'.$comments_link['href'].'">'.$comments_link['title'].'</a></div>';
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
