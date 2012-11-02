<?php
/*
Plugin Name: WP YouTube Player
Plugin URI: http://blog.unijimpe.net/wp-youtube-player/
Description: Insert Youtube Videos on WordPress blog using quicktag <code>[tube][/tube]</code>
Version: 1.7
Author: unijimpe
Author URI: http://blog.unijimpe.net
*/

// Define global params
$wptube_version	= "1.7";											// version of plugin 
$wptube_random	= substr(md5(uniqid(rand(), true)),0,4);			// create unique id for divs
$wptube_number	= 0; 												// number of swf into page
$wptube_params	= array (
						"wptube_width"				=>	"560",		// Player width
						"wptube_height"				=>	"344",		// Player height
						"wptube_method"				=>	"object",	// Embed Method
						"wptube_player_version"		=>	"as3",		// Player Version
						
						"wptube_showinfo"			=>	"1",		// Display information Video
						"wptube_autoplay"			=>	"0",		// Autoplay Video
						"wptube_theme"				=>	"dark",		// Player them
						
						"wptube_fs"					=>	"0",		// Enable Fullscreen Button
						"wptube_rel"				=>	"1",		// Show Related videos
						"wptube_cc_load_policy"		=>	"1",		// Show Close Captions
						"wptube_iv_load_policy"		=>	"1",		// Display Annotations in Videos
						
						"wptube_hd"					=>	"0",		// Enable default HD playback
						"wptube_showsearch"			=>	"1",		// Show Search Box
						
						"wptube_modestbranding"		=>	"0"			// show a YouTube logo
						);

// Define general options
add_option("wptube_width",			$wptube_params["wptube_width"]);
add_option("wptube_height",			$wptube_params["wptube_height"]);
add_option("wptube_method",			$wptube_params["wptube_method"]);
add_option("wptube_player_version",	$wptube_params["wptube_player_version"]);

add_option("wptube_showinfo", 		$wptube_params["wptube_showinfo"]);
add_option("wptube_autoplay",		$wptube_params["wptube_autoplay"]);
add_option("wptube_theme",			$wptube_params["wptube_theme"]);

add_option("wptube_fs",				$wptube_params["wptube_fs"]);
add_option("wptube_rel",			$wptube_params["wptube_rel"]);
add_option("wptube_iv_load_policy", $wptube_params["wptube_iv_load_policy"]);
add_option("wptube_cc_load_policy", $wptube_params["wptube_cc_load_policy"]);

add_option("wptube_hd", 			$wptube_params["wptube_hd"]);
add_option("wptube_showsearch", 	$wptube_params["wptube_showsearch"]);

add_option("wptube_modestbranding",	$wptube_params["wptube_modestbranding"]);


