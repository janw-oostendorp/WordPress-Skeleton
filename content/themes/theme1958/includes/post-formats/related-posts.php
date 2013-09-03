<?php
// Reference : http://codex.wordpress.org/Function_Reference/wp_get_post_tags
// we are using this function to get an array of tags assigned to current post
$tags = wp_get_post_tags($post->ID);

if ($tags) {
	$tag_ids = array();
	foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
	$args=array(
		'tag__in' => $tag_ids,
		'post__not_in' => array($post->ID),
		'showposts' => 4, // these are the number of related posts we want to display
		'caller_get_posts'=>1,
		'ignore_sticky_posts' => 1 // to exclude the sticky post
	);

	// WP_Query takes the same arguments as query_posts
	$related_query = new WP_Query($args);

	if ($related_query->have_posts()) {
		$blog_related = of_get_option('blog_related'); ?>
		<h2>
			<?php if($blog_related) echo of_get_option('blog_related'); 
			else _e('Related Posts','theme1948'); ?>
		</h2>

		<ul class="related-posts rlist clearfix">
			<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
				<li>
					<?php if(has_post_thumbnail()) {
						$thumb = get_post_thumbnail_id();
						$img_url = wp_get_attachment_url( $thumb,'full'); //get img URL
						$image = aq_resize( $img_url, 140, 100, true ); //resize & crop img ?>
						<figure class="featured-thumbnail">
							<span class="img-box"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo $image ?>" alt="<?php the_title(); ?>"></a></span>
						</figure>
					<?php } ?>
					<a href="<?php the_permalink() ?>" > <?php the_title();?> </a>
				</li>
			<?php endwhile; ?>
		</ul>
	<?php }
	wp_reset_query(); // to reset the loop : http://codex.wordpress.org/Function_Reference/wp_reset_query
} ?>