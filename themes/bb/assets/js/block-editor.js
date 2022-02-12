// wp.blocks.registerBlockStyle( 'core/quote', {
//     name: 'fancy-quote',
//     label: 'Fancy Quote',
// } );

wp.domReady( function () {
    // reference slug to remove block
    // "core/quote" is data-type attributec
    wp.blocks.unregisterBlockStyle( 'core/quote', 'large' );
    wp.blocks.unregisterBlockStyle( 'core/quote', 'plain' );
} );