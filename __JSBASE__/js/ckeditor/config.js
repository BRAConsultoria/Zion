﻿/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'pt-br';
	// config.uiColor = '#AADC6E';
	var baseUrl = 'http://www.arraialdaforquilha.org.br/__FMOLD__/__JSBASE__/js/';
	
	config.filebrowserBrowseUrl = baseUrl+'ckeditor/ckfinder/ckfinder.html',
	config.filebrowserImageBrowseUrl = baseUrl+'ckeditor/ckfinder/ckfinder.html?type=Images',
	config.filebrowserFlashBrowseUrl = baseUrl+'ckeditor/ckfinder/ckfinder.html?type=Flash',
	config.filebrowserUploadUrl = baseUrl+'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
	config.filebrowserImageUploadUrl = baseUrl+'ckeditor/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
	config.filebrowserFlashUploadUrl = baseUrl+'ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
};