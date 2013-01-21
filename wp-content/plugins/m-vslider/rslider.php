<?php
/*
  Plugin Name: M-vSlider
  Plugin URI: http://mamirulamin.wordpress.com/2010/08/10/mvslider-multi-sliders-clone-of-vslider/
  Description: Implementing a featured image gallery into your WordPress theme has never been easier! Showcase your portfolio, animate your header or manage your banners with M-vSlider. M-vSlider by  Muhammad Amir Ul Amin.
  Author: M. Amir Ul Amin
  Author URI: http://www.nimble3.com
  Version: 2.1.3

  M-vSlider is released under GPL:
  http://www.opensource.org/licenses/gpl-license.php
 */

// Load jQuery from WordPress
function rslider_enqueue() {
    wp_enqueue_style('nivo-slider-style', WP_PLUGIN_URL . '/m-vslider/nivo-slider.css', null, null, 'screen');
    wp_enqueue_script('jquery-nivo-slider', WP_PLUGIN_URL . '/m-vslider/jquery.nivo.slider.js', array('jquery'));
}

// M-vSlider Theme Head
function rslider_head() {
    global $wpdb;
    global $table_slider;

    $mainsql = " SELECT * FROM $table_slider order by rs_id";

    if ($mainrows = $wpdb->get_results($mainsql)) {
        ?>
        <?php
        $rs_style_tag = "";
        $rs_script_tag = "";
        foreach ($mainrows as $myslider) {
            $rs_id = $myslider->rs_id;
            $rs_options = unserialize($myslider->rs_options);
            $rs_name = $myslider->rs_name;
            $rs_width = $myslider->rs_width;
            $rs_height = $myslider->rs_height;
            $rs_speed = $myslider->rs_speed;
            $rs_animstyle = $myslider->rs_animstyle;
            $rs_css = $myslider->rs_css;
            $rs_timeout = $myslider->rs_timeout * 1000;
            $rs_type = $myslider->rs_type;
            $rs_type = $rs_type == "random_start" ? 'true' : 'false';
            $rs_showdir = $rs_options['rs_showdir'];
            $rs_showdir = $rs_showdir ? 'true' : 'false';
            $rs_shownav = $rs_options['rs_shownav'];
            $rs_shownav = $rs_shownav ? 'true' : 'false';
            $rs_showhover = $rs_options['rs_showhover'];
            $rs_showhover = $rs_showhover && $rs_showdir ? 'true' : 'false';
            $rs_layout = $rs_options['rs_layout'];
            $rs_slices = $rs_options['rs_slices']?$rs_options['rs_slices']:15;
            $rs_boxCols = $rs_options['rs_boxCols']?$rs_options['rs_boxCols']:8;
            $rs_boxRows = $rs_options['rs_boxRows']?$rs_options['rs_boxRows']:4;
            $layout = !$rs_layout?"width: {$rs_width}px; height: {$rs_height}px;":"";
            $rs_style_tag .= "#rslider$rs_id { $layout $rs_css }\n";
            $rs_script_tag .= "jQuery('#rslider$rs_id').nivoSlider({ 
                                    effect: '$rs_animstyle',
                                    slices: $rs_slices,
                                    boxCols: $rs_boxCols,
                                    boxRows: $rs_boxRows,
                                    animSpeed: $rs_speed, 
                                    pauseTime: $rs_timeout,
                                    directionNav: $rs_showdir, 
                                    directionNavHide: $rs_showhover, 
                                    controlNav: $rs_shownav, 
                                    controlNavThumbs: false,
                                    pauseOnHover: true, 
                                    randomStart: $rs_type
                                });\n";
        }
        ?>
        <style type="text/css">
        <?php echo $rs_style_tag; ?>
        </style>
        <script type="text/javascript">
            /*** M-vSlider Init ***/
            jQuery.noConflict();
            jQuery(window).load(function(){
        <?php echo $rs_script_tag; ?>
            });
        </script>
        <?php
    }
}

