/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'vi';
	config.uiColor = '#AADC6E';
	// toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', 'link']
	config.toolbar = [
		{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat'] },
		{ name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Blockquote'] },
		{ name: 'links', items: ['Link', 'Unlink'] },
		{ name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar'] },
		{ name: 'styles', items: ['Styles', 'Format'] },
		{ name: 'tools', items: ['Maximize'] },
		// image and media
		{ name: 'image', items: ['Image', 'Iframe'] },
		{ name: 'media', items: ['Audio', 'Video'] },
		// { name: 'document', items: ['Source'] }
	];
};
