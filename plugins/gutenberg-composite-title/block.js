var el = wp.element.createElement;

wp.blocks.registerBlockType('nicola-gutenberg/composite-title-block', {
	title: 'Composite Title',		// Block name visible to user
	icon: 'heading',	// Toolbar icon can be either using WP Dashicons or custom SVG
	category: 'text',	// Under which category the block would appear
	attributes: {			// The data this block will be storing
		span: { type: 'string', source: 'text', selector: 'span' },			// First word of heading in span tag
		title: { type: 'string', source: 'text', selector: 'h2'}  // Rest of heading in h2 tag
	},
	edit: function(props) {
		// How our block renders in the editor in edit mode
		
      function updateSpan( newdata ) {
	      props.setAttributes( { span: newdata } );
	   }

       function updateTitle( newdata ) {
        props.setAttributes( { title: newdata } );
     }

		return el( 'div', 
			{ 
				className: 'composite-title'
			}, 
			el(
				wp.blockEditor.RichText, 
				{
					tagName: 'span', 
					placeholder: 'Enter first word here...',
					value: props.attributes.span,
					onChange: updateSpan,
				}
			),
            el(
				wp.blockEditor.RichText, 
				{
					tagName: 'h2', 
					placeholder: 'Enter rest of title here...',
					value: props.attributes.title,
					onChange: updateTitle,
				}
			)
			// el(
			// 	wp.editor.RichText,
            // {
            //    tagName: 'p',
            //    onChange: updateContent,
            //    value: props.attributes.content,
            //    placeholder: 'Enter description here...'
            // }
         ); // End return

		// );	

	},	// End edit()
	save: function(props) {
		// How our block renders on the frontend
		
		return el( 'div',
		{
			className:'composite-title'
		},
		el(
			wp.blockEditor.RichText.Content, {
				tagName: 'span',
				value: props.attributes.span
			 }),
		el(
			wp.blockEditor.RichText.Content, {
				tagName: 'h2',
				value: props.attributes.title
			 })
			
            
			
		);	// End return
		
	}	// End save()
});