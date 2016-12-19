var isMac = navigator.appVersion.indexOf("Mac") != -1;
var isIE4 = (navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >=4);
var isNN4 = (navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >=4);

var pageURL = document.location.href;
var pageURLArray = pageURL.substring(7).split("/");
var currentTab = pageURLArray[1].split("?")[0];
var currentLinks = ((pageURLArray.length > 2) ? pageURLArray[2].split("?")[0] : "");
var currentSearch = ((pageURLArray.length > 4) ? pageURLArray[4] : "");

function getParams()
{
	var params = new Object();
	var query = location.search.substring(1);
	var pairs = query.split("&");
	for (var i = 0; i < pairs.length; i++)
	{
		var pos = pairs[i].indexOf('=');
		if (pos == -1) 
			continue;
		var argname = pairs[i].substring(0,pos);
		var value = pairs[i].substring(pos+1);
		// First, replace any plus signs with spaces
		while ((pos = value.indexOf("+")) != -1)
		{
			value = value.substring(0,pos) + ' ' + value.substring(pos+1);
		}
   	params[argname] = unescape(value);
	}
	return params;
}

function getParam(strParam)
{
	return strParam ? strParam : "";
}

function ex(form){
	window.location.href=form.s.options[form.s.selectedIndex].value;
}

function censuspdf(pdfUrl)
{
	var leftPos;
	if (screen){
		leftPos = screen.width-550-15;}
	self.name="ancmain";
	openWin = window.open(pdfUrl,"anc","toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1,top=5,left="+leftPos+",width=550,height=550");
	if (openWin){openWin.focus();}
}

function help(helpURL){
var leftPos;
if (screen){
	leftPos = screen.width-375;}
self.name="ancmain";
helpWin = window.open(helpURL,"anchelp","toolbar=0,location=0,status=0,menubar=0,scrollbars=0,resizable=0,top=5,left="+leftPos+",width=360,height=400");
if (helpWin){helpWin.focus();}
}

function myaccount(myaccountURL){
	window.location.href=myaccountURL;
}

function makeArray(n) {
this.length = n;
return this;
}
monthNames = new makeArray(12);
monthNames[1] = "January";
monthNames[2] = "February";
monthNames[3] = "March";
monthNames[4] = "April";
monthNames[5] = "May";
monthNames[6] = "June";
monthNames[7] = "July";
monthNames[8] = "August";
monthNames[9] = "September";
monthNames[10] = "October";
monthNames[11] = "November";
monthNames[12] = "December";
function dateString(oneDate) {
var theMonth = monthNames[oneDate.getMonth() + 1];
var theYear = oneDate.getFullYear();
return theMonth + " " + oneDate.getDate() + ", " + theYear;}

// used by Data Annotations
function launchDA(_URL)
{
var leftPos;
if(screen)
{leftPos=screen.width-555;}
self.name="ancmain";daWin=window.open(_URL,"daWin","toolbar=0,location=0,status=0,menubar=0,scrollbars=1,resizable=1,top=5,left="+leftPos+",width=540,height=400");
if(daWin){daWin.focus();}
}

// Used with the button bar
var CHOME = 0;
var CSEARCH = 1;
var CTREES = 2;
var CBOARDS = 3;
var CLEARN = 4;
var CSHOPS = 5;
var CHELP = 6;

