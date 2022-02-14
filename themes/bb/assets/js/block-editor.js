// wp.blocks.registerBlockStyle( 'core/post-title', {
//     name: 'fancy-title',
//     label: 'Fancy Title',
// } );

wp.blocks.registerBlockStyle( 'core/image', {
    name: 'boxed-image',
    label: 'Boxed Image',
} );

wp.blocks.registerBlockStyle( 'core/media-text', {
    name: 'boxed-media-text',
    label: 'Boxed Media Text',
} );

wp.blocks.registerBlockStyle( 'core/video', {
    name: 'boxed-video',
    label: 'Boxed Video',
} );

wp.blocks.registerBlockStyle( 'core/table', {
    name: 'striped',
    label: 'Striped Table',
} );

wp.blocks.registerBlockStyle( 'core/button', {
    name: 'secondary',
    label: 'Secondary Button',
} );

wp.blocks.registerBlockStyle( 'core/button', {
    name: 'tertiary',
    label: 'Tertiary Button',
} );

wp.blocks.registerBlockStyle( 'core/group', {
    name: 'collage-centered',
    label: 'Collage Group Centered',
} );

wp.blocks.registerBlockStyle( 'core/group', {
    name: 'collage-left',
    label: 'Collage Group Left',
} );

wp.blocks.registerBlockStyle( 'core/paragraph', {
    name: 'emphasis',
    label: 'Emphasis',
} );

wp.blocks.registerBlockStyle( 'core/heading', {
    name: 'fancy-title',
    label: 'Fancy Title',
} );

wp.domReady( function () {
    // reference slug to remove block
    // "core/quote" is data-type attributec
    wp.blocks.unregisterBlockStyle( 'core/image', 'rounded' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
    // wp.blocks.unregisterBlockStyle( 'core/quote', 'plain' );
} );