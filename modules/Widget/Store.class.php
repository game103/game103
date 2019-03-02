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
                        <div class="store-item-title">Stickers</div>
                        <div class="store-item-price">Estimated Price per Item: $0.17</div>
                        <div class="store-item-quantity">Minimum Quantity: 24</div>
                        <picture>
                            <source srcset="/images/store/stickers.webp" type="image/webp">
                            <source srcset="/images/store/stickers.png"> 
                            <img src="/images/store/stickers.png" alt="Game 103 Stickers" id="stickers-image">
                        </picture>
                        <a target = "_blank" href="http://www.vistaprint.com/vp/gateway.aspx?s=4009498015&preurl=%2fshare-design.aspx%3fdoc_id%3d3345072637%26shopper_id%3dX5QTNQ1SO0Z2RPN1GEHII2FLT4PEE1MF%26xnav%3dsharesource_8%26share_key%3d7e91f8d5-bc68-4f66-b01b-a307361ee5a5">
                            <button>Buy Now</button>
                        </a>
                    </div>
                    <div class="store-item">
                        <div class="store-item-title">Bumper Sticker</div>
                        <div class="store-item-price">Estimated Price per Item: $3.60</div>
                        <div class="store-item-quantity">Minimum Quantity: 1</div>
                        <picture>
                            <source srcset="/images/store/bumpersticker.webp" type="image/webp">
                            <source srcset="/images/store/bumpersticker.png"> 
                            <img src="/images/store/bumpersticker.png" alt="Game 103 Bumper Sticker" id="bumper-sticker-image">
                        </picture>
                        <a target = "_blank" href="http://www.vistaprint.com/vp/gateway.aspx?s=4009498015&preurl=%2fshare-design.aspx%3fdoc_id%3d3344662370%26shopper_id%3dX5QTNQ1SO0Z2RPN1GEHII2FLT4PEE1MF%26xnav%3dsharesource_8%26share_key%3dcf0151f1-4ea6-471e-a853-74e1b4cc2c5e">
                            <button>Buy Now</button>
                        </a>
                    </div>
                </div>
                <div class="store-column">
                    <div class="store-item">
                        <div class="store-item-title">T-Shirt</div>
                        <div class="store-item-price">Estimated Price per Item: $32.95</div>
                        <div class="store-item-quantity">Minimum Quantity: 1</div>
                        <div class="store-item-note">Design available on different shirts and sweatshirts</div>
                        <picture>
                            <source srcset="/images/store/tshirt.webp" type="image/webp">
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