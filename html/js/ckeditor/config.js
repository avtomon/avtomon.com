/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    config.toolbar = [
        { name: 'document', items: [ 'Source', 'Preview'] },
        { name: 'clipboard', items: [ 'PasteFromWord' ] },
        { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight' ] },
        { name: 'links', items: [ 'Link', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Video', 'code' ] },
        { name: 'styles', items: [ 'FontSize' ] }
    ];

    // The default plugins included in the basic setup define some buttons that
    // are not needed in a basic editor. They are removed here.
    config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Subscript,Superscript';

    // Dialog windows are also simplified.
    config.removeDialogTabs = 'link:advanced';

    config.extraPlugins = 'video,code';

    config.allowedContent = true;

    config.filebrowserBrowseUrl = '/html/js/ckeditor/ckfinder/ckfinder.html?type=Files';
    config.filebrowserVideoBrowseUrl = '/html/js/ckeditor/ckfinder/ckfinder.html?type=Video';
    config.filebrowserImageBrowseUrl = '/html/js/ckeditor/ckfinder/ckfinder.html?type=Images';

    config.filebrowserUploadUrl = '/html/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserVideoUploadUrl = '/html/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Video';
    config.filebrowserImageUploadUrl = '/html/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

};
