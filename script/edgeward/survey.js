/**
 * Survey modal popup
 */

$(document).ready(function(){
	var d = new Date();
	var curr_date = padDigits(d.getDate(), 2);	
	var curr_month = padDigits(d.getMonth()+1, 2);	
	var curr_year = d.getFullYear();
	var dateNum = '' + curr_year + curr_month + curr_date;

	//if the cookie is not set, show the popup
	if(!getCookie('ancestryshop_survey_1') && (dateNum*1) <= 20100731 ) showPop();		
	
	function showPop(){
		var overlayString = '<div id="surveyPop"></div>';
		$("#container").append(overlayString);
		//change this to "/" on live
		var basePath = '/';
		var surveyContent = basePath + "survey.php";
		$("#surveyPop").load(surveyContent,{mung:10},function(){
			//attach click events
			$(".survey_btn").click(function(){
				$("#surveyPop").hide();
				var btnId = $(this).attr('id');
				if(btnId == 'sbtn_yes'){
					setCookie( 'ancestryshop_survey_1','1', 730,basePath);
					return true;
				} 
				else if(btnId == 'sbtn_later'){
					setCookie( 'ancestryshop_survey_1','1', 1,basePath);
				} 
				else if(btnId == 'sbtn_no'){
					setCookie( 'ancestryshop_survey_1','1', 730,basePath);
				}
				return false;
			});
			//show the div
			$("#surveyPop").show();
		});
		
	}
});


function getCookie(name) {

	name = name + "=";
	var carray = document.cookie.split(';');

	for(var i=0;i < carray.length;i++) {
		var c = carray[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	}

	return null;
}


function setCookie( name, value, expires, path, domain, secure )
{
	// set time, it's in milliseconds
	var today = new Date();
	today.setTime( today.getTime() );
	
	/*
	if the expires variable is set, make the correct
	expires time, the current script below will set
	it for x number of days, to make it for hours,
	delete * 24, for minutes, delete * 60 * 24
	*/
	if ( expires )
	{
	expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}

function padDigits(n, totalDigits){ 
    n = n.toString(); 
    var pd = ''; 
    if (totalDigits > n.length) 
    { 
        for (i=0; i < (totalDigits-n.length); i++) 
        { 
            pd += '0'; 
        } 
    } 
    return pd + n.toString(); 
} 
