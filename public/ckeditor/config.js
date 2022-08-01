/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */
var placeholders =  ['customers.name','customers.address','customers.pincode','customers.country_name','customers.state_name','customers.city_name','companies.name','companies.company_name','companies.address','companies.pincode','companies.country_name','companies.state_name','companies.city_name','estimates.estimate_no','estimates.estimate_date'];
// $.ajax({
//     url: 'getvalues.php',
//     async: false,
//     dataType: 'json',
//     success: function(data) {
//         placeholders =  data;
//     }
// });

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.toolbarGroups = [
        { name: 'clipboard',   groups: [ 'undo' ]}, //'clipboard',
        // { name: 'editing',  groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'links' },
        // { name: 'insert' },
        // { name: 'forms' },
        { name: 'tools' },
        { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'others' },
        // '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'styles' },

        { name: 'colors' },
        // { name: 'about' },
        { name: 'holders', groups: ['placeholder_select']}
    ];
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    // config.removeDialogTabs = 'image:advanced;link:advanced';

    // Placeholders
    config.placeholder_select = {
        // placeholders: placeholders,
        format: '${%placeholder%}'
    };

    config.extraPlugins = 'richcombo,placeholder_select,sourcedialog';
    // config.placeholder_select= {
    //     placeholders: ['First','last']
    // };
};
