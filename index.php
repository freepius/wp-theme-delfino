<?php get_header(); ?>

<main id="primary">
	<?php
	$tpl = is_archive()
		? fn () => the_title('<h2><a href="'.esc_url(get_permalink()).'">', '</a></h2>')
		: fn () => get_template_part('template-parts/content', get_post_format());

	if (have_posts()) {
		while (have_posts()) {
			the_post();
			$tpl();
		}
	} else {
		echo '<p>'.esc_html__('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'delfino').'</p>';
	}
	?>
</main>

<?php get_footer(); ?>
