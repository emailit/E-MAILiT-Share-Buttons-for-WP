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
  Plugin URI: http://www.e-mailit.com
  Description: Increase your site traffic with E-MAILiT's social life-cycle engagement and industry leading, privacy safe, sharing tools, analytics, and media solutions.
  Author: E-MAILiT
  Version: 6.8.3.5
  Author URI: http://www.e-mailit.com
 */

add_action('admin_init', 'emailit_admin_init');
add_filter('admin_menu', 'emailit_admin_menu');
add_action('widgets_init', 'emailit_widget_init');
add_action('wp_head', 'add_domain_verification_meta');
add_action('admin_notices', 'emailit_admin_notices');

function emailit_admin_notices() {
    global $current_user;
    $user_id = $current_user->ID;

    if (!get_user_meta($user_id, 'emailit_ignore_notice2')) {
        echo '<div class="updated"><p>';
        printf(__('Get a higher click through rate for your ads. <a href="http://www.e-mailit.com/premium" target="_blank">Try the E-MAILiT Go Premium Plan</a>. | <a href="%1$s">Hide Notice</a>'), '?emailit_nag_ignore2=0');
        echo "</p></div>";
    }
}

add_action('admin_init', 'emailit_nag_ignore');

function emailit_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['emailit_nag_ignore2']) && '0' == $_GET['emailit_nag_ignore2']) {
        add_user_meta($user_id, 'emailit_ignore_notice2', 'true', true);
    }
}

function add_domain_verification_meta() {
    $emailit_options = get_option('emailit_options');
    if (isset($emailit_options['domain_verification']) && $emailit_options['domain_verification'] != "") {
        echo '<meta name="e-mailit-site-verification" content="' . $emailit_options['domain_verification'] . '" />' . "\n";
    }

    //Creates Emailit script
    $outputValue = "<script type='text/javascript'>\r\n";
    $configValues = array();
    if (!$emailit_options["display_counter"] == 'true')
        $configValues[] = "display_counter:false";
    else
        $configValues[] = "display_counter:true";
    if ($emailit_options["TwitterID"] != "")
        $configValues[] = "TwitterID:'" . $emailit_options["TwitterID"] . "'";
    else
        $configValues[] = "TwitterID:''";
    if ($emailit_options['GA_id'] != "")
        $configValues[] = "ga_property_id:'" . $emailit_options["GA_id"] . "'";

    $outputValue .= "var e_mailit_config = {" . implode(",", $configValues) . "};";
    $outputValue .= "(function() {	var b=document.createElement('script');	
                        b.type='text/javascript';b.async=true;	
                        b.src=('https:'==document.location.protocol?'https://www':'http://www')+'.e-mailit.com/widget/button/js/button.js';	
                        var c=document.getElementsByTagName('head')[0];	c.appendChild(b) })()";
    $outputValue .= "</script>" . PHP_EOL;
    echo $outputValue;
}

function emailit_admin_init() {
    register_setting('emailit_options', 'emailit_options');
}

function emailit_widget_init() {
    require_once('emailit_sidebar_widget.php');
    register_widget('EmailitSidebarWidget');
}

function emailit_admin_menu() {
    add_options_page('E-MAILiT Settings', 'E-MAILiT Share', 'manage_options', basename(__FILE__), 'emailit_settings_page');
}