function getConfigTube() {
	// get config options
	global $wptube_params;
    static $config;
    if ( empty($config) ) {
		foreach( $wptube_params as $option => $default) {
			$config[$option] = get_option($option);
		}
    }
    return $config;
}
function parseTube($text) {
	// regexp for find swfs
    return preg_replace_callback('|\[tube\](.+?)(,(.+?))?\[/tube\]|i', 'writeTube', $text);
}
function writeTube($match) {
    global $wptube_random, $wptube_number;
	$wptube_config = getConfigTube();
	$wptube_number++;
	$wptube_text = "";
	
	$wptube_path = trim(str_replace("&#038;", "&", $match[1]));
	$wptube_width = $wptube_config['wptube_width'];		// video width
	$wptube_height = $wptube_config['wptube_height'];	// video height
	$wptube_method = $wptube_config['wptube_method'];	// embed method
	$wptube_tubeID = "";								// video ID
	
	// verify width and height 
	if ($match[3] != "") {
		$tmatch = explode(",", $match[3]);
		$wptube_width = trim($tmatch[0]);
		$wptube_height = trim($tmatch[1]);
	}
	
	// use 'object' method in feed
	if (is_feed() || $doing_rss) {
		$wptube_method = "object";
	}
	// use 'iphone' method in iPhone
	if (!(strpos($_SERVER['HTTP_USER_AGENT'], "iPhone") === false)) {
		$wptube_method = "iphone";
	}
	
	
	// Parse youtube url and get video id
	$wptube_tube = parse_url($wptube_path);
	if ($wptube_tube["host"] == "www.youtube.com") {
		if ($wptube_tube["path"] == "/watch") {
			parse_str($wptube_tube["query"], $tube_que);
			if ($tube_que["v"] != "") { 
				$wptube_tubeID = $tube_que["v"];
			}
		} else {
			parse_str($wptube_tube["path"], $tube_que);
			if (key($tube_que) != "") {
				$wptube_tubeID = substr(key($tube_que),3);
			} 
		}
	} else {
		$wptube_tubeID = $wptube_path;
	}
	// generate video url
	if ($wptube_method == "iframe") {
		$wptube_path = "http://www.youtube.com/embed/".$wptube_tubeID."?";
		if ($wptube_config['wptube_autoplay'] == "1") {
			$wptube_path.= "&amp;autoplay=1";
		}
		if ($wptube_config['wptube_showinfo'] == "0") {
			$wptube_path.= "&amp;showinfo=0";
		}
		if ($wptube_config['wptube_theme'] != "") {
			$wptube_path.= "&amp;theme=".$wptube_config['wptube_theme'];
		}
	} else {
		switch ($wptube_config['wptube_player_version']) {
			case "as3":
				$wptube_path = "http://www.youtube.com/v/".$wptube_tubeID."?version=3";
				if ($wptube_config['wptube_autoplay'] == "1") {
					$wptube_path.= "&amp;autoplay=1";
				}
				if ($wptube_config['wptube_showinfo'] == "0") {
					$wptube_path.= "&amp;showinfo=0";
				}
				if ($wptube_config['wptube_theme'] != "") {
					$wptube_path.= "&amp;theme=".$wptube_config['wptube_theme'];
				}
				
				if ($wptube_config['wptube_fs'] != "") {
					$wptube_path.= "&amp;fs=".$wptube_config['wptube_fs'];
				}
				if ($wptube_config['wptube_rel'] == "0") {
					$wptube_path.= "&amp;rel=0";
				}
				if ($wptube_config['wptube_cc_load_policy'] == "1") {
					$wptube_path.= "&amp;cc_load_policy=1";
				}
				if ($wptube_config['wptube_iv_load_policy'] != "") {
					$wptube_path.= "&amp;iv_load_policy=".$wptube_config['wptube_iv_load_policy'];
				}
				
				if ($wptube_config['wptube_modestbranding'] != "") {
					$wptube_path.= "&amp;modestbranding=".$wptube_config['wptube_modestbranding'];
				}
				break;
			case "as2":
				$wptube_path = "http://www.youtube.com/v/".$wptube_tubeID."?feature=player_embedded";
				if ($wptube_config['wptube_autoplay'] == "1") {
					$wptube_path.= "&amp;autoplay=1";
				}
				if ($wptube_config['wptube_showinfo'] == "0") {
					$wptube_path.= "&amp;showinfo=0";
				}
				if ($wptube_config['wptube_theme'] != "") {
					$wptube_path.= "&amp;theme=".$wptube_config['wptube_theme'];
				}
				
				if ($wptube_config['wptube_fs'] != "") {
					$wptube_path.= "&amp;fs=".$wptube_config['wptube_fs'];
				}
				if ($wptube_config['wptube_rel'] == "0") {
					$wptube_path.= "&amp;rel=0";
				}
				if ($wptube_config['wptube_cc_load_policy'] == "1") {
					$wptube_path.= "&amp;cc_load_policy=1";
				}
				if ($wptube_config['wptube_iv_load_policy'] != "") {
					$wptube_path.= "&amp;iv_load_policy=".$wptube_config['wptube_iv_load_policy'];
				}
				
				if ($wptube_config['wptube_hd'] == "1") {
					$wptube_path.= "&amp;hd=1";
				}
				if ($wptube_config['wptube_showsearch'] == "0") {
					$wptube_path.= "&amp;showsearch=0";
				}
				break;
			case "tubeplayer":
				$wptube_path = WP_PLUGIN_URL."/wp-youtube-player/tubeplayer.swf?videoId=".$wptube_tubeID;
				if ($wptube_config['wptube_autoplay'] == "1") {
					$wptube_path.= "&amp;autoPlay=true";
				}
				break;
		}
	}
	
	// Write code for embed
	switch ($wptube_method) {
		case "iphone":
			$wptube_text = "<object type=\"application/x-shockwave-flash\" width=\"".$wptube_width."\" height=\"".$wptube_height."\" data=\"".$wptube_path."\">";
			$wptube_text.= "<param name=\"src\" value=\"".$wptube_path."\" />\n";
			$wptube_text.= "<param name=\"wmode\" value=\"transparent\" />";
			$wptube_text.= "</object>\n";
			break;
		case "iframe":
			$wptube_text = "<iframe type=\"text/html\" width=\"".$wptube_width."\" height=\"".$wptube_height."\" src=\"".$wptube_path."\" frameborder=\"0\"></iframe>";
			break;
		case "object":
			// Use XHTML code
			$wptube_text.= "\n<object width=\"".$wptube_width."\" height=\"".$wptube_height."\">\n";
			$wptube_text.= "<param name=\"movie\" value=\"".$wptube_path."\"></param>\n";
			$wptube_text.= "<param name=\"allowScriptAccess\" value=\"always\"></param>\n";
			if ($wptube_config['wptube_fs'] == "1") {
				$wptube_text.= "<param name=\"allowFullScreen\" value=\"true\"></param>\n";
			}
			$wptube_text.= "<embed src=\"".$wptube_path."\" type=\"application/x-shockwave-flash\" allowScriptAccess=\"always\"";
			if ($wptube_config['wptube_fs'] == "1") {
				$wptube_text.= " allowfullscreen=\"true\"";
			}
			$wptube_text.= " width=\"".$wptube_width."\" height=\"".$wptube_height."\"></embed>\n";
			$wptube_text.= "</object>\n";
			break;
		case "swfobject":
			// Use SWFObject 2.0 code
			$wptube_params = "allowScriptAccess: \"always\", ";
			if ($wptube_config['wptube_fs'] == "1") {
				$wptube_params.= ", allowFullScreen: \"true\"";
			}
			$wptube_text = "<div id=\"swf".$wptube_random.$wptube_number."\">".$wpswf_config['swf_message']."</div>";
			$wptube_text.= "\n<script type=\"text/javascript\">\n";
			$wptube_text.= "\tswfobject.embedSWF(\"".$wptube_path."\", \"swf".$wptube_random.$wptube_number."\", \"".$wptube_width."\", \"".$wptube_height."\", \"9\", \"\", {}, {".$wptube_params."}, {});\n";
			$wptube_text.= "</script>\n";
			break;
		default:
			$wptube_text = "";
			break;
	}
	return $wptube_text;
}

