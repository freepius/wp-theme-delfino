<?php

/**
 * Change the theme version everywhere it's necessary.
 *
 * @package Delfino
 */

$old = '1.1.2';
$new = '1.1.3';
$dir = dirname(__FILE__) . '/../';

$filesPatterns = [
    'functions.php' => [
        "define( 'DELFINO_VERSION', '$old' );" => "define( 'DELFINO_VERSION', '$new' );",
    ],

    'package.json' => [
        "\"version\": \"$old\"" => "\"version\": \"$new\"",
    ],

    'readme.txt' => [
        "Stable tag: $old" => "Stable tag: $new",
    ],

    'style.css' => [
        "Version: $old" => "Version: $new",
    ],
];

echo "Change the theme version from $old to $new:\n";

foreach ($filesPatterns as $file => $patterns) {
    $text = file_get_contents($dir . $file);

    echo "  → Edit $file\n";

    $text = str_replace(
        array_keys($patterns),
        array_values($patterns),
        $text
    );

    file_put_contents($dir . $file, $text);
}
