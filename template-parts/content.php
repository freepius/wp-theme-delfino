<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Delfino
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(is_page() ? 'collapser' : ''); ?>>
	<?php the_title( '<h1 class="entry-title screen-reader-text">', '</h1>' ); ?>
	<?php the_content(); ?>
</article><!-- #post-<?php the_ID(); ?> -->
