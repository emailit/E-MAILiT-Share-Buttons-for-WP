<?php
/*  Copyright YEAR  E-MAILiT  (email :  support@e-mailit.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/*
  Plugin Name: E-MAILiT Social Media Sharing Widget
  Plugin URI: http://www.e-mailit.com/about.html
  Description: Social Media Share Button that Creates Income for Websites and Blogs (respects your privacy, without using cookies). E-MAILiT is automatically tracked in 60 languages, giving the opportunity to your visitors to share and distribute your content in over of 60 social networks, such as on Pinterest, Post on Wordpress or sharing via Email to your friends.
  Author: E-MAILiT
  Version: 1.1
  Author URI: http://www.e-mailit.com
 */

add_action('admin_init', 'emailit_admin_init');
add_filter('admin_menu', 'emailit_admin_menu');
add_action('widgets_init', 'emailit_widget_init');

function emailit_admin_init() {
    register_setting('emailit_options', 'emailit_options');
}

function emailit_widget_init() {
    $emailit_options = get_option('emailit_options');
    if (!isset($emailit_options[plugin_type]))
        $emailit_options[plugin_type] = "content";

    require_once('emailit_sidebar_widget.php');
    if ($emailit_options[plugin_type] == "sidebar") {
        register_widget('EmailitSidebarWidget');
    } else {
        unregister_widget('EmailitSidebarWidget');
    }
}

function emailit_admin_menu() {
    add_options_page('E-MAILiT Settings', 'E-MAILiT Share', 'manage_options', basename(__FILE__), 'emailit_settings_page');
}

function emailit_settings_page() {
    ?>
    <div>
        <script type="text/javascript">
            function hideshow(plugin_type){
                content_options = document.getElementById('content_options'); 
                sidebar_options = document.getElementById('sidebar_options'); 
                if(plugin_type == 'content'){
                    content_options.style.display = 'table';
                    sidebar_options.style.display = 'none';
                }else{
                    content_options.style.display = 'none';
                    sidebar_options.style.display = 'block';
                }
            }
        </script>
        <h2><img src="<?php echo plugins_url('images/logo.png', __FILE__) ?>"/></h2>
        <form id="emailit_options" action="options.php" method="post">

            <?php
            settings_fields('emailit_options');

            $emailit_options = get_option('emailit_options');
            if (!isset($emailit_options[plugin_type]))
                $emailit_options[plugin_type] = "content";
            ?>
            <h2>Plugin options</h2>
            <strong>Use E-MAILiT Share button</strong>
            <br/>
            in content <input onclick="hideshow('content');" type="radio" name="emailit_options[plugin_type]" value="content" <?php echo ($emailit_options[plugin_type] == 'content' ? 'checked="checked"' : ''); ?>/>
            &nbsp; 
            in sidebar <input onclick="hideshow('sidebar');" type="radio" name="emailit_options[plugin_type]" value="sidebar" <?php echo ($emailit_options[plugin_type] == 'sidebar' ? 'checked="checked"' : ''); ?>/>
            <br/>
            <div id="sidebar_options" <?php if ($emailit_options[plugin_type] == "content") echo 'style="display:none"' ?>><br/>You can add your sidebar widget in Appearance > Widgets menu.</div>
            <table id="content_options" <?php if ($emailit_options[plugin_type] == "sidebar") echo 'style="display:none"' ?>>
                <tbody>
                    <tr><td><h3>In Content options</h3></td></tr>
                    <tr><td><strong>Your Button ID:</strong></td>
                        <td><input id="emailit_button_id"  type="text" name="emailit_options[button_id]" value="<?php echo $emailit_options[button_id]; ?>"/></td></tr>

                    <tr><td><strong>Display counter:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_counter]" value="true" <?php echo ($emailit_options[display_counter] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td><strong>Button position:</strong></td>
                        <td><select name="emailit_options[button_position]">
                                <option value="bottom" <?php echo ($emailit_options[button_position] == 'bottom' ? 'selected="selected"' : ''); ?>>Bottom</option>
                                <option value="top" <?php echo ($emailit_options[button_position] == 'top' ? 'selected="selected"' : ''); ?>>Top</option>
                            </select></td></tr>
					<tr><td><h3>E-MAILiT 3rd parties Horizontal Share Counter Toolbox options</h3></td></tr>
                    <tr><td style="height:20px;background: url(<?php echo plugins_url('images/fb_btn.png', __FILE__)?>) no-repeat right;"><strong>Add Facebook:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_fb_button]" value="true" <?php echo ($emailit_options[display_fb_button] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="height:20px;background: url(<?php echo plugins_url('images/tweeter_btn.png', __FILE__)?>) no-repeat right;"><strong>Add Twitter:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_tweeter_button]" value="true" <?php echo ($emailit_options[display_tweeter_button] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="height:20px;background: url(<?php echo plugins_url('images/g_plus_btn.png', __FILE__)?>) no-repeat right;"><strong>Add Google+:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_gplus_button]" value="true" <?php echo ($emailit_options[display_gplus_button] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="height:20px;background: url(<?php echo plugins_url('images/pinterest_btn.png', __FILE__)?>) no-repeat right;"><strong>Add Pinterest:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_pinterest_button]" value="true" <?php echo ($emailit_options[display_pinterest_button] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="height:20px;background: url(<?php echo plugins_url('images/linkedin_btn.png', __FILE__)?>) no-repeat right;"><strong>Add LinkedIn:</strong></td>
                        <td>
                            <input type="checkbox" name="emailit_options[display_linkedin_button]" value="true" <?php echo ($emailit_options[display_linkedin_button] == true ? 'checked="checked"' : ''); ?>/></td></tr>							
							<tr><td><h3>Show E-MAILiT Share Button on</h3></td></tr>
                    <tr><td>   
                            <strong>homepage:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showonhome]" value="true" <?php echo ($emailit_options[emailit_showonhome] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td>   
                            <strong>archives:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showonarchives]" value="true" <?php echo ($emailit_options[emailit_showonarchives] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td>   
                            <strong>categories:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showoncats]" value="true" <?php echo ($emailit_options[emailit_showoncats] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="padding-bottom: 5px">    
                            <strong>pages:</strong></td>
                        <td style="padding-bottom: 5px"><input type="checkbox" name="emailit_options[emailit_showonpages]" value="true" <?php echo ($emailit_options[emailit_showonpages] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="border-top: solid 1px lightgray; padding: 5px" colspan="2"><div style="width:460px">By default, we provide you with a standard appearance of the E-MAILiT sharing button. Do you want fully customization, get powerful sharing analytics and advertising campaign data metrics? <a target="_blank" href="http://www.e-mailit.com/widget/register">Create an account</a> with E-MAILiT. At the end of the flow, you will be given ''Your Button ID''. Please, paste only the number in the textbox above.<br><img src="<?php echo plugins_url('images/e-mailit_button_id.jpg', __FILE__) ?>"></div></td></tr>
                </tbody>
            </table>
            <br/>            
            <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form></div>

    <?php
}

