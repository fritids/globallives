<?php

class FeaturedArticlesWidget extends WP_Widget
{
  function FeaturedArticlesWidget()
  {
    $widget_ops = array('classname' => 'featured-articles', 'description' => 'Displays latest sticky posts' );
    $this->WP_Widget('FeaturedArticlesWidget', 'Featured Articles', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    $number = $instance['number'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('number'); ?>">Number: <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo attribute_escape($number); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['number'] = $new_instance['number'];
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

    if ( $sticky_ids = get_option( 'sticky_posts' )) {
	    rsort( $sticky_ids );
	    $sticky_ids = array_slice( $sticky_ids, 0, $instance['number'] );

	    $sticky_posts = get_posts( array( 'post__in' => $sticky_ids, 'caller_get_posts' => 1 ) );
	    foreach ( $sticky_posts as $sticky_post ) : ?>
    	
    	<a href="<?php echo get_permalink($sticky_post->ID) ?>">
	    	<?php echo get_the_post_thumbnail($sticky_post->ID,'medium'); ?>
	    	<h4><?php echo get_the_title($sticky_post->ID); ?></h4>
	    </a>

    	<?php endforeach;
    } else { echo ""; }
        	
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("FeaturedArticlesWidget");') );?>
