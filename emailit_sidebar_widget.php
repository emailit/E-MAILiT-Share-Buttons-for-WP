<?php

class EmailitSidebarWidget extends WP_Widget {

    function EmailitSidebarWidget() {
        $widget_ops = array('classname' => 'EmailitWidget', 'description' => 'Give the opportunity to your visitors to share and distribute your content in over of 60 social networks.');
        $this->WP_Widget('EmailitWidget', 'E-MAILiT Share', $widget_ops);
    }

    function form($instance) {
        $defaults = array('title' => __('E-MAILiT', 'example'), 'button_id' => '', 'display_counter' => 'on', 'facebook_btn' => '', 'tweet_btn' => '', 'googleplus_btn' => '', 'pinterest_btn' => '', 'linkedin_btn' => '');
        $instance = wp_parse_args((array) $instance, $defaults);
        $title = esc_attr($instance['title']);
        $button_id = esc_attr($instance['button_id']);
        $display_counter = esc_attr($instance['display_counter']);
        $facebook_btn = esc_attr($instance['facebook_btn']);
        $tweet_btn = esc_attr($instance['tweet_btn']);
        $googleplus_btn = esc_attr($instance['googleplus_btn']);
        $pinterest_btn = esc_attr($instance['pinterest_btn']);
        $linkedin_btn = esc_attr($instance['linkedin_btn']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <p>  
            <label for="<?php echo $this->get_field_id('display_counter'); ?>"><?php _e('Show E-MAILiT share counter:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($display_counter, 'on'); ?> id="<?php echo $this->get_field_id('display_counter'); ?>" name="<?php echo $this->get_field_name('display_counter'); ?>" />   
        </p>  
        <p>  
            <label for="<?php echo $this->get_field_id('facebook_btn'); ?>"><?php _e('Add Facebook Like button:'); ?></label>      
            <input class="checkbox" type="checkbox" <?php checked($facebook_btn, 'on'); ?> id="<?php echo $this->get_field_id('facebook_btn'); ?>" name="<?php echo $this->get_field_name('facebook_btn'); ?>" />   
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
        $instance['display_counter'] = strip_tags($new_instance['display_counter']);
        $instance['facebook_btn'] = strip_tags($new_instance['facebook_btn']);
        $instance['tweet_btn'] = strip_tags($new_instance['tweet_btn']);
        $instance['googleplus_btn'] = strip_tags($new_instance['googleplus_btn']);
        $instance['pinterest_btn'] = strip_tags($new_instance['pinterest_btn']);
        $instance['linkedin_btn'] = strip_tags($new_instance['linkedin_btn']);
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
        $display_counter = isset($instance['display_counter']) ? $instance['display_counter'] : '';
        $facebook_btn = isset($instance['display_counter']) ? $instance['facebook_btn'] : '';
        $tweet_btn = isset($instance['display_counter']) ? $instance['tweet_btn'] : '';
        $googleplus_btn = isset($instance['display_counter']) ? $instance['googleplus_btn'] : '';
        $pinterest_btn = isset($instance['display_counter']) ? $instance['pinterest_btn'] : '';
        $linkedin_btn = isset($instance['display_counter']) ? $instance['linkedin_btn'] : '';

        $outputValue = "<!-- E-MAILiT Sharing Button BEGIN -->" . PHP_EOL;
        $outputValue .= "<div class=\"e-mailit_toolbox\">" . PHP_EOL;
        if ($facebook_btn != '')
            $outputValue .= "<span class=\"e-mailit_facebook_btn\"></span>";
        if ($tweet_btn != '')
            $outputValue .= "<span class=\"e-mailit_twitter_btn\"></span>";
        if ($googleplus_btn != '')
            $outputValue .= "<span class=\"e-mailit_google_btn\"></span>";
        if ($pinterest_btn != '')
            $outputValue .= "<span class=\"e-mailit_pinterest_btn\"></span>";
        if ($linkedin_btn != '')
            $outputValue .= "<span class=\"e-mailit_linkedin_btn\"></span>";
        if ($button_id == "" || $button_id == "Your Button ID")
            $outputValue .= " <div class='e_mailit_button'>";
        else
            $outputValue .= " <div class='e_mailit_button' id='$button_id'>";


        //Creates Emailit script
        $outputValue .= "<script type='text/javascript'>\r\n";
        if ($display_counter == '')
            $outputValue .= "var e_mailit_config = {display_counter:false};";

        $outputValue .= "(function() {	var b=document.createElement('script');	
                        b.type='text/javascript';b.async=true;	
                        b.src=('https:'==document.location.protocol?'https://www':'http://www')+'.e-mailit.com/widget/button/js/button.js';	
                        var c=document.getElementsByTagName('head')[0];	c.appendChild(b) })()";
        $outputValue .= "</script>" . PHP_EOL;
        $outputValue .= "</div>";
        $outputValue .= "<!-- E-MAILiT Sharing Button END -->" . PHP_EOL;

        echo $outputValue;

        echo $after_widget;
    }

}
?>