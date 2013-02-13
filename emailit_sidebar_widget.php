<?php

 
class EmailitSidebarWidget extends WP_Widget
{
  function EmailitSidebarWidget()
  {
    $widget_ops = array('classname' => 'EmailitWidget', 'description' => 'Give the opportunity to your visitors to share and distribute your content in over of 60 social networks.' );
    $this->WP_Widget('EmailitWidget', 'E-MAILiT Share', $widget_ops);
  }
 
  function form($instance)
  {
	$defaults = array( 'title' => __('E-MAILiT', 'example'), 'button_id' => '', 'display_counter' => 'on' );  
	$instance = wp_parse_args( (array) $instance, $defaults );
    $title = esc_attr($instance['title']);
	$button_id = esc_attr($instance['button_id']);
	$display_counter = esc_attr($instance['display_counter']);
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('button_id'); ?>">Your Button ID: <input class="widefat" id="<?php echo $this->get_field_id('button_id'); ?>" name="<?php echo $this->get_field_name('button_id'); ?>" type="text" value="<?php echo attribute_escape($button_id); ?>" /></label></p>  
<p>  
    <label for="<?php echo $this->get_field_id( 'display_counter' ); ?>"><?php _e('Display counter:'); ?></label>      
    <input class="checkbox" type="checkbox" <?php checked($display_counter, 'on' ); ?> id="<?php echo $this->get_field_id( 'display_counter' ); ?>" name="<?php echo $this->get_field_name( 'display_counter' ); ?>" />   
</p>  
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['button_id'] = strip_tags($new_instance['button_id']);
    $instance['display_counter'] = strip_tags($new_instance['display_counter']);	
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
	$button_id = "";
	if(!empty($instance['button_id']))
		$button_id = $instance['button_id'];
	$display_counter = isset( $instance['display_counter'] ) ? $instance['display_counter'] : '';  
		
    $outputValue = "<!-- E-MAILiT Sharing Button BEGIN -->" . PHP_EOL;

    if ($button_id == "" || $button_id == "Your Button ID")
        $outputValue .= " <div class='e_mailit_button'>";
    else
        $outputValue .= " <div class='e_mailit_button' id='$button_id'>";


    //Creates Emailit script
    $outputValue .= "<script type='text/javascript'>\r\n";
    if($display_counter == '')
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