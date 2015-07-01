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
  Plugin Name: Share Buttons by E-MAILiT
  Plugin URI: http://www.e-mailit.com
  Description: E-MAILiT Share Buttons can be deployed on any WordPress powered site to help people share to over 130 social sharing services.  [<a href="options-general.php?page=emailit_widget.php">Settings</a>]
  Author: E-MAILiT
  Version: 7.5.4.4
  Author URI: http://www.e-mailit.com
 */
include_once plugin_dir_path(__FILE__).'/include/emailit_admin_panel.php';

add_action('admin_init', 'emailit_admin_init');
add_filter('admin_menu', 'emailit_admin_menu');
add_action('widgets_init', 'emailit_widget_init');
add_action('wp_head', 'add_domain_verification_meta');
add_filter('get_the_excerpt', 'emailit_display_excerpt', 11);
//add_action('admin_notices', 'emailit_admin_notices');
//add_action('admin_init', 'emailit_nag_ignore');

function emailit_admin_notices() {
    global $current_user;
    $user_id = $current_user->ID;

    if (!get_user_meta($user_id, 'emailit_ignore_notice3')) {
        echo '<div class="updated"><p>';
        printf(__('E-MAILiT will discontinue the account registration system on April 4th, 2015. <a href="http://blog.e-mailit.com/2015/02/important-announcement.html" target="_blank">Learn More</a>. | <a href="%1$s">Hide Notice</a>'), '?emailit_nag_ignore3=0');
        echo "</p></div>";
    }
}


function emailit_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['emailit_nag_ignore3']) && '0' == $_GET['emailit_nag_ignore3']) {
        add_user_meta($user_id, 'emailit_ignore_notice3', 'true', true);
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
    if ($emailit_options['back_color'] != "")
        $configValues[] = "back_color:'" . $emailit_options["back_color"] . "'";
    if ($emailit_options['text_color'] != "")
        $configValues[] = "text_color:'" . $emailit_options["text_color"] . "'";
    if ($emailit_options['text_display'] != "Share" && $emailit_options['text_display'] != "") {
        $configValues[] = "text_display:'" . $emailit_options["text_display"] . "'";
    }
    if ($emailit_options['default_services'] != "")
        $configValues[] = "default_services:'" . $emailit_options["default_services"] . "'";
    if ($emailit_options['display_ads'] != "")
        $configValues[] = "display_ads:'" . $emailit_options["display_ads"] . "'";
    if ($emailit_options['promo_ad'] != "")
        $configValues[] = "promo_ad:'" . $emailit_options["promo_ad"] . "'";
    if ($emailit_options["promo_on_share"] == 'no')
        $configValues[] = "promo_on_share:false";
    
    
    $follow_services = array();
    if ($emailit_options['follow_facebook'] != "")
        $follow_services[] = "'Facebook':'" . $emailit_options['follow_facebook'] . "'";
    if ($emailit_options['follow_twiiter'] != "")
        $follow_services[] = "'Twitter':'" . $emailit_options['follow_twiiter'] . "'";
    if ($emailit_options['follow_linkedin'] != "")
        $follow_services[] = "'LinkedIn':'" . $emailit_options['follow_linkedin'] . "'";
    if ($emailit_options['follow_pinterest'] != "")
        $follow_services[] = "'Pinterest':'" . $emailit_options['follow_pinterest'] . "'";
    if ($emailit_options['follow_google'] != "")
        $follow_services[] = "'Google+':'" . $emailit_options['follow_google'] . "'";
    
    if ($emailit_options['follow_youtube'] != "")
        $follow_services[] = "'YouTube':'" . $emailit_options['follow_youtube'] . "'";
    if ($emailit_options['follow_vimeo'] != "")
        $follow_services[] = "'Vimeo':'" . $emailit_options['follow_vimeo'] . "'";
    if ($emailit_options['follow_vimeo'] != "")
        $follow_services[] = "'Instagram':'" . $emailit_options['follow_instagram'] . "'";
    if ($emailit_options['follow_instagram'] != "")
        $follow_services[] = "'Foursquare':'" . $emailit_options['follow_foursquare'] . "'";
    if ($emailit_options['follow_tumblr'] != "")
        $follow_services[] = "'Tumblr':'" . $emailit_options['follow_tumblr'] . "'";
    
    if ($emailit_options['follow_rss'] != "")
        $follow_services[] = "'Rss':'" . $emailit_options['follow_rss'] . "'";

    if (!empty($follow_services)) {
        $configValues[] = "follow_services:{" . implode(",", $follow_services) . "}";
    }
    if ($emailit_options['open_on'] != "") {
        $configValues[] = "open_on:'" . $emailit_options["open_on"] . "'";
    }
    if ($emailit_options['auto_popup'] && $emailit_options['auto_popup'] != "0") {
        $configValues[] = "auto_popup:" . $emailit_options["auto_popup"] * 1000;
    }
    $outputValue .= "var e_mailit_config = {" . implode(",", $configValues) . "};";
    $outputValue .= "(function() {	var b=document.createElement('script');	
                        b.type='text/javascript';b.async=true;	
                        b.src=('https:'==document.location.protocol?'https://www':'http://www')+'.e-mailit.com/widget/button/js/button.js';	
                        var c=document.getElementsByTagName('head')[0];	c.appendChild(b) })()";
    $outputValue .= "</script>" . PHP_EOL;
    echo $outputValue;
}


