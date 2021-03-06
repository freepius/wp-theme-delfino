<?php
/**
 * The main template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Delfino
 */

get_header();
?>

<main id="primary">
	<?php
	$delfino_tpl = is_archive()
		? fn () => the_title( '<h2><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' )
		: fn () => get_template_part( 'inc/template-parts/content', get_post_format() );

	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			$delfino_tpl();
		}
	} else {
		echo '<p>' . esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'delfino' ) . '</p>';
	}
	?>
</main>

<?php get_footer(); ?>
