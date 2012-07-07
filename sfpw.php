<?php
/*
Plugin Name: Simple Featured Posts Widget
Plugin URI: http://www.nebulosaweb.com/wordpress/simple-featured-post-widget-articoli-con-immagine-di-anteprima/
Description: Simple Featured Posts is a pratical widget that allows you to show a post list with thumbnails ordered by random or recent posts. You can also choose post's categories and how many posts you want to show.
Author: Fabio Di Stasio
Version: 1.0
Author URI: http://nebulosaweb.com
*/

include("sfpw-func.php");

wp_enqueue_style('sfpw-style', plugin_dir_url(__FILE__).'/sfpw-style.css');

class sfpWidget extends WP_Widget {
	function sfpWidget() {
		parent::__construct( 
			false, 
			'Simple Featured Posts Widget',
			array( 'description' => "Show a posts list ordered by random or post date." ) 
		);

	}
	function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		echo $before_title.$instance['title'].$after_title;
 
		?>
		<ul id='sfpw'>
			<?php
			global $post;
			$tmp_post = $post;
			$args = array( 
				'numberposts' => $instance['nPosts'], 
				'orderby'=> $instance['order'], 
				'category' => $instance['category'] 
			);
			$myposts = get_posts( $args );
			foreach( $myposts as $post ) : setup_postdata($post); ?>
				<li>
					<?php if($instance['image'] == 1){ echo "<img width='150' src='"plugin_dir_url(__FILE__)."/".first_image()."' alt='".the_title('','',FALSE)."'/>";} ?>
				<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
				<span><?php the_time('j F Y') ?></span>
				</li>
			<?php endforeach;
			$post = $tmp_post; ?>
		</ul><?php

		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	function form( $instance ) { //setta i parametri di default del widget
		if($instance){
			$title = esc_attr($instance['title']);
			$nPosts = esc_attr($instance['nPosts']);
			$order = $instance['order'];
			$category = esc_attr($instance['category']);
			$image = $instance['image'];
		}
		else{
			$title = "Featured Posts";
			$nPosts = 5;
			$order = "rand";
			$category = "";
			$image = 1;
		}?>
		<p>
			<label for="<?php echo $this->get_field_id('title');?>"><?php _e('Title:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nPosts');?>"><?php _e('Number of posts to show:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('nPosts');?>" name="<?php echo $this->get_field_name('nPosts');?>" type="text" value="<?php echo $nPosts; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order');?>"><?php _e('Order:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('order');?>" name="<?php echo $this->get_field_name('order');?>" type="radio">
				<option value='<?php if($order == "rand"):?>rand<?php else:?>post_date<?php endif?>'><?php if($order == "rand"):?><?php _e('Random'); ?><?php else:?><?php _e('Recent Posts'); ?><?php endif?></option>
				<option value='<?php if($order == "rand"):?>post_date<?php else:?>rand<?php endif?>'><?php if($order == "rand"):?><?php _e('Recent Posts'); ?><?php else:?><?php _e('Random'); ?><?php endif?></option>
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('category');?>"><?php _e('Category ID (optional):','sfpw'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('category');?>" name="<?php echo $this->get_field_name('category');?>" type="text" value="<?php echo $category; ?>"/>
			<small>Category IDs, separated by commas</small>
		</p>
		<p>
			<input class="checkbox" <?php if($image == 1): ?>checked="checked"<?php endif?> id="<?php echo $this->get_field_id('image');?>" name="<?php echo $this->get_field_name('image');?>" type="checkbox" value="1"/>
			<label for="<?php echo $this->get_field_id('imahe');?>"><?php _e('Show thumbnail','sfpw'); ?></label> 

		</p>
		<?php
	}
}
 
function sfpw_register() {
	register_widget( 'sfpWidget' );
}
 
add_action( 'widgets_init', 'sfpw_register' );
?>