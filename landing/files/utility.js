
function delCookie(cookie_name)
{
    var exp = new Date();  
    exp.setTime(exp.getTime() - 1);   
	document.cookie = cookie_name + "=" + getCookie(cookie_name) + "; expires=" + exp.toGMTString();
}

function setCookie(cookie_name,cookie_val,cookie_path) 
{
	setCookieEx(cookie_name, cookie_val, cookie_path, null);
}

function setCookieEx(cookie_name,cookie_val,cookie_path,cookie_expires) 
{
	setCookieEx2(cookie_name,cookie_val,cookie_path,cookie_expires,null);
}

function setCookieEx2(cookie_name,cookie_val,cookie_path,cookie_expires,cookie_domain)
{
	if ( null != cookie_name )
		cookie_name = cookie_name.toUpperCase();

	var	new_cookie = cookie_name + "=" + cookie_val + ";";
	new_cookie += " path=";
	new_cookie += ( null != cookie_path ) ? cookie_path : "/";
	if(null != cookie_expires)
	{
		new_cookie += "; expires=" + cookie_expires;
	}
	if(null != cookie_domain)
	{
		new_cookie += "; domain=" + cookie_domain;
	}
	document.cookie = new_cookie;
}

function getCookieVal(cookie_text,offset,delim)
{
	var endstr = cookie_text.indexOf(delim,offset);
	if ( endstr == -1 )
	{
		endstr = cookie_text.length;
	}
	return decodeURIComponent(cookie_text.substring(offset,endstr));
}

function getCookie(cookie_name) 
{
	var arg		= cookie_name.toLowerCase() + "=";
	var alen	= arg.length;
	var clen	= document.cookie.length;
	var i		= 0;
	
	while ( i < clen )
	{
		var j = i + alen;
		if ( document.cookie.substring(i,j).toLowerCase() == arg )
		{
			return getCookieVal(document.cookie,j,';');
		}
		i = document.cookie.indexOf(" ",i) + 1;
		if ( i == 0 )
			break;
	}
	return null;
} 

function getDictionaryCookie(cookie_name,key)
{
	var dictionary = getCookie(cookie_name);
	if ( null == dictionary )
		return null;

	var dict_src = dictionary.toLowerCase();
	var dict_key = key.toLowerCase() + '=';
	var startPos = -1;
	
	do
	{
		startPos = dict_src.indexOf(dict_key,startPos + 1);
		if ( -1 != startPos && (0 == startPos || '&' == dict_src.charAt(startPos-1)) )
		{
			return getCookieVal(dictionary,startPos + dict_key.length,'&');
		}
	}
	while ( -1 != startPos );
	return null;
}

function setDictionaryCookieEx(cookie_name,key,value,path,expires,domain)
{
	if ( null != cookie_name )
		cookie_name = cookie_name.toUpperCase();
	if ( null != key )
		key = key.toUpperCase();

	var dictionary = getCookie(cookie_name);
	if ( null == dictionary )
	{
		dictionary = key + "=" + value;
	}
	else
	{
		var dict_src = dictionary.toLowerCase();
		var start_key = dict_src.indexOf(key.toLowerCase()+"=");
		if(-1 == start_key) // key was not there
		{
			if(dictionary.length > 0)
				dictionary = dictionary + "&";
			dictionary = dictionary + key + "=" + value;
		}
		else
		{
			var end_key = dict_src.indexOf("&", start_key);
			if(-1 == end_key) // no keys after
			{
				if(0 == start_key) // no keys before no keys after
					dictionary = key + "=" + value;
				else // keys before not after
					dictionary = dictionary.substring(0,start_key) + key + "=" + value;
			}
			else // key is somewhere in middle
			{
				dict_src = dictionary;
				dictionary = "";
				if(start_key > 0) // keys before
					dictionary = dict_src.substring(0,start_key);
				dictionary = dictionary + key + "=" + value;
				dictionary = dictionary + dict_src.substring(end_key);
			}
		}
	}
	setCookieEx2(cookie_name, dictionary, path, expires, domain);
}

function setDictionaryCookie(cookie_name,key,value,path,expires)
{
	setDictionaryCookieEx(cookie_name,key,value,path,expires,null);
}
function setVarsCookie(key, value)
{
	var exp = new Date();  
    exp.setFullYear(exp.getFullYear() + 20 ,0,14);
	setDictionaryCookieEx('VARS', key, value, null, exp.toGMTString(), getRootDomain());
}
function getVarsCookie(key)
{	// see /include/utility.inc
	return getDictionaryCookie('VARS',key);
}
function getVars2Cookie(key)
{	// see /include/utility.inc
	return getDictionaryCookie('VARSESSION',key);
}

function areBrowserCookiesEnabled()
{
	var cookie_name = "cookie_test";
	
	setCookie(cookie_name,"true","/");
	if ( null == getCookie(cookie_name) )
	{
		return false;
	}
	delCookie(cookie_name);
	return true;
}

function isAOLEnvironment()
{
	return (-1 != navigator.userAgent.indexOf("AOL"));
}

function GetPageScheme()
{
	return (null != document.location && null != document.location.href && document.location.href.toLowerCase().indexOf('https://') == 0 ) ? 'https' : 'http';
}

function getRootDomain() 
{
	var d = document.domain.split(".");
	var n = d.length;
	var t = d[n-2] + "." + d[n-1];
	if (d[n-1].length > 2) 
		return t;
	else if(d[n-2] == "com" || d[n-2] == "co") {
		return d[n-3] + "." + t;
	}
	return t;
}