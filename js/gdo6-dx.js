"use strict";
$(function(){
	let locale = window.GDO_LANGUAGE;
	console.log('Locale: '+locale);
	DevExpress.localization.locale(locale);
});
