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
  Plugin Name: E-MAILiT Share | Media Solutions & Analytics
  Plugin URI: http://www.e-mailit.com
  Description: E-MAILiT social sharing platform helps publishers drive more clicks, money, follows, shares and higher CTR by displaying in-Share-Button Ads.  [<a href="options-general.php?page=emailit_widget.php">Settings</a>]
  Author: E-MAILiT
  Version: 7.3.1
  Author URI: http://www.e-mailit.com
 */

add_action('admin_init', 'emailit_admin_init');
add_filter('admin_menu', 'emailit_admin_menu');
add_action('widgets_init', 'emailit_widget_init');
add_action('wp_head', 'add_domain_verification_meta');
//add_action('admin_notices', 'emailit_admin_notices');

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
    if ($emailit_options['back_color'] != "")
        $configValues[] = "back_color:'" . $emailit_options["back_color"] . "'";
    if ($emailit_options['text_color'] != "")
        $configValues[] = "text_color:'" . $emailit_options["text_color"] . "'";		
	if  ($emailit_options['text_display'] != "Share" && $emailit_options['text_display'] != ""){
		$configValues[] = "text_display:'" . $emailit_options["text_display"] . "'";
	}
	if  ($emailit_options['open_on'] != ""){
		$configValues[] = "open_on:'" . $emailit_options["open_on"] . "'";
	}
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
		<script type="text/javascript" src="<?php echo plugins_url('js/jquery.js', __FILE__) ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url('js/jquery-ui.min.js', __FILE__) ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url('js/colorpicker/js/colorpicker.js', __FILE__) ?>"></script>
		<script type="text/javascript" src="<?php echo plugins_url('js/colorpicker/js/colorpicker.js', __FILE__) ?>"></script>
		<link href="<?php echo plugins_url('css/jquery-ui.min.css', __FILE__) ?>" rel="stylesheet">
		<link href="<?php echo plugins_url('js/colorpicker/css/colorpicker.css', __FILE__) ?>" rel="stylesheet">
		<link href="<?php echo plugins_url('js/colorpicker/css/layout.css', __FILE__) ?>" rel="stylesheet">
		<link href="<?php echo plugins_url('css/style.css', __FILE__) ?>" rel="stylesheet">
        <h2 style="font-size: 36px;">Welcome to E-MAILiT Share Settings for WordPress</h2>
        <form onsubmit="return validate()" id="emailit_options" action="options.php" method="post">
		<?php
			settings_fields('emailit_options');
			$emailit_options = get_option('emailit_options');
		?>
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
			$(function() {
				$( document ).tooltip({
				  position: {
					my: "center bottom-20",
					at: "center top",
					using: function( position, feedback ) {
					  $( this ).css( position );
					  $( "<div>" )
						.addClass( "arrow" )
						.addClass( feedback.vertical )
						.addClass( feedback.horizontal )
						.appendTo( this );
					}
				  }
				});

				$( "#sel_buttons" ).sortable({
					cancel: ".ui-state-disabled",
					update: function( event, ui ) {
						$("#buttons_order").val("");
						$( "#sel_buttons li" ).each(function(){
							$("#buttons_order").val($("#buttons_order").val() + $(this).attr("id") + ",");
						});
					}
				});
				$( "#sel_buttons" ).disableSelection();
				
				$('#colorSelector div,#colorSelector2 div').css('backgroundColor', '<?php echo $emailit_options["back_color"]?>');
				$('#colorSelector,#colorSelector2').ColorPicker({
					color: '<?php echo $emailit_options["back_color"]?>',
					onShow: function (colpkr) {
						if(!$(this).attr('disabled'))
							$(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onChange: function(hsb, hex, rgb) {
						$("#colorpickerField").val("#"+hex);
						$("#colorpickerField2").val("#"+hex);
						$('#colorSelector div').css('backgroundColor', '#' + hex);
						$('#colorSelector2 div').css('backgroundColor', '#' + hex);
					}
				})
				.bind('keyup', function(){
					$(this).ColorPickerSetColor(this.value);
				});
				
				$("#colorpickerField, #colorpickerField2").change(function(){
					$('#colorSelector div').css('backgroundColor',$(this).val());
					$('#colorSelector2 div').css('backgroundColor',$(this).val());
					$("#colorpickerField, #colorpickerField2").val($(this).val());
				});
				
				$('#colorSelector3 div').css('backgroundColor', '<?php echo $emailit_options["text_color"]?>');
				$('#colorSelector3').ColorPicker({
					color: '<?php echo $emailit_options["text_color"]?>',
					onShow: function (colpkr) {
						if(!$(this).attr('disabled'))					
							$(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onChange: function(hsb, hex, rgb) {
						$("#colorpickerField3").val("#"+hex);
						$('#colorSelector3 div').css('backgroundColor', '#' + hex);
					}
				})
				.bind('keyup', function(){
					$(this).ColorPickerSetColor(this.value);
				});				
				$("#colorpickerField3").change(function(){
					$('#colorSelector3 div').css('backgroundColor',$(this).val());
				});				

				$(".toolbar-style").click(function(){
					$(this).find("input").prop('checked', true);
					$(".toolbar-styles").change();
				});				
				$(".toolbar-styles").change(function(){
					$(".toolbar-styles input[type='radio']").parent().css("opacity","0.3");
					$(".toolbar-styles input[type='radio']:checked").parent().css("opacity","1");
					
					if($(".toolbar-style input:checked").val() !== ""){
						$("#display_linkedin_button input, #display_vkontakte_button input, #display_gplus_button input, " +
						"#display_fb_button input, #display_fb_like_share_button input, #display_odnoklassniki_button input").prop('disabled', true);
						$("#display_linkedin_button, #display_vkontakte_button, #display_gplus_button, " +
						"#display_fb_button, #display_fb_like_share_button, #display_odnoklassniki_button").addClass("ui-state-disabled");
					}else{
						$("#display_linkedin_button input, #display_vkontakte_button input, #display_gplus_button input, " +
						"#display_fb_button input, #display_fb_like_share_button input, #display_odnoklassniki_button input").prop('disabled', false);
						$("#display_linkedin_button, #display_vkontakte_button, #display_gplus_button, " +
						"#display_fb_button, #display_fb_like_share_button, #display_odnoklassniki_button").removeClass("ui-state-disabled");					
					}
					if($(".toolbar-style input:checked").val() !== "" && $(".toolbar-style input:checked").val() !== "3"){
						$("#emailit_button_options").css("opacity","0.3");
						$("#emailit_button_options input").prop('disabled', true);
						$("#emailit_button_options div").attr('disabled', true);
					}else{
						$("#emailit_button_options").css("opacity","1");
						$("#emailit_button_options input").prop('disabled', false);
						$("#emailit_button_options div").attr('disabled', false);
					}
				});
				$(".toolbar-styles").change();
			});			
        </script>
		<div class="toolbar-styles">			
			<div class="toolbar-style" style="background: url('<?php echo plugins_url('images/style1.png', __FILE__) ?>') no-repeat right;">
				<input type="radio"  name="emailit_options[toolbar_type]" value="1" <?php echo ($emailit_options["toolbar_type"] == "1" ? 'checked="checked"' : ''); ?>>			
			</div>
			<div class="toolbar-style"  style="background: url('<?php echo plugins_url('images/style2.png', __FILE__) ?>') no-repeat right;">
				<input type="radio"  name="emailit_options[toolbar_type]" value="2" <?php echo ($emailit_options["toolbar_type"] == "2" ? 'checked="checked"' : ''); ?>>
				<input style="float:right;margin-right:180px;margin-top: 7px;" type="text" name="emailit_options[back_color]" maxlength="7" size="7" id="colorpickerField" value="<?php echo $emailit_options["back_color"]?>" />
				<div style="float:right;margin-top: 3px;" id="colorSelector"><div style="background-color: #0000ff"></div></div>
			</div>
			<div class="toolbar-style"  style="background: url('<?php echo plugins_url('images/style3.png', __FILE__) ?>') no-repeat right;">
				<input type="radio"  name="emailit_options[toolbar_type]" value="3" <?php echo ($emailit_options["toolbar_type"] == "3" ? 'checked="checked"' : ''); ?>>
			</div>
			<div class="toolbar-style"  style="background: url('<?php echo plugins_url('images/style4.png', __FILE__) ?>') no-repeat right;">
				<input type="radio"  name="emailit_options[toolbar_type]" value="" <?php echo ($emailit_options["toolbar_type"] == "" ? 'checked="checked"' : ''); ?>>
			</div>
		</div>
		<div>
            <?php
			$buttons = array(
				"display_fb_like_share_button" => "Facebook Like + Share",
				"display_fb_button" => "Facebook Like",
				"display_fb_share_button" => "Facebook Share",
				"display_tweeter_button" => "Twitter",
				"display_gplus_button" => "Google+",
				"display_pinterest_button" => "Pinterest",
				"display_linkedin_button" => "LinkedIn",
				"display_vkontakte_button" => "Vkontakte",
				"display_odnoklassniki_button" => "Odnoklassniki",
				"display_emailit_button" => "E-MAILiT",
				);
				
				if($emailit_options["remove_emailit_button"] !== 'true' && !($emailit_options["buttons_order"]))
					$emailit_options["display_emailit_button"] = "true";
            ?>
			<ul title="Drag to reorder" id="sel_buttons">
			<?php 
				$sel_buttons = array_filter(explode(",",$emailit_options['buttons_order']));

				foreach($sel_buttons as $sel_button){
			?>
				<li class="ui-state-default ui-sortable-handle" id="<?php echo $sel_button?>">
					<span class="ui-icon ui-icon-arrowthick-2-n-s"></span><i></i><?php echo $buttons[$sel_button]?>
					<input type="checkbox" name="emailit_options[<?php echo $sel_button?>]" value="true" <?php echo ($emailit_options[$sel_button] == true ? 'checked="checked"' : ''); ?>/>
				</li>
			<?php			
				}
				foreach($buttons as $button_key => $button){
					if(!in_array($button_key, $sel_buttons)){
			?>
					<li class="ui-state-default ui-sortable-handle" id="<?php echo $button_key?>"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><i></i><?php echo $button?><input type="checkbox" name="emailit_options[<?php echo $button_key?>]" value="true" <?php echo ($emailit_options[$button_key] == true ? 'checked="checked"' : ''); ?>/></li>
			<?php	
					}			
				}
			?>
			</ul>
			<input id="buttons_order" name="emailit_options[buttons_order]" value="<?php echo $emailit_options['buttons_order']; ?>" type="hidden"/>
			</div>
			<div id="emailit_button_options">
				<input title="EMAILiT Button Text" style="vertical-align:middle;" type="text" name="emailit_options[text_display]" value="<?php if($emailit_options['text_display']) echo $emailit_options['text_display']; else echo "Share"; ?>"/>
				<div style="display:inline-block;vertical-align:middle;" id="colorSelector2"><div style="background-color: #0000ff"></div></div>
				<input title="EMAILiT Button Background" style=";vertical-align:middle;" type="text" name="emailit_options[back_color]" maxlength="7" size="7" id="colorpickerField2" value="<?php echo $emailit_options["back_color"]?>" />
				<div style="display:inline-block;vertical-align:middle;" id="colorSelector3"><div style="background-color: #0000ff"></div></div>
				<input title="EMAILiT Button Text Color" style=";vertical-align:middle;" type="text" name="emailit_options[text_color]" maxlength="7" size="7" id="colorpickerField3" value="<?php echo $emailit_options["text_color"]?>" />
				<strong style="vertical-align:middle;">Show counter: </strong><input title="EMAILiT Button counter" type="checkbox" name="emailit_options[display_counter]" value="true" <?php echo ($emailit_options['display_counter'] == true ? 'checked="checked"' : ''); ?>/><br />
			</div>			
				
			<select title="EMAILiT Menu open on" name="emailit_options[open_on]">
				<option value="onmouseover" <?php echo ($emailit_options['open_on'] == 'onmouseover' ? 'selected="selected"' : ''); ?>>hover</option>
				<option value="onclick" <?php echo ($emailit_options['open_on'] == 'onclick' ? 'selected="selected"' : ''); ?>>click</option>                                
			</select>
            <h2 style="font-size: 36px;">Advanced</h2>
            <table width="650px" >
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
                        <td style="padding-bottom:20px">
						<select name="emailit_options[button_position]">
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
        </form>
		<br/>
		<p>
			<strong style="background-color: #FF0;">&nbsp;&nbsp;Like our share widget?&nbsp;&nbsp;</strong><br><br>
			<a href="http://wordpress.org/support/view/plugin-reviews/e-mailit" target="_blank">Give E-MAILiT your rating and review</a> on WordPress.org<br>
			<a href="http://www.e-mailit.com/share/mobile?url=http://www.e-mailit.com&amp;title=I love E-MAILiT Share Buttons" target="_blank">Share</a> and follow <a href="http://www.e-mailit.com">E-MAILiT</a> on <a href="http://twitter.com/emailit" target="_blank">Twitter</a>.
		</p>
		<p>		
		<br/><strong style="background-color: #FF0;">&nbsp;&nbsp;Need support?&nbsp;&nbsp;</strong><br><br>
		Support via <a href="http://twitter.com/emailit" target="_blank">Twitter</a>.<br/>
		Search the <a href="http://wordpress.org/support/plugin/e-mailit" target="_blank">WordPress Forum</a> or
		see the <a href="https://wordpress.org/plugins/e-mailit/faq" target="_blank">FAQs</a>.<br/>
		<a href="mailto:support@e-mailit.com">Ask a Question</a>.
		</p>		
	</div>

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
	if(isset($emailit_options["toolbar_type"]) && $emailit_options["toolbar_type"] !== "") $style = "e-mailit:style=\"".$emailit_options["toolbar_type"]."\"";
    $outputValue .= "<div class=\"e-mailit_toolbox\" $style>" . PHP_EOL;

	
	$sel_buttons = array_filter(explode(",",$emailit_options['buttons_order']));
	if(sizeof($sel_buttons) === 0)
	$sel_buttons = array("display_fb_like_share_button","display_fb_button","display_fb_share_button","display_tweeter_button",
						"display_gplus_button","display_pinterest_button","display_linkedin_button","display_vkontakte_button",
						"display_odnoklassniki_button","display_emailit_button");
	foreach($sel_buttons as $sel_button){
		switch ($sel_button) {
		  case "display_fb_button":
			if ($emailit_options["display_fb_button"] == 'true')
				$outputValue .= "<span class=\"e-mailit_facebook_btn\" $shared_url $shared_title></span>";
			break;
		  case "display_fb_like_share_button":
			if ($emailit_options["display_fb_like_share_button"] == 'true') {
				$share_str = "e-mailit:include_share='true'";
				$outputValue .= "<span class=\"e-mailit_facebook_btn\" $shared_url $shared_title $share_str></span>";
			}		  
			break;
		  case "display_fb_share_button":
			if ($emailit_options["display_fb_share_button"] == 'true')
				$outputValue .= "<span class=\"e-mailit_facebook_share_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
		  case "display_tweeter_button":
			if ($emailit_options["display_tweeter_button"] == 'true')
				$outputValue .= "<span class=\"e-mailit_twitter_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_gplus_button":
				if ($emailit_options["display_gplus_button"] == 'true')
					$outputValue .= "<span class=\"e-mailit_google_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_pinterest_button":
				if ($emailit_options["display_pinterest_button"] == 'true')
					$outputValue .= "<span class=\"e-mailit_pinterest_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_linkedin_button":
				if ($emailit_options["display_linkedin_button"] == 'true')
					$outputValue .= "<span class=\"e-mailit_linkedin_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_vkontakte_button":
				if ($emailit_options["display_vkontakte_button"] == 'true')
					$outputValue .= "<span class=\"e-mailit_vkontakte_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_odnoklassniki_button":
				if ($emailit_options["display_odnoklassniki_button"] == 'true')
					$outputValue .= "<span class=\"e-mailit_odnoklassniki_btn\" $shared_url $shared_title></span>" . PHP_EOL;
			break;
			case "display_emailit_button":
				if (($emailit_options["remove_emailit_button"] !== 'true' && !$emailit_options["buttons_order"]) || $emailit_options["display_emailit_button"] == 'true') {
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

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );

function add_action_links ( $links ) {
    $mylink = '<a href="options-general.php?page=emailit_widget.php">' . __( 'Settings' ) . '</a>';

    array_unshift( $links, $mylink );
    return $links;
}

require_once('emailit_post_metabox.php');
?>