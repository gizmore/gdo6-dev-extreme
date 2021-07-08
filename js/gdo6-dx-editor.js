"use strict";

//You can create the HtmlEditor widget using the following code.
//Read more at https://js.devexpress.com/Documentation/Guide/Widgets/Common/Advanced/3rd-Party_Frameworks_Integration_API/#Create_and_Configure_a_Widget.
$(function(){

	$('.gdt_message div.wysiwyg').each(function(){
		let textarea = $(this).find('textarea');
		new DevExpress.ui.dxHtmlEditor(this, {
			"height": '240px',
			"name": textarea.attr('name'),
			"placeholder": textarea.attr('placeholder'),
			"readOnly": textarea.attr('disabled') || textarea.attr('read-only'),
			"toolbar": {
				"items": [
					'background',
					'bold',
					'color',
					'font',
					'italic',
					'link',
					'image',
					'size',
					'strike',
					'subscript',
					'superscript',
					'underline',
					'blockquote',
			         {
			             "name": "header",
			             "acceptedValues": [1, 2, 3, false]
			         },
					'increaseIndent',
					'decreaseIndent',
					'orderedList',
					'bulletList',
					'alignLeft',
					'alignCenter',
					'alignRight',
					'alignJustify',
					'codeBlock',
//					'variable',
					'separator',
					'undo',
					'redo',
					'clear',
					'insertTable',
					'insertRowAbove',
					'insertRowBelow',
					'insertColumnLeft',
					'insertColumnRight',
					'deleteColumn',
					'deleteRow',
					'deleteTable'
			     ]
			 },
			 "value": textarea.val(),
			 "variables": {
			     "dataSource": [
			         "FirstName",
			         "LastName",
			         "Company"
			     ],
			     "escapeChar": [ "{", "}" ]
			 }
		});
	});

});