function wp_youtubeplayer($path, $width="", $heigth="") {
	if ($width == "" && $heigth == "")  {
    	echo writeTube( array(null, $path) );
	} else {
		echo writeTube( array(null, $path, "", $width.", ".$heigth) );	
	}
}

function showConfigPageTube() {
	// update general options
	global $wptube_version, $wptube_params;
	if (isset($_POST['wptube_update'])) {
		check_admin_referer();
		foreach( $wptube_params as $option => $default ) {
			$wptube_param = trim($_POST[$option]);
			if ($wptube_param == "") {
				$wptube_param = $default;
			}
			update_option($option, $wptube_param);
		}
		echo "<div class='updated'><p><strong>WP Youtube Player options updated</strong></p></div>";
	}
	$wptube_config = getConfigTube();
?>
		<form method="post" action="options-general.php?page=wp-youtube-player.php">
		<div class="wrap">
			<h2>WP Youtube Player <sup style='color:#D54E21;font-size:12px;'><?php echo $wptube_version; ?></sup></h2>
            <p>
            	WP Youtube Allow insert Youtube videos into Wordpress blog using quicktag <code>[tube][/tube]</code> tag.
			</p>
            <h3>Player Options</h3>
            <table class="form-table">
            	<tr>
                    <th scope="row">
                        <label for="wptb_method">Embed Method</label>
                    </th>
                    <td>
                    	<div id="diviframe">
                            <input type="radio" name="wptube_method" id="wptube_method_iframe" value="iframe"<?php if ($wptube_config["wptube_method"] == "iframe") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_method_iframe">Use <code>&lt;iframe&gt;</code> </label>
                        </div>
                    	<div>
                            <input type="radio" name="wptube_method" id="wptube_method_object" value="object"<?php if ($wptube_config["wptube_method"] == "object") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_method_object">Use <code>&lt;object&gt;</code> <em>(AS3 &amp; AS2 Players)</em></label>
                        </div>
                        <div>
                            <input type="radio" name="wptube_method" id="wptube_method_swfobject" value="swfobject"<?php if ($wptube_config["wptube_method"] == "swfobject") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_method_swfobject">Use <code>SWFObject</code> <em>(AS3 &amp; AS2 Players)</em></label>
                        </div>
                    </td>
                </tr>
            	<tr class="embed">
                    <th scope="row">
                        <label for="wptube_player_version">Player Version</label>
                    </th>
                    <td>
                    	<div>
                            <input type="radio" name="wptube_player_version" id="wptube_player_version_as3" value="as3"<?php if ($wptube_config["wptube_player_version"] == "as3") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_player_version_as3">Use AS3 player</label>
                        </div>
                        <div>
                            <input type="radio" name="wptube_player_version" id="wptube_player_version_as2" value="as2"<?php if ($wptube_config["wptube_player_version"] == "as2") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_player_version_as2">Use AS2 player</label>
                        </div>
                        <div>
                            <input type="radio" name="wptube_player_version" id="wptube_player_version_tubeplayer" value="tubeplayer"<?php if ($wptube_config["wptube_player_version"] == "tubeplayer") { echo " checked=\"checked\""; } ?> />
                            <label for="wptube_player_version_as2">Use tubePlayer</label>
                        </div>
                    </td>
                </tr>
            	<tr>
                    <th scope="row">
                        <label for="wptube_width">Player width</label>
                    </th>
                    <td>
                        <input type="text" name="wptube_width" id="wptube_width" value="<?php echo $wptube_config["wptube_width"]; ?>" style="width:100px;" />
                        <span class="description">Player width in pixels</span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="wptube_height">Player height</label>
                    </th>
                    <td>
                        <input type="text" name="wptube_height" id="wptube_height" value="<?php echo $wptube_config["wptube_height"]; ?>" style="width:100px;" />
                        <span class="description">Player height in pixels</span>
                    </td>
                </tr>
			</table>
            <br class="all" />
            <h3 class="all">Basic embed options</h3>
			<table class="form-table">
            	<tr class="all">
                    <th scope="row">
                        <label for="wptube_showinfo">Show video info</label>
                    </th>
                    <td>
                        <select name="wptube_showinfo" id="wptube_showinfo" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_showinfo"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_showinfo"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="all tube">
                    <th scope="row">
                        <label for="wptube_autoplay">Autoplay video</label>
                    </th>
                    <td>
                        <select name="wptube_autoplay" id="wptube_autoplay" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_autoplay"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_autoplay"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="all">
                    <th scope="row">
                        <label for="wptube_theme">Player theme</label>
                    </th>
                    <td>
                        <select name="wptube_theme" id="wptube_theme" style="width:100px;">
                            <option value="dark" <?php if ($wptube_config["wptube_theme"] == "dark") { echo "selected=\"selected\""; } ?>>dark</option>
                            <option value="light" <?php if ($wptube_config["wptube_theme"] == "light") { echo "selected=\"selected\""; } ?>>light</option>
                        </select>
                    </td>
                </tr>
			</table>
            <br class="as2as3" />
            <h3 class="as2as3">Extra embed options</h3>
            <table class="form-table">
            	<tr class="as2as3 tube">
                    <th scope="row">
                        <label for="wptube_fs">Enable fullscreen</label>
                    </th>
                    <td>
                        <select name="wptube_fs" id="wptube_fs" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_fs"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_fs"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="as2as3">
                    <th scope="row">
                        <label for="wptube_rel">Show related videos</label>
                    </th>
                    <td>
                        <select name="wptube_rel" id="wptube_rel" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_rel"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_rel"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="as2as3">
                    <th scope="row">
                        <label for="wptube_annotations">Show close captions</label>
                    </th>
                    <td>
                        <select name="wptube_cc_load_policy" id="wptube_cc_load_policy" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_cc_load_policy"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_cc_load_policy"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="as2as3">
                    <th scope="row">
                        <label for="wptube_iv_load_policy">Show annotations</label>
                    </th>
                    <td>
                        <select name="wptube_iv_load_policy" id="wptube_iv_load_policy" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_iv_load_policy"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="3" <?php if ($wptube_config["wptube_iv_load_policy"] == "3") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="as2">
                    <th scope="row">
                        <label for="wptube_hd">Enable default HD playback</label>
                    </th>
                    <td>
                        <select name="wptube_hd" id="wptube_hd" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_hd"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_hd"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
               </tr>
               <tr class="as2">
                    <th scope="row">
                        <label for="wptube_showsearch">Show Search Box</label>
                    </th>
                    <td>
                        <select name="wptube_showsearch" id="wptube_showsearch" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_showsearch"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_showsearch"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
                <tr class="as3">
                    <th scope="row">
                        <label for="wptube_modestbranding">Hide Youtube logo</label>
                    </th>
                    <td>
                        <select name="wptube_modestbranding" id="wptube_modestbranding" style="width:100px;">
                            <option value="1" <?php if ($wptube_config["wptube_modestbranding"] == "1") { echo "selected=\"selected\""; } ?>>true</option>
                            <option value="0" <?php if ($wptube_config["wptube_modestbranding"] == "0") { echo "selected=\"selected\""; } ?>>false</option>
                        </select>
                    </td>
                </tr>
            </table>
            <p class="submit">
              <input name="wptube_update" value="Save Changes" type="submit" class="button-primary" />
            </p>
            <table>
                <tr>
                    <th width="30%" style="padding-top: 10px; text-align:left;" colspan="2">
                        How to use WP Youtube Player
                  </th>
                </tr>
                <tr>
                    <td colspan="2">
                    	<p>To include Youtube Videos on post content or Text Widget can use code:</p>
                    	<p>Use Youtube URL: <code>[tube]http://www.youtube.com/watch?v=AFVlJAi3Cso[/tube]</code></p>
               			<p>Use Youtube Embed URL: <code>[tube]http://www.youtube.com/v/AFVlJAi3Cso[/tube]</code></p>
                		<p>Use Youtube Video ID: <code>[tube]AFVlJAi3Cso[/tube]</code></p>
            		</td>
                </tr>
                <tr>
                    <th width="30%" style="padding-top: 10px; text-align:left;" colspan="2">
                        More Information and Support
                    </th>
                </tr>
            	<tr>
                	<td colspan="2">
                      <p>Check our links for updates and comment there if you have any problems / questions / suggestions. </p>
                      <ul>
                        <li><a href="http://blog.unijimpe.net/wp-youtube-player/">Plugin Home Page</a></li>
                        <li><a href="http://blog.unijimpe.net/tubeplayer/">tubePlayer Home Page</a></li>
                        <li><a href="http://blog.unijimpe.net/">Author Home Page</a></li>
                        <li><a href="https://developers.google.com/youtube/player_parameters">YouTube Embedded Players and Player Parameters</a></li>
                    </ul>
                    <p>&nbsp;</p>
                    </td>
              </tr>
            </table>
    </div>
    </form>
    <script type="text/javascript">
		jQuery(document).ready(function(){
			var method = "<?php echo $wptube_config["wptube_method"]; ?>";
			var player = "<?php echo $wptube_config["wptube_player_version"]; ?>";
			
			function renderOptions() {
				switch (method) {
					case "iframe":
						jQuery('.all').show();
						jQuery('.as2as3, .as2, .as3, .embed').hide();
						break;
					case "object":
					case "swfobject":
						jQuery('.embed').show();
						jQuery('.all').show();
						switch (player) {
							case "as3":
								jQuery('.as2as3, .as3').show();
								jQuery('.as2').hide();
								break;
							case "as2":
								jQuery('.as2as3, .as2').show();
								jQuery('.as3').hide();
								break;
							case "tubeplayer":
								jQuery('.as2as3, .as2, .as3, .all').hide();
								jQuery('.tube').show();
								break;	
						}
						break;
				}
			}
			jQuery('#wptube_method_iframe, #wptube_method_object, #wptube_method_swfobject').click(function(e) {
				method = jQuery(this).val();
				renderOptions();
            });
			jQuery('#wptube_player_version_as3, #wptube_player_version_as2, #wptube_player_version_tubeplayer').click(function(e) {
				player = jQuery(this).val();
                renderOptions();
            });
			
			renderOptions();
		});
	</script>
<?php
}
function addMenuTube() {
	// add menu options
	add_options_page('WP Youtube Player Options', 'WP Youtube Player', 'manage_options', basename(__FILE__), 'showConfigPageTube');
}
function addHeaderTube() {
	// Add SWFObject to header
	global $wptube_version;
	echo "\n<!-- WP Youtube Player ".$wptube_version." by unijimpe -->\n";
	if (get_option('wptube_method') == "swfobject") { 
	echo "<script src=\"http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js\" type=\"text/javascript\"></script>\n";
	}
}

add_filter('the_content', 'parseTube');
add_filter('widget_text', 'parseTube');

add_action('wp_head', 'addHeaderTube');
add_action('admin_menu', 'addMenuTube');
?>