// M-vSlider Function
function rslider($atts = 0) {

    $rs_id = $atts;
    if ($atts["id"])
        $rs_id = $atts["id"];

    global $wpdb;
    global $table_slider;
    $rs_sql = " SELECT * FROM $table_slider WHERE rs_id ='$rs_id'";
    if ($rs_rows = $wpdb->get_results($rs_sql)) {
        if ($rs_rows[0]) {
            $rs_row = $rs_rows[0];
            $rs_options = unserialize($rs_row->rs_options);
            $rs_theme = $rs_options['rs_theme'];
            wp_enqueue_style('nivo-slider-theme', WP_PLUGIN_URL . "/m-vslider/themes/$rs_theme/$rs_theme.css", null, null, 'screen');
            ?>
            <div class="slider-wrapper theme-<?php echo $rs_theme;?>" style="<?php echo !$rs_options['rs_layout']?"width: {$rs_row->rs_width}px;":"";?>">
                <div id="rslider<?php echo $rs_id; ?>" class="nivoSlider">
                    <?php
                    $rs_images = unserialize($rs_row->rs_images);
                    if (!empty($rs_images)) {
                        foreach ($rs_images as $rs_image) {
                            if ($rs_image['img']) {
								if ($rs_image['url']){ 
                                ?>
									<a href="<?php echo stripslashes($rs_image['url']); ?>" <?php echo (($rs_image['blank']) ? ' target="_blank" ' : ''); ?>>
										<img src="<?php echo stripslashes($rs_image['img']); ?>" alt="<?php echo $rs_image['cap']; ?>" title="<?php echo $rs_image['cap']; ?>" />
									</a>
                                <?php
                                } else { ?>
									<img src="<?php echo stripslashes($rs_image['img']); ?>" alt="<?php echo $rs_image['cap']; ?>" title="<?php echo $rs_image['cap']; ?>" />
								<?php 
								} //if url
                            }//if img
                        }//foreach
                    }//if
                    ?>
                </div>
            </div>
            <?php
        }
    }
}

function get_rslider($atts = 0) {

    ob_start(); // start buffer
    rslider($atts);
    $content = ob_get_contents(); // assign buffer contents to variable
    ob_end_clean(); // end buffer and remove buffer contents
    return $content;
}

// Add M-vSlider Short Code
add_shortcode('m-vslider', 'get_rslider');

// Register M-vSlider As Widget
add_action('widgets_init', create_function('', "register_widget('rslider_widget');"));

class rslider_widget extends WP_Widget {

    function rslider_widget() {
        $widget_ops = array('classname' => 'rslider-widget', 'description' => 'jQuery based image slider');
        $control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'rslider-widget');
        $this->WP_Widget('rslider-widget', 'M-vSlider - Image Slider', $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);

        echo $before_widget;

        if (!empty($instance['title']) && !empty($instance['rs_id']))
            echo $before_title . $instance['title'] . $after_title;

        rslider($instance['rs_id']);

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function form($instance) {

        global $wpdb;
        global $table_slider;
        $mainsql = " SELECT * FROM $table_slider order by rs_id";

        if ($mainrows = $wpdb->get_results($mainsql)) {
            ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title"); ?>:</label>
                <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" /></p>
            <p><label for="<?php echo $this->get_field_id('rs_id'); ?>"><?php _e("M-vSlider ID"); ?>:</label>
                <select id="<?php echo $this->get_field_id('rs_id'); ?>" name="<?php echo $this->get_field_name('rs_id'); ?>" value="<?php echo $instance['rs_id']; ?>" style="width:95%;">
                    <?php
                    foreach ($mainrows as $myslider) {
                        $rs_id = $myslider->rs_id;
                        $rs_name = $myslider->rs_name;
                        ?>			
                        <option value="<?php echo $rs_id; ?>" <?php echo ($instance['rs_id'] == $rs_id ? " selected " : ""); ?>>[<?php echo $rs_id; ?>]-> <?php echo $rs_name; ?></option>
                        <?php
                    }
                    ?>				
                </select>
            </p>
            <?php
        }
    }

}

// Add The Option Page to WordPress Dashboard
function rslider_addPage() {
    add_menu_page('M-vSlider Setup', 'M-vSlider Setup', 'add_users', 'rslider_page', 'rslider_page');
}