add_action('init', 'emailit_init');

function emailit_init() {
    $emailit_options = get_option('emailit_options');
    if (!isset($emailit_options[plugin_type]))
        $emailit_options[plugin_type] = "content";

    if ($emailit_options[plugin_type] == "content") {
        add_filter('the_content', 'emailit_display_button');
    } else {
        remove_filter('the_content', 'emailit_display_button');
    }
}

function emailit_display_button($content) {
    $emailit_options = get_option('emailit_options');

    if (is_home() || is_front_page())
        $display = (isset($emailit_options['emailit_showonhome']) && $emailit_options['emailit_showonhome'] == true ) ? true : false;
    elseif (is_archive() && !is_category())
        $display = (isset($emailit_options['emailit_showonarchives']) && $emailit_options['emailit_showonarchives'] == true ) ? true : false;
    // Cat
    elseif (is_category())
        $display = (isset($emailit_options['emailit_showoncats']) && $emailit_options['emailit_showoncats'] == true ) ? true : false;
    // Pages
    elseif (is_page())
        $display = (isset($emailit_options['emailit_showonpages']) && $emailit_options['emailit_showonpages'] == true) ? true : false;
    // Single pages (true by default and design)
    elseif (is_single())
        $display = true;
    else
        $display = false;

    //an den prepei na mpei
    if (!$display)
        return $content;

    //Creating div elements for e-mailit
    $button_id = $emailit_options["button_id"];

    $outputValue = "<!-- E-MAILiT Sharing Button BEGIN -->" . PHP_EOL;
	$outputValue .= "<div class=\"e-mailit_toolbox\">" . PHP_EOL;
	if ($emailit_options["display_fb_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_facebook_btn\"></span>";
	if ($emailit_options["display_tweeter_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_twitter_btn\"></span>";
	if ($emailit_options["display_gplus_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_google_btn\"></span>";
	if ($emailit_options["display_pinterest_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_pinterest_btn\"></span>";
	if ($emailit_options["display_linkedin_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_linkedin_btn\"></span>";		

		
    if ($button_id == "" || $button_id == "Your Button ID")
        $outputValue .= " <div class='e_mailit_button'>" . PHP_EOL;
    else
        $outputValue .= " <div class='e_mailit_button' id='$button_id'>" . PHP_EOL;


    //Creates Emailit script
    $outputValue .= "<script type='text/javascript'>\r\n";
    if (!$emailit_options["display_counter"] == 'true')
        $outputValue .= "var e_mailit_config = {display_counter:false};";

    $outputValue .= "(function() {	var b=document.createElement('script');	
                        b.type='text/javascript';b.async=true;	
                        b.src=('https:'==document.location.protocol?'https://www':'http://www')+'.e-mailit.com/widget/button/js/button.js';	
                        var c=document.getElementsByTagName('head')[0];	c.appendChild(b) })()";
    $outputValue .= "</script>" . PHP_EOL;
    $outputValue .= "</div></div>";
    $outputValue .= "<!-- E-MAILiT Sharing Button END -->" . PHP_EOL;

    if ($emailit_options["button_position"] == 'top')
        $content = $outputValue . $content;
    else
        $content = $content . $outputValue;
    return $content;
}
?>