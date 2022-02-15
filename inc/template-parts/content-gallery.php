<?php
/**
 * Display a single post having the 'gallery' format.
 *
 * @package Delfino
 */

$delfino_cat = get_the_category()[0];
echo sprintf(
	'<a id="back-to-category" href="%s" title="%s">Back</a>',
	esc_url( get_category_link( $delfino_cat->cat_ID ) ),
	esc_attr( $delfino_cat->cat_name )
);
?>

<header>
	<?php the_title( '<h1>', '</h1>' ); ?>
	<h2><!-- Will contain legend of active photo (using javascript) --></h2>

	<!-- Buttons to activate the different gallery views -->
	<ul>
		<li><a href="#" role="button" data-view="gallery"
			aria-label="Activate the gallery view (one photo by one photo)">Gallery</a>
		</li>
		<li><a href="#" role="button" data-view="index" class="active"
			aria-label="Activate the index view (a photo wall)">Index</a>
		</li>
		<li><a href="#" role="button" data-view="description"
			aria-label="Display the gallery description">Description</a>
		</li>
	</ul>
</header>

<section class="index">
	<!-- Buttons to navigate through photos and gallery description -->
	<nav>
		<a role="button" data-photo="prev" aria-label="Display the previous photo"></a>
		<a role="button" data-photo="next" aria-label="Display the next photo"></a>
	</nav>
	<?php the_content(); ?>
</section>
