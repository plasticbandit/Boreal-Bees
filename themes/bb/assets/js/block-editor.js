// wp.blocks.registerBlockStyle( 'core/post-title', {
//     name: 'fancy-title',
//     label: 'Fancy Title',
// } );

wp.blocks.registerBlockStyle( 'core/image', {
    name: 'boxed-image',
    label: 'Boxed Image',
} );

wp.blocks.registerBlockStyle( 'core/table', {
    name: 'striped',
    label: 'Striped Table',
} );

wp.blocks.registerBlockStyle( 'core/heading', {
    name: 'fancy-title',
    label: 'Fancy Title',
} );

wp.domReady( function () {
    // reference slug to remove block
    // "core/quote" is data-type attributec
    wp.blocks.unregisterBlockStyle( 'core/image', 'rounded' );
    // wp.blocks.unregisterBlockStyle( 'core/quote', 'plain' );
} );