<!-- enqueue JS -->

<?php
/**
 * Functions which enhance the theme by utilizing the block editor in WordPress
 *
 * @package Boreal_Bees
 */

function bb_enqueue_block_editor_assets() {
    wp_enqueue_script(
        'block-editor-script',
        // get directory of current theme
        get_template_directory_uri() . '/assets/js/block-editor.js',
        array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
        filemtime( get_template_directory() . '/assets/js/block-editor.js' )
    );
}
add_action( 'enqueue_block_editor_assets', 'bb_enqueue_block_editor_assets' );

function bb_enqueue_block_assets() {
    wp_enqueue_style( 
        'block-style',
        get_template_directory_uri() . '/assets/css/block-style.css'
        // plugins_url( 'style.css', __FILE__ )
    );
}
add_action( 'enqueue_block_assets', 'bb_enqueue_block_assets' );


// bb is text domain