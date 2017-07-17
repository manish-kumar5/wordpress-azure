 
"use strict";
var login = jQuery('body.login #user_login');
var pass = jQuery('body.login #user_pass');

if(jQuery(login).length > 0 && jQuery(pass).length > 0) {
	
	jQuery(login).change(function() {
		"use strict";
		if (jQuery(login).val() != '') {
			jQuery(login).addClass('not_empty');
		}
		else
			jQuery(login).removeClass('not_empty');
	});
	
	jQuery(pass).change(function() {
		"use strict";
		if (jQuery(pass).val() != '') {
			jQuery(pass).addClass('not_empty');
		}
		else
			jQuery(pass).removeClass('not_empty');
	});
}