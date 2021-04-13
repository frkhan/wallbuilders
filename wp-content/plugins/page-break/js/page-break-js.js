/**
 * page-break-js.js
 * http://www.espreson.net
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2015, Espreson Media
 * http://www.espreson.net
 */

var jQueryScriptOutputted = false;

function initJQuery() {
    
    //if the jQuery object isn't available
    if (typeof(jQuery) == 'undefined') {
    
        if (! jQueryScriptOutputted) {
            
            jQueryScriptOutputted = true;
            
            document.write('<scr' + 'ipt type="text/javascript" src="//code.jquery.com/jquery-1.11.2.min.js"></scr' + 'ipt>');
        }
        setTimeout("initJQuery()", 50);
    } else {
                        
        jQuery(function($) {  
           
						$( ".page-links" ).addClass( "page-break-custom" );

                        var newClass = document.createElement("ul");

                        $( "ul.page-break-custom:last" ).removeClass( "page-break-custom" ).addClass( "paged" );

        });
    }
            
}
initJQuery();

