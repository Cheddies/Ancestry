	// If the browser supports getElementById (ie. has JS) allow this function and disable the css hover
	if (document.getElementById) {
		document.write('<style type="text/css"> #g_footer #sites ul li:hover div {display:none;}</style>');

		var droplink = document.getElementById("droplink");
		var droplist = document.getElementById("droplist");

		if (droplink != null)
			droplink.onclick = function(){toggle();return false;}
		function toggle(){
			var overlay = document.getElementById("ftr_overlay");

			if (droplist.style.display == "block")
			{
				droplist.style.display = "none";
				overlay.style.display = "none";
				droplist.style.zIndex = '-2';
				overlay.style.zIndex = '-1';
			}
			else
			{
				overlay.style.display = "block";
				droplist.style.display = "block";
				droplist.style.zIndex = '92';
				overlay.style.zIndex = '90';
			}
		};

		var objBody = document.getElementsByTagName("body").item(0);
		
		// create overlay div and hardcode some functional styles (aesthetic styles are in CSS file)
		var objOverlay = document.createElement("div");
		objOverlay.setAttribute('id','ftr_overlay');
		objOverlay.onclick = function(){toggle();return false;}	//function () {droplist.style.display = "none"; return false;}
		//objOverlay.style.display = 'none';
		objOverlay.style.position = 'absolute';
		objOverlay.style.top = '0';
		objOverlay.style.left = '0';
		objOverlay.style.zIndex = '90';
		objOverlay.style.width = '100%';
		// set height of Overlay to take up whole page and show
		var arrayPageSize = getPageSize();
		objOverlay.style.height = (arrayPageSize[1] + 'px');
		objOverlay.style.display = 'none';
		objBody.insertBefore(objOverlay, objBody.firstChild);
	};
		
	// getPageSize()
	// Returns array with page width, height and window width, height
	// Core code from - quirksmode.org
	// Edit for Firefox by pHaez
	function getPageSize(){
		
		var xScroll, yScroll;
		
		if (window.innerHeight && window.scrollMaxY) {	
			xScroll = document.body.scrollWidth;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
		
		var windowWidth, windowHeight;
		if (self.innerHeight) {	// all except Explorer
			windowWidth = self.innerWidth;
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	
		
		// for small pages with total height less then height of the viewport
		if(yScroll < windowHeight){
			pageHeight = windowHeight;
		} else { 
			pageHeight = yScroll;
		}

		// for small pages with total width less then width of the viewport
		if(xScroll < windowWidth){	
			pageWidth = windowWidth;
		} else {
			pageWidth = xScroll;
		}


		arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
		return arrayPageSize;
	}