function emailit_settings_page() {
    ?>
    <div>
        <script type="text/javascript">
            function validate() {
                emailit_domain_verification = document.getElementById('emailit_domain_verification');
                if (emailit_domain_verification.value != "" && emailit_domain_verification.value.substr(0, 9) != "E-MAILiT_") {
                    alert("Error! Paste not valid. Domain Verification Publisher Key, always starts with 'E-MAILiT_'");
                    return false;
                }
                emailit_button_id = document.getElementById('emailit_button_id');
                if (emailit_button_id.value != "" && isNaN(emailit_button_id.value)) {
                    alert("Error! Paste not valid. ''Your Button ID'', must contain only digits.");
                    return false;
                }
                return true;
            }
        </script>
        <h2 style="font-size: 36px;"><img src="<?php echo plugins_url('images/logo.png', __FILE__) ?>"/> Share with E-MAILiT buttons</h2>
        <form onsubmit="return validate()" id="emailit_options" action="options.php" method="post">

            <?php
            settings_fields('emailit_options');

            $emailit_options = get_option('emailit_options');
            ?>           
            <table width="650px" >
                <tr><td  width="300px" style="height:20px;background: url('<?php echo plugins_url('images/fb_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_fb_button]" value="true" <?php echo ($emailit_options['display_fb_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/fb_like_share_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_fb_like_share_button]" value="true" <?php echo ($emailit_options['display_fb_like_share_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/fb_share_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_fb_share_button]" value="true" <?php echo ($emailit_options['display_fb_share_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>                    
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/tweeter_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_tweeter_button]" value="true" <?php echo ($emailit_options['display_tweeter_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/g_plus_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_gplus_button]" value="true" <?php echo ($emailit_options['display_gplus_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/pinterest_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_pinterest_button]" value="true" <?php echo ($emailit_options['display_pinterest_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/linkedin_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_linkedin_button]" value="true" <?php echo ($emailit_options['display_linkedin_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>							
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/vkontakte_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_vkontakte_button]" value="true" <?php echo ($emailit_options['display_vkontakte_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                <tr><td style="height:20px;background: url('<?php echo plugins_url('images/odnoklassniki_btn.png', __FILE__) ?>') no-repeat right;"></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_odnoklassniki_button]" value="true" <?php echo ($emailit_options['display_odnoklassniki_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
            </table>
            <h2 style="font-size: 36px;">Advanced</h2>
            <table width="650px" >
                <tr><td width="300px" style="height:20px;background: url('<?php echo plugins_url('images/emailit_btn.png', __FILE__) ?>') no-repeat right;"><strong>Remove:</strong></td>
                    <td>
                        <input type="checkbox" name="emailit_options[remove_emailit_button]" value="true" <?php echo ($emailit_options['remove_emailit_button'] == true ? 'checked="checked"' : ''); ?>/></td></tr>

                <tr><td><strong>Show E-MAILiT’s fuchsia counter:</strong></td>
                    <td>
                        <input type="checkbox" name="emailit_options[display_counter]" value="true" <?php echo ($emailit_options['display_counter'] == true ? 'checked="checked"' : ''); ?>/>
                    </td>
                </tr>
                <tr><td style="padding-bottom:20px"><strong>Tweet via:</strong></td><td style="padding-bottom:20px"><input type="text" name="emailit_options[TwitterID]" value="<?php echo $emailit_options['TwitterID']; ?>"/></td></tr>
            </table>
            <p><strong>Show sharing on ...</strong></p>
            <table width="650px" id="content_options">
                <tbody>
                    <tr><td width="300px">   
                            <strong>homepage:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showonhome]" value="true" <?php echo ($emailit_options['emailit_showonhome'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td>   
                            <strong>archives:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showonarchives]" value="true" <?php echo ($emailit_options['emailit_showonarchives'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td>   
                            <strong>categories:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showoncats]" value="true" <?php echo ($emailit_options['emailit_showoncats'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td>    
                            <strong>pages:</strong></td>
                        <td><input type="checkbox" name="emailit_options[emailit_showonpages]" value="true" <?php echo ($emailit_options['emailit_showonpages'] == true ? 'checked="checked"' : ''); ?>/></td></tr>
                    <tr><td style="padding-bottom:20px"><strong>button position on page:</strong></td>
                        <td style="padding-bottom:20px"><select name="emailit_options[button_position]">
                                <option value="top" <?php echo ($emailit_options['button_position'] == 'top' ? 'selected="selected"' : ''); ?>>Top</option>                                
                                <option value="bottom" <?php echo ($emailit_options['button_position'] == 'bottom' ? 'selected="selected"' : ''); ?>>Bottom</option>
                                <option value="both" <?php echo ($emailit_options['button_position'] == 'both' ? 'selected="selected"' : ''); ?>>Both</option>
                            </select></td></tr>
                </tbody>
            </table>
            <h2 style="font-size: 36px;">Get Stats</h2>
            <table width="650px" >
                <tr><td style="padding-bottom:20px"><strong>Google Analytics property ID:</strong></td><td style="padding-bottom:20px"><input type="text" name="emailit_options[GA_id]" value="<?php echo $emailit_options['GA_id']; ?>"/></td></tr>
            </table>
            <div><br/><strong>Edit Sidebar (Widget) Settings:</strong> Go to Appearance > Widgets > Main Sidebar > E-MAILiT Share</div>            
            <br/>
            <p>
            <div style="width:500px;border-top: solid 1px lightgray; padding: 5px"><br/><br/>
                <a target="_blank" href="http://www.e-mailit.com/widget/login">Optionally, Create Your Account To Access</a><br/>
                - Social Sharing Analytics &
                Advertising Campaign Data Metrics,<br/>
                - Custom Stylish Buttons (small buttons,
                hovering/floating bars),<br/>
                - Create your own advertising campaigns
                to make extra profit, and many more...            
                </p>          
                <strong>Domain Verification Publisher Key:</strong> <input id="emailit_domain_verification"  type="text" name="emailit_options[domain_verification]" value="<?php echo $emailit_options['domain_verification']; ?>" size="50"/><br/><br/>                            
                <div id="button_id" <?php if ($emailit_options['plugin_type'] == "sidebar") echo 'style="display:none"' ?>><strong>Your Button ID:</strong>
                    <input id="emailit_button_id"  type="text" name="emailit_options[button_id]" value="<?php echo $emailit_options['button_id']; ?>"/><br/><br/></div>
                <br/>            
                <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
            </div>
        </form></div>

    <?php
}