// rslide Options Page
function rslider_page() {

    global $wpdb;
    global $table_slider;
    $rs_config_count = 5;
    if ($_GET['rs_action'] == 'rs_remove') {
        $wpdb->query("DELETE FROM $table_slider WHERE rs_id = '" . $_GET['rs_id'] . "'");
        $_GET['rs_id'] = '';
    }


    if ($_POST['rs_addnew'] && $_POST['rs_name']) {
        $rows_affected = $wpdb->insert($table_slider, array('rs_id' => '', 'rs_name' => $_POST['rs_name'], 'rs_css' => 'margin: 0px 0px 0px 0px;padding: 0;border: none;'));
        if ($rows_affected == 1) {
            $_GET['rs_id'] = $wpdb->insert_id;
        }
    }

    $currurl = $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'];
    if (!$_GET['rs_id'] && !$_POST['rs_id']) {

        $sql = " 	SELECT * FROM $table_slider order by rs_id";

        $rows = $wpdb->get_results($sql);
        ?>
        <div class="wrap" id="rslider-panel">
            <div id="icon-options-general" class="icon32"><br /></div>
            <h2><?php _e("M-vSlider - Home"); ?></h2>		
            <div id='css_rs_main'>
                <table id='css_rs_table'>
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>ID</th>
                            <th>Shortcode</th>
                            <th style="width:200px;">Use in Template/PHP code</th>
                            <th>Name</th>
                            <th>W x H (px)</th>
                            <th>Speed</th>
                            <th>Timeout</th>
                            <th>Anim. Style</th>
                            <th>Type</th>
                            <th>Custom CSS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $style_a['fade'] = 'Fade';
                        $style_a['slide'] = 'Slide';

                        $type_a['sequence'] = 'Sequence';
                        //$type_a['random'] = 'Random';
                        $type_a['random_start'] = 'Random Start';

                        if ($rows = $wpdb->get_results($sql)) {
                            foreach ($rows as $row) {
                                ?>
                                <tr>
                                    <td align="center"><a href="<?php echo $currurl; ?>&rs_id=<?php echo $row->rs_id; ?>&rs_action=rs_edit" title="Edit"><img src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/edit.png"></a>&nbsp;&nbsp;<a href="<?php echo $currurl; ?>&rs_id=<?php echo $row->rs_id; ?>&rs_action=rs_remove" title="Delete" onclick="return confirm('Are you sure, you want to delete this slider?');"><img src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/remove.png"></a></td>
                                    <td><?php echo $row->rs_id; ?></td>
                                    <td>
                                        <pre>[m-vslider id="<?php echo $row->rs_id; ?>"]</pre>
                                    </td>
                                    <td style="text-align: left;">
                                        <pre>
&lt;?php 
    if( function_exists('rslider') ) { 
        rslider(<?php echo $row->rs_id; ?>);
    } 
?&gt;  
                                        </pre>
                                    </td>
                                    <td><?php echo $row->rs_name; ?></td>
                                    <td><?php echo $row->rs_width . " x " . $row->rs_height; ?></td>
                                    <td><?php echo $row->rs_speed . " ms"; ?></td>
                                    <td><?php echo $row->rs_timeout . " sec"; ?></td>
                                    <td><?php echo $row->rs_animstyle; ?></td>
                                    <td><?php echo $type_a[$row->rs_type]; ?></td>
                                    <td>
                                        <div class="hidden" id="contextual-css-wrap<?php echo $row->rs_id; ?>">
                                            <div class="metabox-prefs"><pre><?php echo $row->rs_css; ?></pre></div>
                                        </div>
                                        <div id="screen-meta-links">
                                            <div class="hide-if-no-js screen-meta-toggle" id="contextual-help-link-wrap">
                                                <a class="show-settings" id="contextual-css-link<?php echo $row->rs_id; ?>" href="#" style="color:red">Show</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="11">No Slider Configured!</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <div>
                    <form method='post' action='<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>'>
                        New Slider Name: <input type=text id='rs_name' name='rs_name'>
                        <input type=submit value='Add New Slider' id='rs_addnew'  name='rs_addnew' onclick="var val=this.form.rs_name.value;if (val== null || val == '' ) {alert('Pleae enter slider name first!');return false;}">
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else {
        $cur_page = $_SERVER['PHP_SELF'] . '?page=' . $_GET['page'];
        $rs_id = $_GET['rs_id'];

        if ('process' == $_POST['tcOptions']) {

            $rs_options = array(
                'rs_showdir' => $_POST['rs_showdir'],
                'rs_shownav' => $_POST['rs_shownav'],
                'rs_showhover' => $_POST['rs_showhover'],
                'rs_layout' => $_POST['rs_layout'],
                'rs_theme' => $_POST['rs_theme'],
                'rs_slices' => $_POST['rs_slices'],
                'rs_boxRows' => $_POST['rs_boxRows'],
                'rs_boxCols' => $_POST['rs_boxCols']
            );

            $updatequery = "UPDATE $table_slider SET rs_name='" . $_POST['rs_name'] . "',";

            if ($_POST['rs_width']) {
                $updatequery .= " rs_width = '" . $_POST['rs_width'] . "',";
            }
            if ($_POST['rs_height']) {
                $updatequery .= " rs_height = '" . $_POST['rs_height'] . "',";
            }
            if ($_POST['rs_speed']) {
                $updatequery .= " rs_speed = '" . $_POST['rs_speed'] . "',";
            }
            if ($_POST['rs_animstyle']) {
                $updatequery .= " rs_animstyle = '" . $_POST['rs_animstyle'] . "',";
            }
            if ($_POST['rs_css']) {
                $updatequery .= " rs_css = '" . $_POST['rs_css'] . "',";
            }
            if ($_POST['rs_timeout']) {
                $updatequery .= " rs_timeout = '" . $_POST['rs_timeout'] . "',";
            }
            if ($_POST['rs_type']) {
                $updatequery .= " rs_type = '" . $_POST['rs_type'] . "',";
            }

            $updatequery .= " rs_options = '" . stripslashes(serialize($rs_options)) . "',";

            $rs_count = $_POST['rs_totalimgs'] > $rs_config_count ? $_POST['rs_totalimgs'] : $rs_config_count;
            $rs_images = array();
            for ($i = 0; $i < $rs_count; $i++) {
                if ($_POST["rs_img$i"]) {
                    $rs_images[] = array(
                        'img' => $_POST["rs_img$i"], 
                        'url' => $_POST["rs_lnk$i"], 
                        'cap' => $_POST["rs_cap$i"], 
                        'blank' => array_key_exists("rs_bnk$i", $_POST)
                    );
                }
            }

            $updatequery .= " rs_images = '" . stripslashes(serialize($rs_images)) . "'";

            $updatequery .= " WHERE rs_id = " . $_POST['rs_id'];

            $wpdb->query($updatequery);
            $rs_id = $_POST['rs_id'];
        }

        $myslider = $wpdb->get_row("SELECT * FROM $table_slider WHERE rs_id = '" . $rs_id . "'");
        $rs_name = $myslider->rs_name;
        $rs_width = $myslider->rs_width;
        $rs_height = $myslider->rs_height;
        $rs_speed = $myslider->rs_speed;
        $rs_animstyle = $myslider->rs_animstyle;
        $rs_css = $myslider->rs_css;
        $rs_timeout = $myslider->rs_timeout;
        $rs_type = $myslider->rs_type;
        $rs_layout = $myslider->rs_layout;
        $rs_images = unserialize($myslider->rs_images);
        $rs_options = unserialize($myslider->rs_options);

        if ($rs_width == "") {
            $rs_width = 250;
        }
        if ($rs_height == "") {
            $rs_height = 250;
        }
        if ($rs_speed == "") {
            $rs_speed = 1000;
        }
        if ($rs_timeout == "") {
            $rs_timeout = 5;
        }
        if ($rs_css == "") {
            $rs_css = "margin: 0px 0px 0px 0px; padding: 0; border: none;";
        }
        
        $rs_showdir = $rs_options['rs_showdir'];
        $rs_shownav = $rs_options['rs_shownav'];
        $rs_showhover = $rs_options['rs_showhover'];
        $rs_layout = $rs_options['rs_layout'];
        $rs_theme = $rs_options['rs_theme'];
		$rs_slices = $rs_options['rs_slices']?$rs_options['rs_slices']:15;
		$rs_boxCols = $rs_options['rs_boxCols']?$rs_options['rs_boxCols']:8;
		$rs_boxRows = $rs_options['rs_boxRows']?$rs_options['rs_boxRows']:4;

        $rs_count = count($rs_images) > $rs_config_count ? count($rs_images) : $rs_config_count;
        ?>

        <div class="wrap" id="rslider-panel"><div id="icon-options-general" class="icon32"><br /></div>
            <h2><?php _e("<a href='$currurl'>M-vSlider</a> &raquo; Slider Options"); ?></h2>
            <?php
            if ($_REQUEST['save'])
                echo '<div id="message" class="updated fade" style="width: 95.5%;"><p><strong>Slider Options Saved.</strong></p></div>';
            ?>
            <form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>&updated=true">
                <input type="hidden" name="tcOptions" value="process" />
                <input type="hidden" name="rs_id" value="<?php echo $rs_id ?>" />
                <!-- Start First Column -->
                <div class="metabox-holder">
                    <div class="postbox">
                        <h3><?php _e("Slider General Settings"); ?></h3>
                        <div class="inside">
                            <p>
                                <?php _e("Name:"); ?>&nbsp;<input type="text" name="rs_name" value="<?php echo $rs_name; ?>" size="35" />&nbsp;
                            </p>
                            <p>
                                <?php _e("Width:"); ?>&nbsp;<input type="text" name="rs_width" value="<?php echo $rs_width; ?>" size="5" />&nbsp;px&nbsp;&nbsp;
                                <?php _e("Height:"); ?>&nbsp;<input type="text" name="rs_height" value="<?php echo $rs_height; ?>" size="5" />&nbsp;px
                            </p>
                            <p>
                                <?php _e("Animation/Transition:"); ?>&nbsp;
                                <select name="rs_animstyle" id="rs_animstyle">
                                    <option style="padding-right:10px;" value="sliceDown" <?php selected('sliceDown', $rs_animstyle); ?>><?php _e("sliceDown"); ?></option>
                                    <option style="padding-right:10px;" value="sliceDownLeft" <?php selected('sliceDownLeft', $rs_animstyle); ?>><?php _e("sliceDownLeft"); ?></option>
                                    <option style="padding-right:10px;" value="sliceUp" <?php selected('sliceUp', $rs_animstyle); ?>><?php _e("sliceUp"); ?></option>
                                    <option style="padding-right:10px;" value="sliceUpLeft" <?php selected('sliceUpLeft', $rs_animstyle); ?>><?php _e("sliceUpLeft"); ?></option>
                                    <option style="padding-right:10px;" value="sliceUpDown" <?php selected('sliceUpDown', $rs_animstyle); ?>><?php _e("sliceUpDown"); ?></option>
                                    <option style="padding-right:10px;" value="sliceUpDownLeft" <?php selected('sliceUpDownLeft', $rs_animstyle); ?>><?php _e("sliceUpDownLeft"); ?></option>
                                    <option style="padding-right:10px;" value="fold" <?php selected('fold', $rs_animstyle); ?>><?php _e("fold"); ?></option>
                                    <option style="padding-right:10px;" value="fade" <?php selected('fade', $rs_animstyle); ?>><?php _e("fade"); ?></option>
                                    <option style="padding-right:10px;" value="random" <?php selected('random', $rs_animstyle); ?>><?php _e("random"); ?></option>
                                    <option style="padding-right:10px;" value="slideInRight" <?php selected('slideInRight', $rs_animstyle); ?>><?php _e("slideInRight"); ?></option>
                                    <option style="padding-right:10px;" value="slideInLeft" <?php selected('slideInLeft', $rs_animstyle); ?>><?php _e("slideInLeft"); ?></option>
                                    <option style="padding-right:10px;" value="boxRandom" <?php selected('boxRandom', $rs_animstyle); ?>><?php _e("boxRandom"); ?></option>
                                    <option style="padding-right:10px;" value="boxRain" <?php selected('boxRain', $rs_animstyle); ?>><?php _e("boxRain"); ?></option>
                                    <option style="padding-right:10px;" value="boxRainReverse" <?php selected('boxRainReverse', $rs_animstyle); ?>><?php _e("boxRainReverse"); ?></option>
                                    <option style="padding-right:10px;" value="boxRainGrow" <?php selected('boxRainGrow', $rs_animstyle); ?>><?php _e("boxRainGrow"); ?></option>
                                    <option style="padding-right:10px;" value="boxRainGrowReverse" <?php selected('boxRainGrowReverse', $rs_animstyle); ?>><?php _e("boxRainGrowReverse"); ?></option>
                                </select>
                            </p>
                            <p id="rs_slices_box">
                                <?php _e("Number of Slices:"); ?>&nbsp;<img title="Number of Slices to show in Slice animations." src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;<input readonly="readonly" type="text" name="rs_slices" value="<?php echo $rs_slices; ?>" size="5" id="rs_slices" />
                            </p>
							<div id="rs_slices-slider"> </div>
                            <p id="rs_boxCols_box">
                                <?php _e("Number of Box Columns:"); ?>&nbsp;<img title="Number of Box Columns to show in Box animations." src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;<input readonly="readonly" type="text" name="rs_boxCols" value="<?php echo $rs_boxCols; ?>" size="5" id="rs_boxCols" />
                            </p>
							<div id="rs_boxCols-slider"> </div>
                            <p id="rs_boxRows_box">
                                <?php _e("Number of Box Rows:"); ?>&nbsp;<img title="Number of Box Rows to show in Box animations." src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;<input readonly="readonly" type="text" name="rs_boxRows" value="<?php echo $rs_boxRows; ?>" size="5" id="rs_boxRows" />
                            </p>
							<div id="rs_boxRows-slider"> </div>
                            <p>
                                <?php _e("Theme:"); ?>&nbsp;
                                <select name="rs_theme">
                                    <?php 
                                        if (is_dir(M_VSLIDER_DIR_THEMES_DIR))
                                        {
                                            $thd = dir(M_VSLIDER_DIR_THEMES_DIR);
                                            while (false !== ($theme_name = $thd->read())) { 
                                                echo M_VSLIDER_DIR_THEMES_DIR . $theme_name;
                                                if( $theme_name == "." || $theme_name == ".." ) { continue; }
                                    ?>
                                                <option style="padding-right:10px;" value="<?php echo $theme_name;?>" <?php selected($theme_name, $rs_theme); ?>><?php echo ($theme_name); ?></option>
                                    <?php                                                
                                            }
                                            $thd->close();                                            
                                        }
                                    ?>
                                </select>
                                <label><input type="checkbox" id="rs_layout" name="rs_layout" value="1" <?php echo ($rs_layout?" checked='checked' ":""); ?> /> <?php _e("Resposive?"); ?>&nbsp;</label><img title="If checked, it will use responsive layout instead of fixed width and height." src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;
                            </p>
                            <p>
                                <label><input type="checkbox" id="rs_showdir" name="rs_showdir" value="1" <?php echo ($rs_showdir?" checked='checked' ":""); ?> /> <?php _e("Show directions controls"); ?>&nbsp;</label><img title="Show Next and Previous controls" src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;
                                <label><input type="checkbox" id="rs_showhover" name="rs_showhover" value="1" <?php echo ($rs_showhover?" checked='checked' ":""); ?> /> <?php _e("Only show on hover"); ?></label>
                            </p>
                            <p>
                                <label><input type="checkbox" name="rs_shownav" value="1" <?php echo ($rs_shownav?" checked='checked' ":""); ?> /> <?php _e("Show navigation controls"); ?></label>&nbsp;<img title="Show 1,2,3 navigation" src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">
                            </p>
                            <p>
                                <?php _e("Start From:"); ?>&nbsp;<img title="Start from first slide (Sequenece) or a random slide (Random Start)" src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;
                                <select name="rs_type">
                                    <option style="padding-right:10px;" value="sequence" <?php selected('sequence', $rs_type); ?>><?php _e("Sequence"); ?></option>
                                    <!--<option style="padding-right:10px;" value="random" <?php selected('random', $rs_type); ?>><?php _e("Random"); ?></option>-->
                                    <option style="padding-right:10px;" value="random_start" <?php selected('random_start', $rs_type); ?>><?php _e("Random Start"); ?></option>
                                </select>
                            </p>
                            <p>
                                <?php _e("Speed:"); ?>&nbsp;<img title="Slide transition speed" src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;<input readonly="readonly" type="text" name="rs_speed" value="<?php echo $rs_speed; ?>" size="5" id="rs_speed" />&nbsp;<?php _e("milliseconds"); ?>
                                <br />
                                <div id="rs_speed-slider"> </div>
                            </p>
                            <p>
                                <?php _e("Timeout:"); ?>&nbsp;<img title="How long each slide will show" src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png">&nbsp;<input readonly="readonly" type="text" id="rs_timeout" name="rs_timeout" value="<?php echo $rs_timeout; ?>" size="5" />&nbsp;<?php _e("seconds"); ?>
                                <br />
                                <div id="rs_timeout-slider"> </div>
                            </p>
                        </div>
                        <h3><?php _e("Custom CSS Settings"); ?></h3>
                        <div class="inside">
                            <p>Enter here custom CSS for this Slider:<br />
                                <textarea name="rs_css" style="width:350px;" rows="4"><?php echo stripslashes($rs_css); ?></textarea>
                            </p>
                            <p><input type="submit" class="button-primary" name="save" value="<?php _e('Save Settings') ?>" /></p>
                        </div>
                    </div>
                </div>
                <!-- End First Column -->
                <!-- Start Second Column -->
                <div class="metabox-holder">
                    <div class="postbox">
                        <h3><?php _e("Images Setup"); ?></h3>
                        <div class="inside">
                            <?php
                            for ($i = 0; $i < $rs_count; $i++) {
                                ?>
                                <p style="background-color:#<?php echo ($i % 2 ? 'E0E6ED;border: 1px dashed #888' : 'E6EDE0'); ?>; padding:10px;">
                                    <label for="rs_img<?php echo $i; ?>"><?php _e("Image " . ($i + 1) . " path: "); ?></label><img title="Either enter URL, or use 'Media Gallery' button to upload or insert path from gallery." src="<?php echo WP_PLUGIN_URL; ?>/m-vslider/help.png" /><br />
                                    <input type="text" id="rs_img<?php echo $i; ?>" name="rs_img<?php echo $i; ?>" value="<?php echo stripslashes($rs_images[$i]['img']); ?>" style="width:70%;" />
                                    <input class="button upload_image_button" type="button" value="Media Gallery" rel="rs_img<?php echo $i; ?>"  style="width:20%;margin:0;" /><br />
                                    <label for="rs_lnk<?php echo $i; ?>"><?php _e("Image " . ($i + 1) . " links to:"); ?></label>
                                    <input type="text" id="rs_lnk<?php echo $i; ?>" name="rs_lnk<?php echo $i; ?>" value="<?php echo stripslashes($rs_images[$i]['url']); ?>" style="width:100%;" />
                                    <label for="rs_cap<?php echo $i; ?>"><?php _e("Image " . ($i + 1) . " caption:"); ?></label>
                                    <input type="text" id="rs_cap<?php echo $i; ?>" name="rs_cap<?php echo $i; ?>" value="<?php echo stripslashes($rs_images[$i]['cap']); ?>" style="width:100%;" />
                                    <input type="checkbox" name="rs_bnk<?php echo $i; ?>" id="rs_bnk<?php echo $i; ?>" <?php echo ($rs_images[$i]['blank'] ? ' checked ' : ''); ?> value="<?php echo $i; ?>" /> <label for="rs_bnk<?php echo $i; ?>"><em>Open link in New Tab/Window</em></label>
                                </p>
                                <?php
                            }
                            ?>
                            <div id="rs_divinside"></div>
                            <p><input type="hidden" name="rs_totalimgs" id="rs_totalimgs" value="<?php echo $rs_count; ?>" /></p>
                            <p><input type="button" class="button" name="rs_addnewimg" id="rs_addnewimg" value="<?php _e('Add Another Image') ?>" /></p>
                            <p><input type="submit" class="button-primary" name="save" value="<?php _e('Save Settings') ?>" /></p>
                        </div>
                    </div>

                </div>
                <!-- End Second Column -->
            </form>
        </div>
        <?php
    }
}

function rslider_install() {
    global $wpdb;
    global $table_slider;
    global $rslider_db_version;

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $cur_rslider_db_version = get_option("rslider_db_version", null);

    if ($wpdb->get_var("show tables like '$table_slider'") != $table_slider) {
        $create_table_sql = "CREATE TABLE `$table_slider` (
								`rs_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
								`rs_name` varchar(100) NOT NULL,
								`rs_css` text,
								`rs_width` smallint(6) NOT NULL DEFAULT '250',
								`rs_height` smallint(6) NOT NULL DEFAULT '250',
								`rs_speed` int(11) NOT NULL DEFAULT '1000',
								`rs_animstyle` varchar(50) NOT NULL DEFAULT 'fade',
								`rs_timeout` smallint(6) NOT NULL DEFAULT '5',
								`rs_type` varchar(50) NOT NULL DEFAULT 'sequence',
								`rs_images` blob NOT NULL,
								`rs_options` blob NOT NULL,
								PRIMARY KEY (`rs_id`),
								UNIQUE KEY `rs_name` (`rs_name`)
								) ENGINE=MyISAM DEFAULT CHARSET=latin1";

        if ($wpdb->query($create_table_sql)!== false) {
            $rows_affected = $wpdb->insert($table_slider, array('rs_id' => '', 'rs_name' => 'Default Slider', 'rs_css' => 'margin: 0px 0px 0px 0px;padding: 0;border: none;'));
            update_option("rslider_db_version", $rslider_db_version);
        }
    } else {
        if ($cur_rslider_db_version == "1.0") { // If old DB version found then upgrade it
            // #1 -> add rs_images, and rs_options columns
            $alterquery = " ALTER TABLE $table_slider ADD `rs_images` BLOB NOT NULL, ADD `rs_options` BLOB NOT NULL";
            $wpdb->query($alterquery);

            // #2 -> update rs_images column from current image columns
            $mainsql = " SELECT * FROM $table_slider order by rs_id";

            if ($mainrows = $wpdb->get_results($mainsql, ARRAY_A)) {
                foreach ($mainrows as $myslider) {
                    $rs_images = array();
                    for ($i = 0; $i < 10; $i++) {
                        if ($myslider["rs_img$i"]) {
                            $rs_images[] = array('img' => $myslider["rs_img$i"], 'url' => $myslider["rs_lnk$i"], 'blank' => '');
                        }
                    }
                }
                $wpdb->update($table_slider, array('rs_images' => serialize($rs_images)), array('rs_id' => $myslider['rs_id']), array('%s'), array('%d'));
            }

            // #3 -> remove all current image columns
            $alterquery = " ALTER TABLE $table_slider
							DROP `rs_img1`,
							DROP `rs_lnk1`,
							DROP `rs_img2`,
							DROP `rs_lnk2`,
							DROP `rs_img3`,
							DROP `rs_lnk3`,
							DROP `rs_img4`,
							DROP `rs_lnk4`,
							DROP `rs_img5`,
							DROP `rs_lnk5`,
							DROP `rs_img6`,
							DROP `rs_lnk6`,
							DROP `rs_img7`,
							DROP `rs_lnk7`,
							DROP `rs_img8`,
							DROP `rs_lnk8`,
							DROP `rs_img9`,
							DROP `rs_lnk9`,
							DROP `rs_img10`,
							DROP `rs_lnk10`";
            if ($wpdb->query($alterquery)!== false)
                update_option("rslider_db_version", $rslider_db_version);
        } elseif ($cur_rslider_db_version == "1.1") { // If old DB version is 1.1 then update DB Table
            $alterquery = " ALTER TABLE $table_slider
                                                    MODIFY `rs_css` text ,
                                                    MODIFY `rs_animstyle` varchar(50) DEFAULT 'fade',
                                                    MODIFY `rs_type` varchar(50) DEFAULT 'sequence'";
            if ($wpdb->query($alterquery)!== false)
                update_option("rslider_db_version", $rslider_db_version);
        }//
    }
}

function rslider_admin_enqueue() {

    if ($_REQUEST['page'] == 'rslider_page') {
        wp_enqueue_style('rslider-admin-style', WP_PLUGIN_URL . '/m-vslider/rslider-admin.css', null, null, 'screen');
        wp_enqueue_style('rslider-admin-ui-slider', WP_PLUGIN_URL . '/m-vslider/jquery.ui.slider.css');
        wp_enqueue_style('rslider-admin-ui-theme', WP_PLUGIN_URL . '/m-vslider/jquery.ui.theme.css');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-ui-slider', 'js/ui.slider.js', array('jquery'));
        wp_enqueue_script('rslider-admin-script', WP_PLUGIN_URL . '/m-vslider/rslider-admin.js', array('jquery', 'jquery-ui-slider','media-upload','thickbox'));
        ?>
        <!-- M-vSlider - Start -->
        <script type="text/javascript">
            /*** M-vSlider Init ***/
            jQuery.noConflict();
            jQuery(document).ready(function() {
				var imgfield='';
				jQuery('.upload_image_button').click(function() {
					imgfield = jQuery(this).attr('rel');
					var imglabel = jQuery("label[for='"+imgfield+"']").text();
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
					jQuery("#TB_iframeContent").load(function(){
						var _this = jQuery(this);
						jQuery("input[value='Insert into Post']", frames[_this.attr('name')].document).each(function(){jQuery(this).val('Insert as ' + imglabel);});
					});
					return false;
				});

				window.send_to_editor = function(html) {
					imgurl = jQuery('img',html).attr('src');
					jQuery('#'+imgfield).val(imgurl);
					tb_remove();
				}
				 
				
        <?php
        global $wpdb;
        global $table_slider;
        $mainsql = " SELECT * FROM $table_slider order by rs_id";

        if ($mainrows = $wpdb->get_results($mainsql)) {
            foreach ($mainrows as $myslider) {
                $rs_id = $myslider->rs_id;
                ?>
                                jQuery('#contextual-css-link<?php echo $rs_id; ?>').click(function(e) {
                                    e.preventDefault();
                                    if (jQuery('#contextual-css-wrap<?php echo $rs_id; ?>').hasClass('contextual-help-open'))
                                    {
                                        jQuery(this).text('Show');
                                        jQuery(this).css('background-position', 'right top');
                                        jQuery('#contextual-css-wrap<?php echo $rs_id; ?>').removeClass('contextual-help-open');
                                        jQuery('#contextual-css-wrap<?php echo $rs_id; ?>').hide();
                                    }
                                    else
                                    {
                                        jQuery(this).text('Hide');
                                        jQuery(this).css('background-position', 'right bottom');
                                        jQuery('#contextual-css-wrap<?php echo $rs_id; ?>').addClass('contextual-help-open');
                                        jQuery('#contextual-css-wrap<?php echo $rs_id; ?>').show();
                                    }
                                });
                <?php
            } //foreach
        }//if
        ?>
                                                    		
            });
        </script>
        <!-- M-vSlider - End -->
        <?php
    }
}

global $wpdb;
global $rslider_db_version;
global $table_slider;
$rslider_db_version = "1.2";
$table_slider = $wpdb->prefix . 'rs_slider';
define('M_VSLIDER_DIR', dirname(__FILE__));
define('M_VSLIDER_DIR_THEMES_DIR', M_VSLIDER_DIR . "/themes/");

register_activation_hook(__FILE__, 'rslider_install');
add_action('wp_enqueue_scripts', 'rslider_enqueue');
add_action('admin_head', 'rslider_admin_enqueue');
add_action('wp_head', 'rslider_head');
add_action('admin_menu', 'rslider_addPage');
?>
