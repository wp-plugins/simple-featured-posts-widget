<?php
function first_image() {
	global $post, $posts;
	$first_img = "";
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];

	if(empty($first_img)){ //Defines a default image
		$first_img = "images/default.jpg";
	}
		
	return $first_img;
}
add_action('wp_head', 'first_image');
?>