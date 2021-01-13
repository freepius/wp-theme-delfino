<?php get_header(); ?>

<main id="primary">
	<?php
	if (have_posts()) {
		while (have_posts()) {
			the_post();
			get_template_part('template-parts/content', get_post_type());
		}

		if (! is_home()) {
			the_posts_navigation();
		}
	} else {
		echo '<p>'.esc_html__('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'delfino').'</p>';
	}
	?>
</main>

<?php get_footer(); ?>