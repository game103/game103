<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Store page.
	*/
	class Store extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct() {
            \Widget::__construct( array() );
            $this->CSS[] = "/css/store.css";
            $this->JS[] = "/javascript/store.js";
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			$html = <<<HTML
			<p>Welcome to the Game 103 Store. Here you can find designs for Game 103 content that have been made over the years. 
            Please note that Game 103 is not involved in getting the items to you. If you choose to purchase a design, 
            you will be dealing directly with the company that you purchase it from (Vistaprint or Custom Ink). Game 103 does not receive any money when you
            buy these products.</p>
            <div class="store">
                <div class="store-column">
                    <div class="store-item">
                        <div class="store-item-title">T-Shirt</div>
                        <div class="store-item-price">Estimated Price per Item: $40.25</div>
                        <div class="store-item-quantity">Minimum Quantity: 1</div>
                        <div class="store-item-note">Design available on different shirts and sweatshirts</div>
                        <picture>
                            <source srcset="/images/store/tshirt.webp" data-back-src="/images/store/tshirtback.webp" type="image/webp">
                            <source srcset="/images/store/tshirt.jpg" data-back-src="/images/store/tshirtback.jpg"> 
                            <img src="/images/store/tshirt.jpg" alt="Game 103 T-Shirt" id="tshirt-image" data-back-src="/images/store/tshirtback.jpg">
                        </picture>
                        <a target = "_blank" href="https://www.customink.com/designs/game103/rjg0-0012-nke0/">
                            <button>Buy Now</button>
                        </a>
                    </div>
                </div>
            </div>
HTML;
			
			$box = new \Widget\Box( array(
				'content'		=> array( 
									array( 'title' => '-', 'content' => $html ),
									),
				'title'			=> 'Store',
				'footer'		=> '',
			) );
			$box->generate();
			$this->HTML = $box->get_HTML();
			$this->JS = array_merge( $this->JS, $box->get_JS() );
			$this->CSS = array_merge( $this->CSS, $box->get_CSS() );
			
			return $this->HTML;
		}
		
	}

?>