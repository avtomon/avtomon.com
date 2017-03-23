/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

    config.toolbar = [
        { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
        { name: 'paragraph', items: [ 'NumberedList', 'BulletedList' ] },
        { name: 'links', items: [ 'Link' ] },
        { name: 'insert', items: [ 'Image', 'Video' ] }
    ];

    // The default plugins included in the basic setup define some buttons that
    // are not needed in a basic editor. They are removed here.
    config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Underline,Strike,Subscript,Superscript';

    // Dialog windows are also simplified.
    config.removeDialogTabs = 'link:advanced';

    config.extraPlugins = 'video';

    config.allowedContent = true;

    config.resize_dir = 'both';

    config.filebrowserBrowseUrl = '/js/ckeditor/ckfinder/ckfinder.html?type=Files';
    config.filebrowserVideoBrowseUrl = '/js/ckeditor/ckfinder/ckfinder.html?type=Video';
    config.filebrowserImageBrowseUrl = '/js/ckeditor/ckfinder/ckfinder.html?type=Images';

    config.filebrowserUploadUrl = '/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserVideoUploadUrl = '/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Video';
    config.filebrowserImageUploadUrl = '/js/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';

};
