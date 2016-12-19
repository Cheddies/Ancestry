/* Ancestry.com Omniture Page-Level Configuration
/************************** NON-CORE CONFIGURATION **************************/
function s_domain_standards(){
	var track_dls        = true;
	var track_externals  = true;
	var internal_filters = 'javscript:,*.ancestry.*';
	var leave_query      = false;
	var domainName       = document.location.hostname;

	//Props and Evars
	s.prop20 = s.prop20 ? s.prop20 : s.getQueryParam('o_ufc');
	s.prop21 = s.prop21 ? s.prop21 : s.getQueryParam('o_cvc');

	s.eVar4 = s.eVar4 ? s.evar4 : s.getQueryParam('o_iid');
	s.eVar23 = s.prop23 ? s.prop23 : 'unknown';

	//First Party Cookies
	if (domainName.indexOf("ancestry") > -1) {
	    domainName = domainName.substring(domainName.indexOf("ancestry"), domainName.length)
	} else if (domainName.indexOf("mfcreative") > -1) {
	    domainName = domainName.substring(domainName.indexOf("mfcreative"), domainName.length);
	}
	s.trackingServer = "metrics." + domainName;
	s.trackingServerSecure = "smetrics." + domainName;
	s.visitorMigrationKey = "4DD6DCB5";
	s.visitorMigrationServer = s_account + ".112.2o7.net";
	s.visitorMigrationServerSecure = s_account + ".112.2o7.net";
}

// Call core
if ("https:" == document.location.protocol) {
    document.write("<scr" + "ipt type='text/javascript' src='https://a248.e.akamai.net/7/248/7051/v001/origin.c.mfcreative.com/js/omniture_core.js'></scr" + "ipt>");
} else {
    document.write("<scr" + "ipt type='text/javascript' src='http://c.mfcreative.com/js/omniture_core.js'></scr" + "ipt>");
}

