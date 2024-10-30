// Start color picker code
jQuery(document).ready(function(jQuery){
    jQuery('.ifpwap-background-color').wpColorPicker();
    jQuery('.ifpwap-theme-color').wpColorPicker();
});
// End color picker code

// Start icon upload code
jQuery( function(jQuery){
	// on upload button click
	jQuery( 'body' ).on( 'click', '.ifpwap-icon-upload', function( event ){
		event.preventDefault(); // prevent default link click and page refresh
		
		const button = jQuery(this)
		const imageId = button.next().next().val();

		const customUploader = wp.media({

			title: 'Insert image', // modal window title
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: true

		}).on( 'select', function() { // it also has "open" and "close" events
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();
			button.removeClass( 'button' ).html( '<img src="' + attachment.url + '">'); // add image instead of "Upload Image"
			button.next().show(); // show "Remove image" link
			button.next().next().val( attachment.id ); // Populate the hidden field with image ID
		})
		
		// already selected images
		customUploader.on( 'open', function() {

			if( imageId ) {
			  const selection = customUploader.state().get( 'selection' )
			  attachment = wp.media.attachment( imageId );
			  attachment.fetch();
			  selection.add( attachment ? [attachment] : [] );
			}
			
		})

		customUploader.open()
	
	});

	// on remove button click
	jQuery( 'body' ).on( 'click', '.ifpwap-icon-remove', function( event ){
		event.preventDefault();
		const button = jQuery(this);
		button.next().val( '' ); // emptying the hidden field
		button.hide().prev().addClass( 'button' ).html( 'Upload image' ); 
		// replace the image with text
	});
});
// End icon upload code