function emailit_widget_init() {
    require_once('emailit_sidebar_widget.php');
    register_widget('EmailitSidebarWidget');
}

function emailit_display_excerpt($content) {
    $options = get_option('emailit_options');

    // I don't think has_excerpt() is always necessarily true when calling "get_the_excerpt()",
    // but since this function is only as a get_the_excerpt() filter, we should probably
    // not care whether or not an excerpt is there since the caller obviously wants one.
    // needs testing/understanding.
    if ($options['emailit_showonexcerpts'] == true) {
        return emailit_display_button($content);
    } else
        return $content;
}

function emailit_admin_menu() {
    add_options_page('E-MAILiT Settings', 'E-MAILiT Share', 'manage_options', basename(__FILE__), 'emailit_settings_page');
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
    if (isset($emailit_options["toolbar_type"]) && $emailit_options["toolbar_type"] !== "")
        $style = "e-mailit:style=\"" . $emailit_options["toolbar_type"] . "\"";
    if (isset($emailit_options["circular"]) && $emailit_options["circular"] == "true")
        $circular = " circular";
    $outputValue .= "<div class=\"e-mailit_toolbox$circular\" $style>" . PHP_EOL;


    $sel_buttons = array_filter(explode(",", $emailit_options['buttons_order']));
    if (sizeof($sel_buttons) === 0)
        $sel_buttons = array("display_fb_like_share_button", "display_fb_button", "display_fb_share_button", "display_tweeter_button",
            "display_gplus_button", "display_pinterest_button", "display_linkedin_button", "display_vkontakte_button",
            "display_odnoklassniki_button", "display_emailit_button");
    foreach ($sel_buttons as $sel_button) {
        switch ($sel_button) {
            case "display_fb_button":
                if ($emailit_options["display_fb_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_facebook_btn\" $shared_url $shared_title></div>";
                break;
            case "display_fb_like_share_button":
                if ($emailit_options["display_fb_like_share_button"] == 'true') {
                    $share_str = "e-mailit:include_share='true'";
                    $outputValue .= "<div class=\"e-mailit_facebook_btn\" $shared_url $shared_title $share_str></div>";
                }
                break;
            case "display_fb_share_button":
                if ($emailit_options["display_fb_share_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_facebook_share_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_tweeter_button":
                if ($emailit_options["display_tweeter_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_twitter_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_gplus_button":
                if ($emailit_options["display_gplus_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_google_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_pinterest_button":
                if ($emailit_options["display_pinterest_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_pinterest_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_linkedin_button":
                if ($emailit_options["display_linkedin_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_linkedin_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_vkontakte_button":
                if ($emailit_options["display_vkontakte_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_vkontakte_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_odnoklassniki_button":
                if ($emailit_options["display_odnoklassniki_button"] == 'true')
                    $outputValue .= "<div class=\"e-mailit_odnoklassniki_btn\" $shared_url $shared_title></div>" . PHP_EOL;
                break;
            case "display_emailit_button":
                if ($emailit_options["display_emailit_button"] == 'true') {
                    if ($button_id == "" || $button_id == "Your Button ID") {
                        $outputValue .= " <div class='e_mailit_button' $shared_url $shared_title></div>" . PHP_EOL;
                    } else {
                        $outputValue .= " <div class='e_mailit_button' id='$button_id' $shared_url $shared_title></div>" . PHP_EOL;
                    }
                }
                break;
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

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');

function add_action_links($links) {
    $mylink = '<a href="options-general.php?page=emailit_widget.php">' . __('Settings') . '</a>';

    array_unshift($links, $mylink);
    return $links;
}

require_once('emailit_post_metabox.php');
?>