/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar_Admin =  
		[ 
			['Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ],
			[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],
			[ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ],
			[ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ],
			[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ],
			[ 'Link','Unlink','Anchor' ],
			[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ],
			[ 'Styles','Format','Font','FontSize' ],
			[ 'TextColor','BGColor' ],
			[ 'Maximize', 'ShowBlocks','-','About' ]
		];
	config.extraPlugins = 'codemirror';
	config.resize_enabled = false;
	config.fullPage = false;
	config.skin = 'moono';
	
	config.enterMode = CKEDITOR.ENTER_P;
	config.shiftEnterMode = CKEDITOR.ENTER_BR;
	config.filebrowserBrowseUrl = CKEDITOR.getUrl('ckfinder/ckfinder.html'); 
	config.filebrowserImageBrowseUrl = CKEDITOR.getUrl('ckfinder/ckfinder.html?type=Images'); 
	config.filebrowserFlashBrowseUrl = CKEDITOR.getUrl('ckfinder/ckfinder.html?type=Flash'); 
	config.filebrowserUploadUrl = CKEDITOR.getUrl('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'); 
	config.filebrowserImageUploadUrl = CKEDITOR.getUrl('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'); 
	config.filebrowserFlashUploadUrl = CKEDITOR.getUrl('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash');
};
