<?php

class EmailitSidebarWidget extends WP_Widget {

    function EmailitSidebarWidget() {
        $widget_ops = array('classname' => 'EmailitWidget', 'description' => 'Increase your site traffic with E-MAILiT\'s social life-cycle engagement and industry leading, privacy safe, sharing tools, analytics, and media solutions.');
        $this->WP_Widget('EmailitWidget', 'E-MAILiT Share', $widget_ops);
    }

    function form($instance) {
        $defaults = array('title' => __('E-MAILiT', 'example'), 'button_id' => '', 'emailit_btn' => '', 'facebook_btn' => '', 'facebook_like_share_btn' => '', 'facebook_share_btn' => '', 'tweet_btn' => '', 'googleplus_btn' => '', 'pinterest_btn' => '', 'linkedin_btn' => '' , 'vkontakte_btn' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        $button_id = esc_attr($instance['button_id']);
        $emailit_btn = esc_attr($instance['emailit_btn']);
        $facebook_btn = esc_attr($instance['facebook_btn']);
        $facebook_like_share_btn = esc_attr($instance['facebook_like_share_btn']);
        $facebook_share_btn = esc_attr($instance['facebook_share_btn']);
        $tweet_btn = esc_attr($instance['tweet_btn']);
        $googleplus_btn = esc_attr($instance['googleplus_btn']);
        $pinterest_btn = esc_attr($instance['pinterest_btn']);
        $linkedin_btn = esc_attr($instance['linkedin_btn']);
        $vkontakte_btn = esc_attr($instance['vkontakte_btn']);
        $odnoklassniki_btn = esc_attr($instance['odnoklassniki_btn']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <p>  
            <label for="<?php echo $this->get_field_id('emailit_btn'); ?>"><?php _e('Remove E-MAILiT button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($emailit_btn, 'on'); ?> id="<?php echo $this->get_field_id('emailit_btn'); ?>" name="<?php echo $this->get_field_name('emailit_btn'); ?>" />   
        </p>

        <p>  
            <label for="<?php echo $this->get_field_id('facebook_btn'); ?>"><?php _e('Add Facebook Like button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($facebook_btn, 'on'); ?> id="<?php echo $this->get_field_id('facebook_btn'); ?>" name="<?php echo $this->get_field_name('facebook_btn'); ?>" />   
        </p>
        <p>  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for="<?php echo $this->get_field_id('facebook_like_share_btn'); ?>"><?php _e('Include Share button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($facebook_like_share_btn, 'on'); ?> id="<?php echo $this->get_field_id('facebook_like_share_btn'); ?>" name="<?php echo $this->get_field_name('facebook_like_share_btn'); ?>" />   
        </p>

        <p>  
            <label for="<?php echo $this->get_field_id('facebook_share_btn'); ?>"><?php _e('Add Facebook Share button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($facebook_share_btn, 'on'); ?> id="<?php echo $this->get_field_id('facebook_share_btn'); ?>" name="<?php echo $this->get_field_name('facebook_share_btn'); ?>" />   
        </p>         
        <p>  
            <label for="<?php echo $this->get_field_id('tweet_btn'); ?>"><?php _e('Add Tweet share counter button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($tweet_btn, 'on'); ?> id="<?php echo $this->get_field_id('tweet_btn'); ?>" name="<?php echo $this->get_field_name('tweet_btn'); ?>" />   
        </p>         
        <p>  
            <label for="<?php echo $this->get_field_id('googleplus_btn'); ?>"><?php _e('Add Google+ share counter button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($googleplus_btn, 'on'); ?> id="<?php echo $this->get_field_id('googleplus_btn'); ?>" name="<?php echo $this->get_field_name('googleplus_btn'); ?>" />   
        </p> 
        <p>  
            <label for="<?php echo $this->get_field_id('pinterest_btn'); ?>"><?php _e('Add Pinterest share counter button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($pinterest_btn, 'on'); ?> id="<?php echo $this->get_field_id('pinterest_btn'); ?>" name="<?php echo $this->get_field_name('pinterest_btn'); ?>" />   
        </p>       
        <p>  
            <label for="<?php echo $this->get_field_id('linkedin_btn'); ?>"><?php _e('Add Linkedin share counter button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($linkedin_btn, 'on'); ?> id="<?php echo $this->get_field_id('linkedin_btn'); ?>" name="<?php echo $this->get_field_name('linkedin_btn'); ?>" />   
        </p>   
        <p>  
            <label for="<?php echo $this->get_field_id('vkontakte_btn'); ?>"><?php _e('Add VKontakte share counter button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($vkontakte_btn, 'on'); ?> id="<?php echo $this->get_field_id('vkontakte_btn'); ?>" name="<?php echo $this->get_field_name('vkontakte_btn'); ?>" />   
        </p>
        <p>  
            <label for="<?php echo $this->get_field_id('odnoklassniki_btn'); ?>"><?php _e('Add Odnoklassniki class button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($odnoklassniki_btn, 'on'); ?> id="<?php echo $this->get_field_id('odnoklassniki_btn'); ?>" name="<?php echo $this->get_field_name('odnoklassniki_btn'); ?>" />   
        </p>         
        <p>
            <a target="_blank" href="http://www.e-mailit.com/widget/login">Create Your Account To Access</a><br/>
            - Social Sharing Analytics &
            Advertising Campaign Data Metrics,<br/>
            - Custom Stylish Buttons (small buttons,
            hovering/floating bars),<br/>
            - Create your own advertising campaigns
            to make extra profit, and many more...            
        </p>
        <p><label for="<?php echo $this->get_field_id('button_id'); ?>">Your Button ID: <input class="widefat" id="<?php echo $this->get_field_id('button_id'); ?>" name="<?php echo $this->get_field_name('button_id'); ?>" type="text" value="<?php echo attribute_escape($button_id); ?>" /></label></p>          
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['button_id'] = strip_tags($new_instance['button_id']);
        $instance['emailit_btn'] = strip_tags($new_instance['emailit_btn']);
        $instance['facebook_btn'] = strip_tags($new_instance['facebook_btn']);
        $instance['facebook_like_share_btn'] = strip_tags($new_instance['facebook_like_share_btn']);
        $instance['facebook_share_btn'] = strip_tags($new_instance['facebook_share_btn']);      
        $instance['tweet_btn'] = strip_tags($new_instance['tweet_btn']);
        $instance['googleplus_btn'] = strip_tags($new_instance['googleplus_btn']);
        $instance['pinterest_btn'] = strip_tags($new_instance['pinterest_btn']);
        $instance['linkedin_btn'] = strip_tags($new_instance['linkedin_btn']);
        $instance['vkontakte_btn'] = strip_tags($new_instance['vkontakte_btn']);
        $instance['odnoklassniki_btn'] = strip_tags($new_instance['odnoklassniki_btn']);       
        return $instance;
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if (!empty($title))
            echo $before_title . $title . $after_title;;

        // WIDGET CODE GOES HERE
        $button_id = "";
        if (!empty($instance['button_id']))
            $button_id = $instance['button_id'];
        $emailit_btn = isset($instance['emailit_btn']) ? $instance['emailit_btn'] : '';
        $facebook_btn = isset($instance['facebook_btn']) ? $instance['facebook_btn'] : '';
        $facebook_like_share_btn = isset($instance['facebook_like_share_btn']) ? $instance['facebook_like_share_btn'] : '';
        $facebook_share_btn = isset($instance['facebook_share_btn']) ? $instance['facebook_share_btn'] : '';        
        $tweet_btn = isset($instance['tweet_btn']) ? $instance['tweet_btn'] : '';
        $googleplus_btn = isset($instance['googleplus_btn']) ? $instance['googleplus_btn'] : '';
        $pinterest_btn = isset($instance['pinterest_btn']) ? $instance['pinterest_btn'] : '';
        $linkedin_btn = isset($instance['linkedin_btn']) ? $instance['linkedin_btn'] : '';
        $vkontakte_btn = isset($instance['vkontakte_btn']) ? $instance['vkontakte_btn'] : '';
        $odnoklassniki_btn = isset($instance['odnoklassniki_btn']) ? $instance['odnoklassniki_btn'] : '';
        
        $outputValue = "<!-- E-MAILiT Sharing Button BEGIN -->" . PHP_EOL;
        $outputValue .= "<div class=\"e-mailit_toolbox\">" . PHP_EOL;
        if ($facebook_btn != ''){
            if($facebook_like_share_btn != '')
                $share_str = "e-mailit:include_share='true'";
            $outputValue .= "<span class=\"e-mailit_facebook_btn\" $share_str></span>" . PHP_EOL;
        }
        if ($facebook_share_btn != '')
            $outputValue .= "<span class=\"e-mailit_facebook_share_btn\"></span>" . PHP_EOL;        
        if ($tweet_btn != '')
            $outputValue .= "<span class=\"e-mailit_twitter_btn\"></span>" . PHP_EOL;
        if ($googleplus_btn != '')
            $outputValue .= "<span class=\"e-mailit_google_btn\"></span>" . PHP_EOL;
        if ($pinterest_btn != '')
            $outputValue .= "<span class=\"e-mailit_pinterest_btn\"></span>" . PHP_EOL;
        if ($linkedin_btn != '')
            $outputValue .= "<span class=\"e-mailit_linkedin_btn\"></span>" . PHP_EOL;
        if ($vkontakte_btn != '')
            $outputValue .= "<span class=\"e-mailit_vkontakte_btn\"></span>" . PHP_EOL;   
        if ($odnoklassniki_btn != '')
            $outputValue .= "<span class=\"e-mailit_odnoklassniki_btn\"></span>" . PHP_EOL;            
        if ($emailit_btn == '')
            if ($button_id == "" || $button_id == "Your Button ID")
                $outputValue .= " <div class='e_mailit_button'></div>" . PHP_EOL;
            else
                $outputValue .= " <div class='e_mailit_button' id='$button_id'></div>" . PHP_EOL;
        $outputValue .= "</div>";
        $outputValue .= "<!-- E-MAILiT Sharing Button END -->" . PHP_EOL;

        echo $outputValue;

        echo $after_widget;
    }

}
?>