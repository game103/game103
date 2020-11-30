<?php
	namespace Widget;

	require_once('Widget.class.php');
	require_once('Widget/Box.class.php');

	/**
	*	Widget representing the Front end of the Flash Guide page.
	*/
	class FlashGuide extends \Widget {
		
		/**
		*	Constructor.
		*/
		public function __construct() {
            \Widget::__construct( array() );
            $this->CSS[] = "/css/flashguide.css";
		}
		
		/**
		* Generate HTML
		*/
		public function generate() {
			$html = <<<HTML
			In 2020, many browsers and devices are making it more difficult to use Adobe Flash Player. However, the internet (including this site!) is full 
of great Flash resources, mostly games, that many will want to use for years to come. This is a guide to make sure that Flash runs well on various devices/browsers.
<br><br>
The first option to try is to click the "Play in HTML5 (Beta)" button under the Options heading. This will enable <a href="https://ruffle.rs" rel="noopener" target="_blank">Ruffle</a>, which transforms
Flash content into HTML5.
<br><br>
<div class="table-of-contents">
Table of Contents
<ol>
    <li>
        <a href="#chrome">Chrome</a>
        <ol type="a">
            <li>
                <a href="#chrome-installing-flash">Installing Flash</a>
            </li>
            <li>
                <a href="#chrome-running-flash-without-asking">Running Flash without Asking</a>
                <ol type="i">
                    <li>
                        <a href="#chrome-running-flash-without-asking-windows">Windows</a>
                    </li>
                    <li>
                        <a href="#chrome-running-flash-without-asking-mac">MacOS</a>
                    </li>
                    <li>
                        <a href="#chrome-running-flash-without-asking-linux">Linux & Chrome OS Developer Mode</a>
                    </li>
                </ol>
            </li>
        </ol>
    </li>
    <li>
        <a href="#firefox">Firefox</a>
        <ol type="a">
            <li>
                <a href="#firefox-installing-flash">Installing Flash</a>
            </li>
            <li>
                <a href="#firefox-fixing-firefox-issues">Fixing Firefox Issues (e.g. Games not Saving)</a>
            </li>
        </ol>
    </li>
    <li>
        <a href="#iosandroid">iOS & Android</a>
    </li>
    <li>
        <a href="#tips">Tips</a>
    </li>
</ol>
</div>
<h2><a name="chrome">Chrome</a></h2>
<div class='flash-guide-tested'>(Tested on version 72 - released January 29th, 2019)</div>
<h3><a name="chrome-installing-flash">Installing Flash</a></h3>
Chrome comes with Flash pre-installed.<br>
Note: By default, when you visit a page with Flash content, you will have to click the Flash content area and then 
opt to allow Flash to run. This will enable Flash for the site that you are on. Your decision will be saved until you close your browser. You can make your decision last 
permanently by following the instructions below.
<h3><a name="chrome-running-flash-without-asking">Running Flash without Asking</a></h3>
Note: Please do this only if you understand the risks involved. 
One of the reasons that Flash is being deprecated is due to security vulnerabilities.
<h4><a name="chrome-running-flash-without-asking-windows">Windows</a></h4>
<ol>
    <li>Open Windows Registry.</li>
    <ol type="a">
        <li>On Windows 10, press Windows key + X.</li>
        <li>Click run.</li>
        <li>Type "regedit" and click OK (Allow the app to make changes if prompted).</li>
    </ol>
    <li>On the left side of the window, navigate to "Computer" > "HKEY_CURRENT_USER" > "Policies."
    <li>If you do not have a "Google" folder/key under "Policies," create one by right clicking "Policies," then clicking "New" > "Key," and naming the key "Google".</li>
    <li>If you do not have a "Chrome" folder/key under "Google," create one by right clicking "Google," then clicking "New" > "Key," and naming the key "Chrome".</li>
    <li>Right click your Chrome folder/key, then click "New" > "DWORD," and name the DWORD, "RunAllFlashInAllowMode."</li>
    <li>Make sure your Chrome folder is selected on the left side bar; then, double click your newly created DWORD on the right side of the window and set the value to 1.</li>
    <li>Repeat the previous two steps for the following two DWORDS: "AllowOutdatedPlugins" and "DefaultPluginsSetting" (both values should again be set to 1).</li>
    <li>Right click your Chrome folder/key; then, click "New" > "Key," and name the new key "PluginsAllowedForUrls."</li>
    <li>Right click your new PluginsAllowedForUrls folder/key, and click "New" > "String Value" and name it "1".</li>
    <li>Make sure your PluginsAllowedForUrls folder/key is selected on the left side and double click your newly created String value on the right side of the window. Set the value to the site you want to allow Flash on.</li>
    <li>Repeat the previous two steps for each new site that you want to add. Let the name be one number higher than the previous name (2, 3, 4, etc...) and the value be the site that you want to allow.</li>
    <ul><li>Note: If you understand the risks, you can use <pre class='inline-pre'>https://*</pre> and <pre class='inline-pre'>http://*</pre> as your site values to enable Flash everywhere.</li></ul>
    <li>Restart Chrome, and you should be able to play Flash without being prompted to enable it.</li>
</ol>
<div class="video-container-wrapper">
    <div class="video-container">
        <iframe allowfullscreen="allowfullscreen" src="https://www.youtube.com/embed/O8qW7U973dw?rel=0&amp;modestbranding=1&amp;theme=light&amp;iv_load_policy=3" frameborder="0"></iframe>
    </div>
</div>
<h4><a name="chrome-running-flash-without-asking-mac">MacOS</a></h4>
<ol>
    <li>Open the terminal application.</li>
    <li>Type <pre class='inline-pre'>defaults write com.google.Chrome RunAllFlashInAllowMode -bool true</pre> and press enter.</li>
    <li>Type <pre class='inline-pre'>defaults write com.google.Chrome AllowOutdatedPlugins -bool true</pre> and press enter.</li>
    <li>Type <pre class='inline-pre'>defaults write com.google.Chrome DefaultPluginsSetting -int 1</pre> and press enter.</li>
    <li>Type <pre class='inline-pre'>defaults write com.google.Chrome PluginsAllowedForUrls -array "https://mysite1.com" "http://mysite2.com"</pre>, replacing <pre class='inline-pre'>https://mysite1.com</pre> and <pre class='inline-pre'>http://mysite2.com</pre> with sites that you want to allow Flash on (you can add more sites [seperated by a space] or delete one if you like),  and press enter.</li>
    <ul><li>Note: If you understand the risks, you can use <pre class='inline-pre'>https://*</pre> and <pre class='inline-pre'>http://*</pre> in the above command to enable Flash everywhere.</li></ul>
    <li>Restart Chrome, and you should be able to play Flash without being prompted to enable it.</li>
</ol>
<h4><a name="chrome-running-flash-without-asking-linux">Linux & Chrome OS Developer Mode</a></h4>
<ol>
    <li>Create a file in the following location "/etc/opt/chrome/policies/managed/test_policy.json"
        and fill it with the contents below, replacing <pre class='inline-pre'>https://mysite1.com</pre> and <pre class='inline-pre'>http://mysite2.com</pre> with sites that you want to allow Flash on (you can add more sites [seperated by a comma] or delete one if you like):
<pre>
{ <br>
    "RunAllFlashInAllowMode": true,<br>
    "AllowOutdatedPlugins": true,<br>
    "DefaultPluginsSetting": 1,<br>
    "PluginsAllowedForUrls": ["https://mysite1", "http://mysite2.com"]<br>
}
</pre></li>
<ul><li>Note: If you understand the risks, you can use <pre class='inline-pre'>https://*</pre> and <pre class='inline-pre'>http://*</pre> in the above code as the values for <pre class='inline-pre'>PluginsAllowedForUrls</pre> to enable Flash everywhere.</li></ul>
<li>Restart Chrome, and you should be able to play Flash without being prompted to enable it.</li>
</ol>
<h2><a name="firefox">Firefox</a></h2>
<div class='flash-guide-tested'>(Tested on version 65 - released January 29th, 2019)</div>
<h3><a name="firefox-installing-flash">Installing Flash</a></h3>
<ol>
    <li>Visit <a href="https://get.adobe.com/flashplayer/" target='_blank' rel="noopener">https://get.adobe.com/flashplayer/</a> to download the Flash installer.</li>
    <li>Make sure you have completely exited out of Firefox.</li>
    <li>Open the file you downloaded in step 1.</li>
    <li>Follow the installer's instructions.</li>
    <li>Adobe Flash should now be installed and available to use next time you start Firefox.</li>
    <li>Note: By default, when you visit a page with Flash Content, you will have to click the Flash content area and then 
opt to allow Flash to run. This will enable Flash for the site that you are on. Your decision will be saved permanently if you opt to have your decision remembered.</li>
</ol>
<h3><a name="firefox-fixing-firefox-issues">Fixing Firefox Issues (e.g. Games not Saving)</a></h3>
By default, in Firefox 62 and onwards, Flash is run in a "sandbox" mode. This prevents certain actions including the ability save games.
Follow the instructions below to disable sandbox mode.
<ol>
    <li>Type "about:config" in the address bar and press enter.</li>
    <li>Click "I accept the risk!" if prompted.</li>
    <li>Search for "dom.ipc.plugins.sandbox-level.flash" and double click the preference with that name.</li>
    <li>Change the value from 1 to 0 and click "OK."</li>
    <li>Restart Firefox for your changes to take effect.</li>
    <li>You should now be able to save Flash Games.</li>
</ol>
<h2><a name="iosandroid">iOS & Android</a></h2>
iOS has never supported Flash natively, and Android has not supported Flash since 2013. 
However, there are some alternative browsers that will play Flash games and movies by streaming them remotely.
Sometimes this can be a little choppy, but games that require limited interaction work fairly well.<br>
Note: the data centers for these applications are in the US, 
meaning your proximity to the United States will impact the lag involved.
<br><br>
Puffin Web Browser Free (Contains Ads)<br>
<a target='_blank' rel="noopener" href="https://play.google.com/store/apps/details?id=com.cloudmosa.puffinFree">Google Play</a> (Free)<br>
<br>
<div class="video-container-wrapper">
    <div class="video-container">
        <iframe allowfullscreen="allowfullscreen" src="https://www.youtube.com/embed/a5FOM8w09mg?rel=0&amp;modestbranding=1&amp;theme=light&amp;iv_load_policy=3" frameborder="0"></iframe>
    </div>
</div><br>
Puffin Web Browser Pro<br>
<a target='_blank' rel="noopener" href="https://itunes.apple.com/us/app/puffin-browser-pro/id406239138">Apple App Store</a> ($4.99)<br>
<a target='_blank' rel="noopener" href="https://play.google.com/store/apps/details?id=com.cloudmosa.puffin">Google Play</a> ($4.99)<br>
<br>
Photon Flash Player & Browser<br>
<a target='_blank' rel="noopener" href="https://itunes.apple.com/us/app/photon-flash-player-for-iphone-flash-video-games-plus/id453546382">Apple App Store</a> ($3.99)<br>
<a target='_blank' rel="noopener" href="https://play.google.com/store/apps/details?id=com.appsverse.photon">Google Play</a> (Free)<br>
<h2><a name="tips">Tips</a></h2>
<ul>
    <li>If you have not yet allowed Flash to run on a given site, many sites will ask you to "download" Flash. If you already
        have Flash installed, these links to "download" will usually bring up the prompt to simply allow Flash.
    </li>
    <li>You can update Flash by reinstalling it.</li>
</ul>
</div>
HTML;

            $box = new \Widget\Box( array(
                'content'		=> array( 
                                    array( 'title' => '-', 'content' => $html ),
                                    ),
                'title'			=> 'Flash Player Guide',
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