<?php
$cat = get_the_category()[0];
echo sprintf(
    '<a id="back-to-category" href="%s" title="%s">Back</a>',
    get_category_link( $cat->cat_ID ),
    $cat->cat_name
);
?>

<header>
    <h1><?= get_the_title() ?></h1>
    <h2><!-- Will contain legend of active photo (using javascript) --></h2>

    <!-- Buttons to activate the different gallery views -->
    <ul>
        <li><a href="#" role="button" data-view="gallery" class="active"
            aria-label="Activate the gallery view (one photo by one photo)">Gallery</a>
        </li>
        <li><a href="#" role="button" data-view="index"
            aria-label="Activate the index view (a photo wall)">Index</a>
        </li>
        <li><a href="#" role="button" data-view="description"
            aria-label="Display the gallery description">Description</a>
        </li>
    </ul>
</header>

<section class="gallery">
    <!-- Buttons to navigate through photos and gallery description -->
    <nav>
        <a role="button" data-photo="prev" aria-label="Display the previous photo"></a>
        <a role="button" data-photo="next" aria-label="Display the next photo"></a>
    </nav>
    <?php the_content(); ?>
</section>