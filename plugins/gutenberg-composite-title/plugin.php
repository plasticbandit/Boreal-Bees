<?php
/**
 * Plugin Name: Gutenberg Composite Title Block
 * Author: Nicola McFadden
 * Author URI: https://nicolamaydesign.ca
 * Description: A heading with a predifined span style.
 * Version: 1.0
 */

// Load assets for wp-admin when editor is active
function nicola_gutenberg_composite_title_block_admin() {
   wp_enqueue_script(
      'gutenberg-composite-title-block-editor',
      plugins_url( 'block.js', __FILE__ ),
      array( 'wp-blocks', 'wp-element' )
   );

   wp_enqueue_style(
      'gutenberg-composite-title-block-editor',
      plugins_url( 'block.css', __FILE__ ),
      array()
   );
}
add_action( 'enqueue_block_editor_assets', 'nicola_gutenberg_composite_title_block_admin' );

// Load assets for frontend
function nicola_gutenberg_composite_title_block_frontend() {

   wp_enqueue_style(
      'gutenberg-composite-title-block-editor',
      plugins_url( 'block.css', __FILE__ ),
      array()
   );
}
add_action( 'wp_enqueue_scripts', 'nicola_gutenberg_composite_title_block_frontend' );