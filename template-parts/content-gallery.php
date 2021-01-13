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
    <nav>
        <a href="#" role="button" data-view="gallery"
            aria-label="Activate the gallery view (one photo by one photo)">Gallery</a>
        &nbsp;/&nbsp;
        <a href="#" role="button" data-view="index"
            aria-label="Activate the index view (a photo wall)">Index</a>
    </nav>
</header>

<section>
    <?php the_content(); ?>
    <nav>
        <a role="button" data-photo="prev" aria-label="Display the previous photo"></a>
        <a role="button" data-photo="next" aria-label="Display the next photo"></a>
    </nav>
</section>