add_action('init', 'emailit_init');

function emailit_init() {
    $emailit_options = get_option('emailit_options');
    if (!isset($emailit_options['plugin_type']))
        $emailit_options['plugin_type'] = "content";

    if ($emailit_options['plugin_type'] == "content") {
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

    $custom_fields = get_post_custom($post->ID);
    if (isset($custom_fields['emailit_exclude']) && $custom_fields['emailit_exclude'][0] == 'true')
        $display = false;

    //an den prepei na mpei
    if (!$display)
        return $content;


    $url = get_permalink();
    $title = get_the_title();

    $shared_url = "e-mailit:url='" . $url . "'";
    $shared_title = "e-mailit:title='" . strip_tags($title) . "'";
    //Creating div elements for e-mailit
    $button_id = $emailit_options["button_id"];

    $outputValue = "<!-- E-MAILiT Sharing Button BEGIN -->" . PHP_EOL;
    $outputValue .= "<div class=\"e-mailit_toolbox\">" . PHP_EOL;
    if ($emailit_options["display_fb_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_facebook_btn\" $shared_url $shared_title $share_str></span>";
    if ($emailit_options["display_fb_like_share_button"] == 'true') {
        $share_str = "e-mailit:include_share='true'";
        $outputValue .= "<span class=\"e-mailit_facebook_btn\" $shared_url $shared_title $share_str></span>";
    }
    if ($emailit_options["display_fb_share_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_facebook_share_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_tweeter_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_twitter_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_gplus_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_google_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_pinterest_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_pinterest_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_linkedin_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_linkedin_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_vkontakte_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_vkontakte_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["display_odnoklassniki_button"] == 'true')
        $outputValue .= "<span class=\"e-mailit_odnoklassniki_btn\" $shared_url $shared_title></span>" . PHP_EOL;
    if ($emailit_options["remove_emailit_button"] !== 'true') {
        if ($button_id == "" || $button_id == "Your Button ID") {
            $outputValue .= " <div class='e_mailit_button' $shared_url $shared_title></div>" . PHP_EOL;
        } else {
            $outputValue .= " <div class='e_mailit_button' id='$button_id' $shared_url $shared_title></div>" . PHP_EOL;
        }
    }



    $outputValue .= "</div>";
    $outputValue .= "<!-- E-MAILiT Sharing Button END -->" . PHP_EOL;

    if ($emailit_options["button_position"] == 'top' || $emailit_options["button_position"] == 'both')
        $content = $outputValue . $content;
    if ($emailit_options["button_position"] == 'bottom' || $emailit_options["button_position"] == 'both')
        $content = $content . $outputValue;
    return $content;
}

require_once('emailit_post_metabox.php');
?>