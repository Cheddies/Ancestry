if ('undefined' == typeof TGN) { TGN = {}; }
if ('undefined' == typeof TGN.Header) { TGN.Header = {}; }
if ('undefined' == typeof TGN.Header.Drawer) { TGN.Header.Drawer = {}; }
if ('undefined' == typeof TGN.Header.Navigation) { TGN.Header.Navigation = {}; }
if ('undefined' == typeof _HDrwr) { _HDrwr = TGN.Header.Drawer; }
if ('undefined' == typeof _HNav) { _HNav = TGN.Header.Navigation; }
if ('undefined' == typeof _YDom) { _YDom = YAHOO.util.Dom; }
if ('undefined' == typeof _YConn) { _YConn = YAHOO.util.Connect; }
if ('undefined' == typeof _YDDM) { _YDDM = YAHOO.util.DragDropMgr; }
if ('undefined' == typeof _YEvent) { _YEvent = YAHOO.util.Event; }
if ('undefined' == typeof _YAnim) { _YAnim = YAHOO.util.Anim; }

/* NOTE ABOUT COMMENTS!!  In order for the header to work cross domains, please use the "slash-star" comments instead of the slash-slash comments */

/* ======================= GLOBAL ======================================= */

var _drawerObjs; /* array of drawer objects */

var _addQlDialog;

var _showTodosView = true;

var _favsDrwr;
var _todoDrwr;
var _myAcctHovering = false;

//this variable is for chrome and safari because they cache the request if the service call has the same parameters.
var _lameChomeCounter = 0;

var _HNavTreeMoreString;
var _HNavTreeError;
var _HNavAddQlErrorMessage;
var _HNavAddQlSuccess;
var _HNavGetQlSuccess;
var _HNavTodoSuccess;
var _HNavCompletedTodos;
var _HNavCompletedCount = 0;
var _HNavSubZIndex = 99;
var _HNavTrees = [];

var treesMnu;
var searchMnu;
var communityMnu;
var learningMnu;

/* ======================================================================= */

_HNav.callLoader = function(functionName, functionArgs, onSuccessCall, onFailureCall)
{
	var loader = new YAHOO.util.YUILoader({
		onSuccess: onSuccessCall
	});

	var serviceCall;

	if (functionArgs != null)
	{
		serviceCall = _HNavServicePath + '?f=' + functionName + functionArgs;
	}
	else
	{
		serviceCall = _HNavServicePath + '?f=' + functionName;
	}

	loader.addModule({
		name: functionName,
		type: 'js',
		fullpath: serviceCall
	});

	loader.require(functionName);
	loader.insert();
};

/* ======================= Nav menu object ======================================= */

_HNav.AncMenu = function(headerDivId, mnuContainerId, mnuContentId, callbackFn, callbackPm) {
	this.headerObj = _YDom.get(headerDivId);
	this.mnuContainerObj = _YDom.get(mnuContainerId);
	this.mnuContentObj = _YDom.get(mnuContentId);
	if (this.headerObj && this.mnuContainerObj && this.mnuContentObj) {
		/*var menuItems = _YDom.getElementsBy(function (el){return true; }, 'li', mnuContainerId);
		if (menuItems && menuItems.length > 0)
		{
		_YEvent.addListener(menuItems, 'mouseover', _HNav.animColorMouseOver);
		_YEvent.addListener(menuItems, 'mouseout', _HNav.animColorMouseOut);
		}*/
		var width = this.headerObj.offsetWidth + 1;
		if (width < 185) {
			width = 185;
		}
		this.mnuContainerObj.style.width = width + 'px';
		this.mnuContentObj.style.width = width + 'px';
		this.recentHover = false;
		this.isHovering = false;
		this.isOpen = false;
		this.dynamicContentLoaded = true;
		this.height = _HNav.getElementHeight(mnuContainerId);
		this.callbackFn = callbackFn;
		this.callbackPm = callbackPm;
	}
};

/* =============================================================================== */

/*===============================================  Footer Links =======================================================*/

_HNav.toggleSitesBox = function(e)
{
	if (_YDom.get('ftrSitesContainer').style.display == 'block')
	{
		_YDom.get('ftrSitesContainer').style.display = 'none';
	}
	else
	{
		_YDom.get('ftrSitesContainer').style.display = 'block';
	}
};

_YEvent.addListener("ftrNavDD", "click", _HNav.toggleSitesBox);
_YEvent.addListener("closeFtrSitesX", "click", _HNav.toggleSitesBox);

/*=============================================== End Footer Links =======================================================*/



_HNav.dynamicCallBack = function(func)
{
	this.func = func;
};



_HNav.animColorMouseOver = function(e)
{
	var cAttributes = { backgroundColor: { from: '#fcfff3', to: '#e8eebb'} };
	var cAnim = new YAHOO.util.ColorAnim(this.id, cAttributes, 0.3);
	cAnim.animate();
};

_HNav.animColorMouseOut = function(e)
{
	var cAttributes = { backgroundColor: { from: '#e8eebb', to: '#fcfff3'} };
	var cAnim = new YAHOO.util.ColorAnim(this.id, cAttributes, 0.3);
	cAnim.animate();
};

_HNav.open = function(e, ancMenu)
{
	if (ancMenu.mnuContainerObj == null) return;	/* institutional may have no submenu */
	ancMenu.isHovering = true;
	ancMenu.recentHover = true;
	ancMenu.mnuContainerObj.style.zIndex = _HNavSubZIndex++;
	setTimeout(function() { _HNav.getDynamicContent(ancMenu); }, 250);
};
_HNav.close = function(e, ancMenu)
{
	ancMenu.headerObj.style.backgroundColor = '';
	ancMenu.isHovering = false;
	setTimeout(function() { _HNav.animClose(ancMenu); }, 500);
};

_HNav.getDynamicContent = function(ancMenu)
{
	if (ancMenu.isHovering == true && ancMenu.isOpen == false)
	{
		/* check to see if menu has callback function and if the user is logged in */
		if (_HNavUserLoggedIn == 'True' && ancMenu.callbackFn != null)
		{
			try
			{
				/* change class to spinner on menu, should be changed back in dynamic function */
				ancMenu.dynamicContentLoaded = false;
				/* don't start the spinner immediately */
				setTimeout(function() { _HNav.startSpinner(ancMenu); }, 1000);
				var fn = new _HNav.dynamicCallBack(ancMenu.callbackFn);
				fn.func();
			}
			catch (exc)
			{
				/* todo fail */
			}
		}
		else /* no dynamic content or the user isn't logged in, so just start open animation */
		{
			_HNav.animOpen(ancMenu);
		}
	}
};

_HNav.startSpinner = function(ancMenu)
{
	/* if we're still waiting for dynamic content to load */
	if (!ancMenu.dynamicContentLoaded)
	{
		/* change class to spinner on menu, should be changed back in dynamic function */
		ancMenu.headerObj.className += ' G-NavItemLoading';
		/* prevent the spinner from showing indefinitely if there is a problem in the dynamic function */
		setTimeout(function() { _HNav.stopSpinnerOpenMenu(ancMenu); }, 2000);
	}
};

_HNav.stopSpinnerOpenMenu = function(ancMenu)
{
	/* whether the content was loaded or not, just say we're done trying */
	ancMenu.dynamicContentLoaded = true;
	/* stop the spinner */
	ancMenu.headerObj.className = ancMenu.headerObj.className.replace("G-NavItemLoading", "");
	/* open the menu */
	_HNav.animOpen(ancMenu);
};

_HNav.animOpen = function(ancMenu)
{
	if (ancMenu.mnuContainerObj == null) return;	/* institutional may have no submenu */

	if (ancMenu.isHovering == true && ancMenu.isOpen == false)
	{
		ancMenu.mnuContainerObj.style.height = '1px';
		var height = _HNav.getElementHeight(ancMenu.mnuContentObj.id);
		ancMenu.mnuContainerObj.style.display = 'block';
		var attributes = { height: { from: 0, to: height} };
		var anim = new _YAnim(ancMenu.mnuContainerObj.id, attributes, 0.4, YAHOO.util.Easing.easeOut);
		anim.animate();
		/* setTimeout(function (){ancMenu.mnuContainerObj.style.height = height;}, 400); */
		ancMenu.isOpen = true;
	}
};
_HNav.animClose = function(ancMenu)
{
	if (ancMenu.mnuContainerObj == null) return;	/* institutional may have no submenu */
	if (ancMenu.recentHover == true)
	{
		setTimeout(function() { _HNav.animClose(ancMenu); }, 1000);
		ancMenu.recentHover = false;
	}
	else if (ancMenu.isHovering == false && ancMenu.isOpen == true)
	{
		ancMenu.isOpen = false;
		var height = _HNav.getElementHeight(ancMenu.mnuContainerObj.id);
		var attributes = { height: { from: height, to: 0} };
		var anim = new _YAnim(ancMenu.mnuContainerObj.id, attributes, 0.3, YAHOO.util.Easing.easeIn);
		anim.animate();
		/* setTimeout(function (){ document.getElementById(ancMenu.mnuContainerObj.id).style.height = 0; }, 400);	*/
	}
};

_HNav.getTrees = function()
{
	_HNav.callLoader('GetTrees', null, _HNav.getTreesSuccess, null);
};

_HNav.getTreesSuccess = function()
{
	if (!_HNavTreeError)
	{
		var trees = _HNavTrees;

		try
		{
			var totalCount = _HNavTreeTotalCount;
			var treesDomain = _HNavTreesDomain;
			if (trees != undefined && trees.length > 0)
			{
				var treeList = "";
				for (var i = 0; i < trees.length; i++)
				{
					treeList += "<li id=\"" + trees[i].tid + "\">";
					treeList += '<a href="http://' + treesDomain + '/pt/pedigree.aspx?tid=' + trees[i].tid + '">' + trees[i].name + '</a>';
					treeList += "</li>";
				}
				if (totalCount != undefined && totalCount > trees.length)
				{
					treeList += "<li id=\"moreTrees\">";
					treeList += '<a href="http://' + treesDomain + '/pt/treelist.aspx">' + _HNavTreeMoreString + '...</a>';
					treeList += "</li>";
				}
				document.getElementById('loadTrees').innerHTML = treeList;

				/*var newTrees = _YDom.getElementsBy(function (el){return true; }, 'li', 'loadTrees');
				if (newTrees && newTrees.length > 0)
				{*/
				/*remove, then re-add listeners for mouseover/out color animation */
				/*_YEvent.removeListener(newTrees, 'mouseover');
				_YEvent.removeListener(newTrees, 'mouseout');
				_YEvent.addListener(newTrees, 'mouseover', _HNav.animColorMouseOver);
				_YEvent.addListener(newTrees, 'mouseout', _HNav.animColorMouseOut);
				}*/
			}

			/* change class back (remove loading gif)*/
			_HNav.stopSpinnerOpenMenu(treesMnu);
		}
		catch (e)
		{
			_HNav.stopSpinnerOpenMenu(treesMnu);
		}
	}
	else
	{
		_HNav.stopSpinnerOpenMenu(treesMnu);
	}
};

_HNav.getTreesFail = function()
{
	/* just show default items */
	_HNav.stopSpinnerOpenMenu(treesMnu);
};

_HNav.MouseOverMyAcctDD = function()
{
    _myAcctHovering = true;   
    setTimeout("_HNav.OpenMyAcctDD()", 300);
};

_HNav.MouseOutMyAcctDD = function()
{
    _myAcctHovering = false;
    setTimeout("_HNav.CloseMyAcctDD()", 600);
};

_HNav.OpenMyAcctDD = function()
{
    if (_myAcctHovering == true)
    {
        var menu = document.getElementById('acctDD');
        menu.style.display = '';
    }
};
_HNav.CloseMyAcctDD = function()
{
    if (_myAcctHovering != true)
    {
        var menu = document.getElementById('acctDD');
        menu.style.display = 'none'; 
    }
};

_HNav.getElementHeight = function(Elem)
{
	var elem;

	if (document.getElementById)
	{
		elem = document.getElementById(Elem);
		elem.style.overflow = 'scroll';
	}
	else if (document.all)
	{
		elem = document.all[Elem];
	}
	elem.style.overflow = 'hidden';
	return elem.scrollHeight - 8;

};

_HNav.subMenuHover = function(navId)
{
	var nav = document.getElementById(navId);
	nav.style.backgroundColor = '#fcfff3';
	var x = nav.style.backgroundColor;
};

/* ======================================= Menu instances =============================================== */


_HNav.initMenus = function()
{
	try
	{
		treesMnu = new _HNav.AncMenu('treesNav', 'treesSubNav', 'treesContent', _HNav.getTrees);
		_YEvent.on('treesNav', 'mouseover', _HNav.open, treesMnu);
		_YEvent.on('treesNav', 'mouseout', _HNav.close, treesMnu);

		_YEvent.on('treesSubNav', 'mouseover', function() { _HNav.subMenuHover('treesNav'); });
	}
	catch (exc) { } /* tab is set to visible=false */

	try
	{
		searchMnu = new _HNav.AncMenu('searchNav', 'searchSubNav', 'searchContentNav', null);
		_YEvent.on('searchNav', 'mouseover', _HNav.open, searchMnu);
		_YEvent.on('searchNav', 'mouseout', _HNav.close, searchMnu);

		_YEvent.on('searchSubNav', 'mouseover', function() { _HNav.subMenuHover('searchNav'); });
	}
	catch (exc2) { } /* tab is set to visible=false */

	try
	{
		communityMnu = new _HNav.AncMenu('communityNav', 'communitySubNav', 'communityContentNav', null);
		_YEvent.on('communityNav', 'mouseover', _HNav.open, communityMnu);
		_YEvent.on('communityNav', 'mouseout', _HNav.close, communityMnu);

		_YEvent.on('communitySubNav', 'mouseover', function() { _HNav.subMenuHover('communityNav'); });
	}
	catch (exc3) { } /* tab is set to visible=false  */

	try
	{
		learningMnu = new _HNav.AncMenu('learningNav', 'learningSubNav', 'learningContentNav', null);
		_YEvent.on('learningNav', 'mouseover', _HNav.open, learningMnu);
		_YEvent.on('learningNav', 'mouseout', _HNav.close, learningMnu);

		_YEvent.on('learningSubNav', 'mouseover', function() { _HNav.subMenuHover('learningNav'); });
	}
	catch (exc4) { } /* tab is set to visible=false  */
};

_YEvent.onDOMReady(_HNav.initMenus);

/* ======================================= End Menu instances =============================================== */

/* ======================================= Drawer Obj ================================================*/

_HDrwr.Drawer = function(liId, leftId, rightId, containerId, linkId, emId, spanId, topId, contentId, noContentId, altContentId, noAltContentId, spinnerId, callbackFn)
{
	this.isOpen = false;
	this.li = _YDom.get(liId);
	this.left = _YDom.get(leftId);
	this.right = _YDom.get(rightId);
	this.container = _YDom.get(containerId);
	this.link = _YDom.get(linkId);
	this.em = _YDom.get(emId);
	this.span = _YDom.get(spanId);
	this.top = _YDom.get(topId);
	this.content = _YDom.get(contentId);
	this.noContent = _YDom.get(noContentId);
	this.altContent = null;
	if (altContentId != null)
	{
		this.altContent = _YDom.get(altContentId);
	}
	this.noAltContent = null;
	if (noAltContentId != null)
	{
		this.noAltContent = _YDom.get(noAltContentId);
	}
	this.callbackFn = callbackFn;
	this.spinner = _YDom.get(spinnerId);

	this.openTab = function()
	{

		_YDom.get(liId).style.marginTop = "-4px";
		_YDom.get(leftId).className = "DrwSelectL";
		_YDom.get(rightId).className = "DrwSelectR";
		_YDom.get(containerId).className = "DrwSelect";
		_YDom.get(linkId).className = "CalloutActive";
		_YDom.get(emId).className = "CallbublActv";
		_YDom.get(spanId).className = "CallBublLeftActv";
		_YDom.get(topId).style.display = 'block';
		_YDom.get(contentId).style.display = 'block';

	};

	this.close = function()
	{
		_YDom.get(liId).style.marginTop = "0px";
		_YDom.get(leftId).className = "";
		_YDom.get(rightId).className = "";
		_YDom.get(containerId).className = "";
		_YDom.get(linkId).className = "Callout";
		_YDom.get(emId).className = "Callbubl";
		_YDom.get(spanId).className = "";
		_YDom.get(noContentId).style.display = 'none';
		_YDom.get(spinnerId).style.display = 'none';
		if (altContentId != null)
		{
			_YDom.get(altContentId).style.display = 'none';
		}
		if (noAltContentId != null)
		{
			_YDom.get(noAltContentId).style.display = 'none';
		}
		this.isOpen = false;
	};
};

_HDrwr.closeDrwrTop = function(drawer)
{
	drawer.top.style.display = 'none';
	drawer.content.style.display = 'none';
};

_HDrwr.openDrawerTab = function(e, drawer)
{
	/* close all first */
	_HDrwr.closeAllDrawerTabs();

	drawer.openTab();

	/* get content */
	_HDrwr.getDynamicContent(drawer);

};

_HDrwr.animOpenDrawer = function(drawer)
{
	drawer.top.style.display = 'block';
	drawer.content.style.display = 'block';

	var height = _HNav.getElementHeight('DrwWrapper') + 11;
	if (drawer.isOpen == false)
	{
		var anim = new YAHOO.util.Anim('DrwSlider', { height: { to: height} }, 0.5, YAHOO.util.Easing.easeOut);
		anim.animate();
		setTimeout(function() { drawer.spinner.style.display = 'none'; }, 500);

	}
	else /* drawer is already open */
	{
		/* may need to adjust height if content was added */
		_YDom.get('DrwSlider').style.height = height + "px";
	}
	drawer.isOpen = true;
};

_HDrwr.closeAllDrawerTabs = function()
{
	if (_drawerObjs)
	{
		for (var i = 0; i < _drawerObjs.length; i++)
		{
			_drawerObjs[i].close();
		}
	}
};

_HDrwr.animClose = function()
{
	_HDrwr.closeAllDrawerTabs();
	var height = _HNav.getElementHeight('DrwWrapper') + 11;
	var anim = new YAHOO.util.Anim('DrwSlider', { height: { from: height, to: 0} }, 0.5, YAHOO.util.Easing.easeIn);
	anim.animate();
};

_HDrwr.dynamicCallBack = function(func)
{
	this.func = func;
};

_HDrwr.getDynamicContent = function(drawer)
{
	if (drawer.callbackFn != null)
	{
		try
		{
			var fn = new _HDrwr.dynamicCallBack(drawer.callbackFn);
			fn.func();
		}
		catch (exc)
		{
			/* todo fail */
		}
	}
};


/* ======================================= End Drawer Obj ================================================ */

/* ======================================= Header Drawer Drag Drop ================================================ */

_HDrwr.DragDrop = {
	initHdrTodo: function()
	{
		var hdrTodoTarget = new YAHOO.util.DDTarget("hdrTodos", "hdrTdList");
		var ul = document.getElementById("hdrTodos");

		hdrTodoTarget.unlock();

		if (ul)
		{
			var items = ul.getElementsByTagName("li");

			for (var i = 0; i < items.length; i = i + 1)
			{
				var todoDDItm = new _HDrwr.DDList(items[i].id, "hdrTdList");
			}
		}
	},
	initHdrQuickLinks: function()
	{
		var hdrQuickLinkTarget = new YAHOO.util.DDTarget("hdrFavs", "hdrFavList");
		var ul = document.getElementById("hdrFavs");

		hdrQuickLinkTarget.unlock();

		if (ul)
		{
			var items = ul.getElementsByTagName("li");

			for (var i = 0; i < items.length; i = i + 1)
			{
				var qlDDItem = new _HDrwr.DDList(items[i].id, "hdrQLList");
			}
		}
	}


};

_HDrwr.DDList = function(id, sGroup, config)
{

	_HDrwr.DDList.superclass.constructor.call(this, id, sGroup, config);

	this.logger = this.logger || YAHOO;
	var el = this.getDragEl();
	_YDom.setStyle(el, "opacity", 0.67); /*  The proxy is slightly transparent */

	this.goingUp = false;
	this.lastY = 0;
};

YAHOO.extend(_HDrwr.DDList, YAHOO.util.DDProxy, {

	startDrag: function(x, y)
	{
		this.logger.log(this.id + " startDrag");

		/* make the proxy look like the source element */
		var dragEl = this.getDragEl();
		var clickEl = this.getEl();
		_YDom.setStyle(clickEl, "visibility", "hidden");

		dragEl.innerHTML = clickEl.innerHTML;

		_YDom.setStyle(dragEl, "color", _YDom.getStyle(clickEl, "color"));
		_YDom.setStyle(dragEl, "backgroundColor", _YDom.getStyle(clickEl, "backgroundColor"));
		_YDom.setStyle(dragEl, "border", "2px solid gray");
	},

	endDrag: function(e)
	{

		var srcEl = this.getEl();
		var proxy = this.getDragEl();

		/* Show the proxy element and animate it to the src element's location */
		_YDom.setStyle(proxy, "visibility", "");
		var a = new YAHOO.util.Motion(proxy, { points: { to: _YDom.getXY(srcEl)} }, 0.2, YAHOO.util.Easing.easeOut);
		var proxyid = proxy.id;
		var thisid = this.id;

		/* Hide the proxy and show the source element when finished with the animation */
		a.onComplete.subscribe(function()
		{
			_YDom.setStyle(proxyid, "visibility", "hidden");
			_YDom.setStyle(thisid, "visibility", "");
		});
		a.animate();

		var parentEl = srcEl.parentNode;
		if (parentEl.id == "hdrTodos" || parentEl.id == "hdrFavs")
		{
			_HDrwr.reorderDrwrItems(parentEl.id);
		}
	},

	onDragDrop: function(e, id)
	{

		/* If there is one drop interaction, the li was dropped either on the list,
		or it was dropped on the current location of the source element. */
		if (_YDDM.interactionInfo.drop.length === 1)
		{

			/* The position of the cursor at the time of the drop (YAHOO.util.Point) */
			var pt = _YDDM.interactionInfo.point;

			/* The region occupied by the source element at the time of the drop */
			var region = _YDDM.interactionInfo.sourceRegion;

			/* Check to see if we are over the source element's location.  We will
			append to the bottom of the list once we are sure it was a drop in
			the negative space (the area of the list without any list items) */
			if (!region.intersect(pt))
			{
				var destEl = _YDom.get(id);
				var destDD = _YDDM.getDDById(id);
				destEl.appendChild(this.getEl());
				destDD.isEmpty = false;
				_YDDM.refreshCache();
			}

		}
	},

	onDrag: function(e)
	{

		/* Keep track of the direction of the drag for use during onDragOver */
		var y = _YEvent.getPageY(e);

		if (y < this.lastY)
		{
			this.goingUp = true;
		} else if (y > this.lastY)
		{
			this.goingUp = false;
		}

		this.lastY = y;
	},

	onDragOver: function(e, id)
	{

		var srcEl = this.getEl();
		var destEl = _YDom.get(id);

		/* We are only concerned with list items, we ignore the dragover
		notifications for the list.*/
		if (destEl.nodeName.toLowerCase() == "li")
		{
			var orig_p = srcEl.parentNode;
			var p = destEl.parentNode;

			if (this.goingUp)
			{
				p.insertBefore(srcEl, destEl); /* insert above */
			} else
			{
				p.insertBefore(srcEl, destEl.nextSibling); /* insert below */
			}

			_YDDM.refreshCache();
		}
	}
});

_HDrwr.reorderDrwrItems = function(uiId)
{
	try
	{
		var ulElmt = _YDom.get(uiId);
		var todoItems = ulElmt.getElementsByTagName("li");
		var orderedStr = "";

		for (i = 0; i < todoItems.length; i++)
		{
			orderedStr += todoItems[i].id + ",";
		}
		var args = '&orderedStr=' + orderedStr;
		_HNav.callLoader('ReorderDrawerItems', args, _HDrwr.reorder_Success, null);

	}
	catch (ex) { this.dump(ex); }
};

_HDrwr.reorder_Success = function()
{
	var success = _HNavReorderSuccess;
};

/*======================================= End Header Drag Drop ================================================ */

/*=================================== QuickLinks Section ============================================= */


/*========  QuickLinks Object ====================== */
_HDrwr.QuickLink = function(id, linkName, linkUrl)
{
	this.id = id;
	this.linkName = linkName;
	this.linkUrl = linkUrl;

	this.draw = function()
	{
		var html;
		var template = _YDom.get("favs_template");
		html = template.innerHTML;

		html = html.replace("__favId__", this.id);
		html = html.replace("__favId2__", this.id);
		html = html.replace("__favName__", unescape(this.linkName));
		html = html.replace("__favName2__", this.linkName);
		html = html.replace("__favURL__", this.linkUrl);

		return html;
	};
};

_HDrwr.getQuickLinks = function()
{
	/* if the request takes longer than a second, turn on the spinner */
	setTimeout(function() { if (!_favsDrwr.isOpen) { _favsDrwr.spinner.style.display = 'block'; } }, 500);
	var args = '&partnerId=' + _HNavPartnerId + '&subType=' + _HNavSubType + '&lameChrome=' + _lameChomeCounter++;
	_HNav.callLoader('GetQuickLinks', args, _HDrwr.getQuickLinks_Success, null);
};

_HDrwr.getQuickLinks_Success = function()
{
	if (_HNavGetQlSuccess)
	{
		_YDom.get("drwrError").style.display = 'none';
		_YDom.get('todoPhaseout').style.display = 'none';
		_HDrwr.drawQuickLinks(_HNavGetQlSuccess, _HNavQuickLinks, _HNavQlTotalCount);
	}
	else
	{
		_YDom.get("drwrError").style.display = 'block';
	}
	_HDrwr.closeDrwrTop(_todoDrwr);
	_HDrwr.animOpenDrawer(_favsDrwr);
	_favsDrwr.spinner.style.display = 'none';
};


_HDrwr.drawQuickLinks = function(success, quicklinks, totalCount)
{
	var container = _YDom.get("hdrFavs");
	container.innerHTML = "";
	if (success)
	{
		_YDom.get('favCount').innerHTML = "(" + totalCount + ")";
		if (quicklinks.length > 0)
		{
			_YDom.get('noQuickLinks').style.display = 'none';
			var jql;
			var quicklink;
			for (var i = 0; i < quicklinks.length; i++)
			{
				jql = quicklinks[i];
				quicklink = new _HDrwr.QuickLink(jql.id, jql.linkName, jql.linkUrl);
				container.innerHTML += quicklink.draw();
			}
			_HDrwr.DragDrop.initHdrQuickLinks();
		}
		else
		{
			_YDom.get('noQuickLinks').style.display = 'block';
		}
	}
	/*todo: display error
	else
	{
        
	}*/
};

_HDrwr.addNewQuickLink = function()
{
	var url = _YDom.get('ql_urlTxt');
	var name = _YDom.get('ql_nameTxt');
	if (url.value != "" && name.value != "")
	{
		if (url.value.indexOf('http://') != 0 && url.value.indexOf('https://') != 0)
			url.value = 'http://' + url.value;
		var urlEnc = encodeURIComponent(url.value);
		/* Important!  linkUrl needs to be the last parameter on the query string.  We assume that any
		query string parameters after linkUrl are part of linkUrl.  This allows us to save a url such as
		http://trees.ancestry.ca/pt/editperson.aspx?tid=373&pid=-2147316033&pg=32768 */
		var args = '&linkName=' + name.value + '&partnerId=' + _HNavPartnerId + '&subType=' + _HNavSubType + '&linkUrl=' + urlEnc;
		_HNav.callLoader('AddQuickLink', args, _HDrwr.addQuickLink_Success, null);
	}
	else
	{
		_YDom.get('addHdrQuickLink_needInfo').style.display = 'inline';
	}
};

_HDrwr.addQuickLink_Success = function()
{
	if (_HNavAddQlSuccess != null && _HNavAddQlSuccess == 'true')
	{
		_YDom.get('ql_urlTxt').value = "http://";
		_YDom.get('ql_nameTxt').value = "";
		_HDrwr.toggleAddQuickLink();
		_HDrwr.getQuickLinks();
	}
	/*todo: display error msg
	else
	{
        
	}*/
};

_HDrwr.deleteQuickLink = function(linkId, linkName)
{
	var confMsg = _HNavDeleteItemConf;
	if (confirm(confMsg + "\n\n" + unescape(linkName)))
	{
		var args = '&linkId=' + linkId;
		_HNav.callLoader('DeleteQuickLink', args, _HDrwr.deleteQuickLink_Success, null);
	}
};

_HDrwr.deleteQuickLink_Success = function()
{
	/*todo: check to see if it deleted and if not, display error msg */
	_HDrwr.getQuickLinks();
};

_HDrwr.toggleAddQuickLink = function(addFav)
{
	var addQL = _YDom.get('addHdrQuickLink');

	if (addFav)
	{
		var curUrl = location.href;
		var pageTitle = document.title;
		addQL.style.display = 'block';
		_YDom.get('ql_urlTxt').value = curUrl;
		_YDom.get('ql_nameTxt').value = pageTitle;
		_YDom.get('addHdrQuickLink_needInfo').style.display = 'none';
	}

	else
	{
		if (addQL.style.display == 'none')
		{
			addQL.style.display = 'block';
			var urlField = _YDom.get('ql_urlTxt');
			urlField.focus();
			if (urlField.createTextRange)
			{
				var FieldRange = urlField.createTextRange();
				FieldRange.moveStart('character', urlField.value.length);
				FieldRange.collapse();
				FieldRange.select();
			}
			_YDom.get('addHdrQuickLink_needInfo').style.display = 'none';
		}
		else { addQL.style.display = 'none'; }
	}


	return false;
};

_HNav.QuickLink = {};

_HNav.QuickLink.isFireFox = function()
{
	return navigator.userAgent.indexOf("Firefox") != -1;
};

_HNav.QuickLink.ancestryDomain = "www.ancestry.com";

_HNav.QuickLink.show = function(ancestryDomain)
{

	if (ancestryDomain != "undefined" && ancestryDomain.length > 0)
	{
		_HNav.QuickLink.ancestryDomain = ancestryDomain;
	}
	var availWidth;
	var availHeight;
	var dropSheet = document.createElement('div');
	dropSheet.setAttribute("id", "ql_dropSheet");
	if (_HNav.QuickLink.isFireFox())
	{
		availWidth = document.body.scrollWidth;
		availHeight = document.body.scrollHeight;
	}
	else
	{
		availWidth = document.body.clientWidth;
		availHeight = document.body.clientHeight;
	}
	dropSheet.style.width = availWidth + "px";
	dropSheet.style.height = availHeight + "px";

	var ql_dialogWidth = "370px";
	var ql_dialogHeight = "180px";
	var ql_dialog = document.createElement('div');
	ql_dialog.setAttribute("id", "ql_dialog");
	var template = document.getElementById("ql_dialogTemplate");
	ql_dialog.innerHTML = template.innerHTML;
	ql_dialog.style.display = "none";
	ql_dialog.style.width = ql_dialogWidth;
	ql_dialog.style.height = ql_dialogHeight;
	var bodyTag = document.getElementsByTagName('body')[0];
	bodyTag.insertBefore(ql_dialog, bodyTag.firstChild);
	bodyTag.insertBefore(dropSheet, ql_dialog);

	var windowWidth;
	var windowHeight;
	var dw = 370;
	var dh = 180;
	if (_HNav.QuickLink.isFireFox())
	{
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;
	}
	else
	{
		if (document.documentElement.clientHeight > 0)
		{
			windowHeight = document.documentElement.clientHeight;
		}
		else
		{
			windowHeight = document.body.clientHeight;
		}
		if (document.documentElement.clientWidth > 0)
		{
			windowWidth = document.documentElement.clientWidth;
		}
		else
		{
			windowWidth = document.body.clientWidth;
		}
	}
	ql_dialog.style.display = "block";
	ql_dialog.style.left = ((windowWidth - dw) / 2) + "px";
	ql_dialog.style.top = ((windowHeight - dh) / 2) + "px";

	var title = document.getElementsByTagName('title')[0];
	var input = document.getElementById("ql_name");
	if (typeof TGN.KeyWatcher != 'undefined')
	{
		TGN.KeyWatcher.addListener(input);
	}
	input.value = title.innerHTML;
};

_HNav.QuickLink.addQuickLink = function()
{
	_YDom.get("ql_content").style.display = "none";
	_YDom.get("ql_buttons").style.display = "none";
	_YDom.get("ql_result").style.display = "block";
	_YDom.get("ql_image").style.display = "none";

	var url = document.location;
	var name = document.getElementById("ql_name");
	if (url != "" && name.value != "")
	{
		/*	Important!  linkUrl needs to be the last parameter on the query string.  We assume that any
		query string parameters after linkUrl are part of linkUrl.  This allows us to save a url such as
		http://trees.ancestry.ca/pt/editperson.aspx?tid=373&pid=-2147316033&pg=32768 */
		var args = '&linkName=' + name.value + '&partnerId=' + _HNavPartnerId + '&subType=' + _HNavSubType + '&linkUrl=' + encodeURIComponent(url.href);
		_HNav.callLoader('AddQuickLink', args, _HNav.QuickLink.addQuickLink_Success, null);
	}
};

_HNav.QuickLink.addQuickLink_Success = function()
{
	if (_HNavAddQlErrorMessage == null)
	{
		if (_HNavAddQlSuccess == 'true')
		{
			_YDom.get("ql_AddSuccess").style.display = "block";
			_YDom.get("ql_AddFail").style.display = "none";
		}
		else
		{
			_YDom.get("ql_AddSuccess").style.display = "none";
			_YDom.get("ql_AddFail").style.display = "block";
		}
	}
	else
	{
		_YDom.get("ql_AddSuccess").style.display = "none";
		_YDom.get("ql_AddFail").style.display = "block";
	}
};

_HNav.QuickLink.closeDialog = function()
{
	var bodyTag = document.getElementsByTagName('body')[0];
	bodyTag.removeChild(document.getElementById("ql_dropSheet"));
	bodyTag.removeChild(document.getElementById("ql_dialog"));
};

/*=================================== END QuickLinks Section ============================================= */


/*=================================== Todos Section ============================================= */

_HDrwr.Todo = function(id, todoName, completed)
{
	this.todoName = todoName;
	this.id = id;

	this.draw = function()
	{
		var html;
		var template = _YDom.get('todo_template');
		html = template.innerHTML;
		html = html.replace("__todoItemId__", this.id);
		html = html.replace("__todoItemTxt__", unescape(this.todoName));
		html = html.replace("__todoItemId2__", this.id);
		html = html.replace("__todoItemTxt2__", this.todoName);
		html = html.replace("__todoChBId__", "chB_" + this.id);
		html = html.replace("__todoId__", this.id);

		if (completed)
		{
			html = html.replace("__checked__", "checked=\"checked\"");
			html = html.replace("__callbackFn__", "_HDrwr.retaskTodo");
		}
		else
		{
			html = html.replace("__checked__", "");
			html = html.replace("__callbackFn__", "_HDrwr.completeTodo");
		}

		return html;
	};
};
_HDrwr.getTodos = function()
{
	/* if the request takes longer than a half second, turn on the spinner */
	setTimeout(function() { if (!_todoDrwr.isOpen) { _todoDrwr.spinner.style.display = 'block'; } }, 500);
	var args = '&lameChrome=' + _lameChomeCounter++;
	_HNav.callLoader('GetTodos', args, _HDrwr.getTodos_Success, null);
};

_HDrwr.getTodos_Success = function()
{
	if (_HNavTodoSuccess)
	{
		_YDom.get("drwrError").style.display = 'none';
		_HDrwr.drawTodos(_HNavTodoSuccess, _HNavTodos, _HNavTodoCount, _HNavCompletedTodos, _HNavCompletedCount);
	}
	else
	{
		_YDom.get("drwrError").style.display = 'block';
	}
	_HDrwr.closeDrwrTop(_favsDrwr);
	//_HDrwr.animOpenDrawer(_todoDrwr);     
	_HDrwr.changeTodoView();
	_todoDrwr.spinner.style.display = 'none';

};

_HDrwr.drawTodos = function(success, todos, todoCount, completedTodos, completedCount)
{
	if (success)
	{
		_YDom.get('todoPhaseout').style.display = 'block';
		var todoContainer = _YDom.get("hdrTodos");
		todoContainer.innerHTML = "";
		if (_YDom.get('todoViewDD').value == 'todos')
		{
			if (todoCount == 0)
			{
				/*show no todos */
				_YDom.get('noTodos').style.display = 'block';
			}
			else
			{
				_YDom.get('noTodos').style.display = 'none';
			}
		}
		else
		{
			if (completedCount == 0)
			{
				/* show no completed todos */
				_YDom.get('noCompletedItems').style.display = 'block';
			}
			else
			{
				try { _YDom.get('noCompletedItems').style.display = 'none'; } catch (exc) { }
			}
		}

		var jtd;
		var todo;
		for (var i = 0; i < todos.length; i++)
		{
			jtd = todos[i];
			todo = new _HDrwr.Todo(jtd.id, jtd.todoName, false);
			todoContainer.innerHTML += todo.draw();
		}
		/* init drag drop */
		_HDrwr.DragDrop.initHdrTodo();

		/* populate completed items */
		var complContainer = _YDom.get("hdrCompletedItems");
		complContainer.innerHTML = "";


		var jctd;
		var complTodo;
		for (var j = 0; j < completedCount; j++)
		{
			jctd = completedTodos[j];
			complTodo = new _HDrwr.Todo(jctd.id, jctd.todoName, true);
			complContainer.innerHTML += complTodo.draw();
		}

		_YDom.get('todoCount').innerHTML = "(" + todoCount + ")";

		_YDom.get('completedCount').innerHTML = "(" + completedCount + ")";


		/* show the current view */
		if (_showTodosView)
		{
			todoContainer.style.display = 'block';
			complContainer.style.display = 'none';
		}
		else
		{
			todoContainer.style.display = 'none';
			complContainer.style.display = 'block';
		}

	}
	/*todo: display error
	else
	{
        
	}*/
};

_HDrwr.saveNewTodo = function()
{
	var subj = _YDom.get('addTodoTxt');
	if (subj.value != "")
	{
		var subjEnc = encodeURIComponent(subj.value);
		var args = '&subject=' + subjEnc + '&partnerId=' + _HNavPartnerId + '&subType=' + _HNavSubType;
		_HNav.callLoader('AddTodo', args, _HDrwr.getTodos, null);
		_YDom.get('addTodoTxt').value = "";
	}
	_HDrwr.toggleAddTodo();
	_HDrwr.getTodos();
};


_HDrwr.completeTodo = function(id)
{
	var args = '&todoId=' + id;
	_HNav.callLoader('CompleteTodo', args, _HDrwr.getTodos, null);
};

_HDrwr.completeTodo_success = function()
{
	//    var json = null;
	//    try{
	//        json = eval("(" + o.responseText + ")");
	//    }
	//    catch(e){
	//        /* display error */
	//    }
	_HDrwr.getTodos();
};

_HDrwr.deleteTodo = function(id, todoName)
{
	var confMsg = _HNavDeleteItemConf;

	if (confirm(confMsg + "\n\n" + unescape(todoName)))
	{
		var args = '&todoId=' + id;
		_HNav.callLoader('DeleteTodo', args, _HDrwr.deleteTodo_Success, null);
	}
};

_HDrwr.deleteTodo_Success = function()
{
	_HDrwr.getTodos();
};

_HDrwr.retaskTodo = function(id)
{
	var args = '&todoId=' + id;
	_HNav.callLoader('ReTaskTodo', args, _HDrwr.retaskTodo_success, null);
};

_HDrwr.retaskTodo_success = function()
{
	_HDrwr.getTodos();
};

_HDrwr.toggleAddTodo = function()
{
	var addTodo = _YDom.get('addTodoBox');
	if (addTodo.style.display == 'none')
	{
		addTodo.style.display = 'block';
		_YDom.get('addTodoTxt').focus();
	}
	else { addTodo.style.display = 'none'; }
	return false;
};

_HDrwr.changeTodoView = function()
{
	if (_YDom.get('todoViewDD').value == 'todos')
	{
		/* switch to normal view */
		_YDom.get('hdrTodos').style.display = 'block';
		_YDom.get('hdrCompletedItems').style.display = 'none';
		_YDom.get('addTodoBtn').style.display = 'block';
		_YDom.get('todoCount').style.display = 'inline';
		_YDom.get('completedCount').style.display = 'none';
		_YDom.get('noCompletedItems').style.display = 'none';
		_YDom.get('todoInstruction').style.display = 'block';
		if (_HNavTodoCount == 0)
		{
			_YDom.get('noTodos').style.display = 'block';
		}
		_HDrwr.animOpenDrawer(_todoDrwr);
	}
	else
	{
		/* switch to completed items */
		_YDom.get('hdrCompletedItems').style.display = 'block';
		_YDom.get('hdrTodos').style.display = 'none';
		_YDom.get('addTodoBtn').style.display = 'none';
		_YDom.get('todoCount').style.display = 'none';
		_YDom.get('completedCount').style.display = 'inline';
		_YDom.get('noTodos').style.display = 'none';
		_YDom.get('todoInstruction').style.display = 'none';
		if (_HNavCompletedCount == 0)
		{
			_YDom.get('noCompletedItems').style.display = 'block';
		}
		var height = _HNav.getElementHeight('DrwWrapper') + 11;
		_YDom.get('DrwSlider').style.height = height + "px";
		setTimeout(function() { _todoDrwr.spinner.style.display = 'none'; }, 500);
	}
};


/*=================================== END Todos Section ================================================== */

_HDrwr.initDrawers = function()
{
	_todoDrwr = new _HDrwr.Drawer('todosLi', 'todosLeft', 'todosRight', 'todosContainer', 'todosLink', 'todosEm', 'todosSpan', 'todoTop', 'hdrTodos', 'noTodos', 'hdrCompletedItems', 'noCompletedItems', 'todoSpinner', _HDrwr.getTodos);
	_YEvent.on('todosLi', 'click', _HDrwr.openDrawerTab, _todoDrwr);

	_favsDrwr = new _HDrwr.Drawer('favsLi', 'favsLeft', 'favsRight', 'favsContainer', 'favsLink', 'favsEm', 'favsSpan', 'favTop', 'hdrFavs', 'noQuickLinks', null, null, 'favsSpinner', _HDrwr.getQuickLinks);
	_YEvent.on('favsLi', 'click', _HDrwr.openDrawerTab, _favsDrwr);

	_YEvent.on('todoCloser', 'click', _HDrwr.animClose);
	_YEvent.on('favCloser', 'click', _HDrwr.animClose);

	_drawerObjs = [_todoDrwr, _favsDrwr];
};

_YEvent.onDOMReady(_HDrwr.initDrawers);


/*==================================== Legacy header code ======================================================= */

/* This will now create a "return to" styled button if addToHeader=false.  */
function g_createTab(name, address, addToHeader, className)
{
	var navBar = _YDom.get("_HdrNav");
	if (navBar != null && addToHeader == true)
	{
		navBar.innerHTML += "<li class=\"G-NavItem\"><a href=\"" + address + "\">" + name + "</a></li>";
	}
	else if (!addToHeader)
	{
		var btn = document.getElementById("_HdrReturnBtn");
		btn.style.display = 'block';
		btn.innerHTML = "<div style=\"position:absolute; left:0; top:0; height:30px; width:auto;\"><a href=\"" + address + "\" class=\"gbtn med-green\"><em><span class=\"flat_icon arrow1left_white\"></span>" + name + "</em></a></div><br>";
	}
}

if(typeof(document.getElementsByClassName)=='undefined'){document.getElementsByClassName=function(a,b,c){var d=[],e=[],f=0,g=0,h=[];if(typeof(c)=='undefined'){c=document;}else if(typeof(c)==typeof('string')){c=document.getElementById(c);}if(typeof(b)=='undefined'){b='*';}e=c.getElementsByTagName(b);for(f=0;f<e.length;f++){if(e[f].className){h=e[f].className.split(' ');for(g=0;g<h.length;g++){if(h[g]==a){d.push(e[f]);break;}}}}return(d);};}
function hasClassName(a,b){return a.className.match(new RegExp('(\\s|^)'+b+'(\\s|$)'));}
function addClassName(a,b){if(!hasClassName(a,b)){a.className+=" "+b;return true;}return false;}
function removeClassName(a,b){if(hasClassName(a,b)){var c=new RegExp('(\\s|^)'+b+'(\\s|$)');a.className=a.className.replace(c,' ');return true;}return false;}

function setButtonStates(buttons)
{
	if (buttons && buttons.length > 0)
	{
		for (var a = 0; a < buttons.length; a++)
		{
			buttons[a].onmouseover = function() { if(hasClassName(this,'disabled')){return false;} this.style.backgroundPosition = 'left center'; for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right center'; break; } } return true; };
			buttons[a].onmousedown = function() { if(hasClassName(this,'disabled')){return false;} this.style.backgroundPosition = 'left bottom'; for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right bottom'; break; } } return true; };
			buttons[a].onmouseup = function() { if(hasClassName(this,'disabled')){return false;} this.style.backgroundPosition = 'left center'; for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right center'; break; } } return true; };
			buttons[a].onmouseout = function() { if(hasClassName(this,'disabled')){return false;} this.style.backgroundPosition = 'left top'; for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right top'; break; } } return true; };
		}
	}
}
function setTranBtnStates(buttons)
{
	if (buttons && buttons.length > 0)
	{
		for (var a = 0; a < buttons.length; a++)
		{
			buttons[a].onmouseover = function() { if(hasClassName(this,'disabled')){return false;} for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'span') { this.childNodes[i].style.backgroundPosition = 'left center'; } if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right center'; } } return true; };
			buttons[a].onmousedown = function() { if(hasClassName(this,'disabled')){return false;} for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'span') { this.childNodes[i].style.backgroundPosition = 'left bottom'; } if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right bottom'; } } return true; };
			buttons[a].onmouseup = function() { if(hasClassName(this,'disabled')){return false;} for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'span') { this.childNodes[i].style.backgroundPosition = 'left center'; } if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right center'; } } return true; };
			buttons[a].onmouseout = function() { if(hasClassName(this,'disabled')){return false;} for (var i = 0; i < this.childNodes.length; i++) { if (this.childNodes[i].nodeName.toLowerCase() == 'span') { this.childNodes[i].style.backgroundPosition = 'left top'; } if (this.childNodes[i].nodeName.toLowerCase() == 'em') { this.childNodes[i].style.backgroundPosition = 'right top'; } } return true; };
		}
	}
}
_YEvent.onDOMReady(function()
{
	var gbtns = document.getElementsByClassName('gbtn', 'a');
	setButtonStates(gbtns);
	gbtns = document.getElementsByClassName('gbtn_in', 'a');
	setButtonStates(gbtns);
	gbtns = document.getElementsByClassName('gtbtn', 'a');
	setTranBtnStates(gbtns);
});


/*==================================== Communication Banner ======================================================= */

function GetCommunicationBanner()
{
	if (typeof clientId != "undefined")
	{
		var stackName = document.getElementById(clientId + '_hdnStackName').value;
		if (_HNavUserLoggedIn == 'True' && bannerOff.toLowerCase() == "true" &&
		    (stackName == 'c_lihp' || stackName == 'c_home' || stackName == 'c_trees' || stackName == 'c_boards' || stackName == 'c_search' || stackName == 'c_learn'))
		{

			var bannerArgs = '&stack=' + stackName;
			_HNav.callLoader('CommunicationBanner', bannerArgs, GetBannerSucc, null);
			
		}
	}
}

function GetBannerSucc()
{

	var stackName = document.getElementById(clientId + '_hdnStackName').value;
	var bannerDiv, wrpDiv;
	var wrpHtml = new String();
	try
	{
			if (!_HNavBannertext == "")
			{
				bannerDiv = document.getElementById(clientId + '_banComMessage');
				bannerDiv.innerHTML = '';
				var className, addClassName;
				var addHtml = new String();
				wrpDiv = document.createElement("div");
				wrpDiv.id = "wrpDiv";
				wrpHtml = _HNavBannertext;
				if (_HNavBannertext.indexOf('<LANG') > -1)
				{
					wrpHtml = CanadaLanguageBanner(_HNavBannertext);

				}
				if (!wrpHtml == "")
				{
					wrpHtml = wrpHtml.concat("<a class=\"close\" href=\"javascript:void(0)\" onclick=\"CloseBannerClick();\">" + closeStr + "</a>");
					switch (_HNavColor)
					{
						case "green": className = "grnWrp"; addClassName = "grnBtm"; break;
						case "yellow": className = "yloWrp"; addClassName = "yloBtm"; break;
						case "red": className = "redWrp"; addClassName = "redBtm"; break;
						case "orange": className = "orangeWrp"; addClassName = "orangeBtm"; break;
						case "blue": className = "bluWrp"; addClassName = "bluBtm"; break;
						default: className = "bluWrp"; addClassName = "bluBtm"; break;
					}
					wrpDiv.className = className + ' gHeaderAlert';
					wrpHtml = wrpHtml.concat("<div class=\"tl\"></div><div class=\"tr\"></div>");
					document.getElementById(clientId + '_hdnbannerID').value = _HNavBannerid;

					if (_HNavAddtext == "")
					{
						wrpHtml = wrpHtml.concat("<div class=\"bl\"></div><div class=\"br\"></div>");
						wrpDiv.innerHTML = wrpHtml;
						bannerDiv.appendChild(wrpDiv);
					}
					else
					{
						wrpDiv.innerHTML = wrpHtml;
						bannerDiv.appendChild(wrpDiv);
						var addDiv = document.createElement("div");
						addHtml = _HNavAddtext;
						addDiv.ID = "addDiv";
						addDiv.className = addClassName;
						addHtml = addHtml.concat("<div class=\"bl\"></div><div class=\"br\"></div>");
						addDiv.innerHTML = addHtml;
						bannerDiv.appendChild(addDiv);
					}
					document.getElementById(clientId + '_bannerCommunication').style.display = 'block';
					document.getElementById('fbanner').style.display = 'block';
				}
			}
			else
			{
				document.getElementById(clientId + '_bannerCommunication').style.display = 'none';
				document.getElementById('fbanner').style.display = 'none';
			}
	}
	catch (err)
	{
		document.getElementById(clientId + '_bannerCommunication').style.display = 'none';
		document.getElementById('fbanner').style.display = 'none';
	}
}

_YEvent.onDOMReady(function() { GetBannerSucc(); });


function CloseBannerClick()
{
	var bannerId = document.getElementById(clientId + '_hdnbannerID').value;
	var stackName = document.getElementById(clientId + '_hdnStackName').value;
	document.getElementById(clientId + '_bannerCommunication').style.display = 'none';
	document.getElementById('fbanner').style.display = 'none';
	var args = '&bannerId=' + bannerId + '&stack=' + stackName;
	_HNav.callLoader('CommunicationBanner', args, GetBannerSucc, null);
}


function CanadaLanguageBanner(bannerText)
{
	var iStart = -1;
	var iEnd = -1;
	var sLangText = '';

	try
	{
		var culture = cultureName;  //EXAMPLE: en-CA
		iStart = bannerText.indexOf('<LANG' + culture + '>');
		iEnd = bannerText.indexOf('</LANG' + culture + '>');
		if (iStart > -1 && iEnd > -1 && iEnd > iStart)
		{
			sLangText = bannerText.substring(iStart + 11, iEnd);
		}
	}
	catch (ex) { }
	return sLangText;
}


/*==================================== Username Change Tracking ======================================================= */

/*_YEvent.onDOMReady(function()
{
	var sCHANGECOOKIE = 'atthandler';
	var keyUCDM = 'ucdm';
	var keyURL = 'url';
	var keyTIMESTAMP = 'timestamp';
	var keyLILOSTATUS = 'lilostatus';

	var iMAXMINUTES = 10;
	var iMAXMILLISECONDS = 1000 * 60 * iMAXMINUTES;

	var dtNow = (new Date()).getTime();
	var dtExpires = new Date(dtNow + iMAXMILLISECONDS);
	var sExpires = dtExpires.toGMTString();
	var sCurrentLocation = document.location.href;
	sCurrentLocation = sCurrentLocation.replace(/&/g, 'AMP'); // replace '&' with 'AMP' so we can get all of the query string parameters with the keyURL key

	var aUlink = getVars2Cookie('ULINK');
	// If the user is logged out, they won't have the ulink cookie
	if (aUlink == null)
		return;
	aUlink = aUlink.split(',');
	sUcdm = aUlink[aUlink.length - 1];

	var sDomain = getRootDomain();

	// Find the atthandler cookie
	if (getCookie(sCHANGECOOKIE) != null)
	{
		// We found the cookie, so compare the values

		// If the cookie was recorded in our timeframe
		if (dtNow < parseInt(getDictionaryCookie(sCHANGECOOKIE, keyTIMESTAMP)) + iMAXMILLISECONDS)
		{
			// If their last logged-in-logged-out state was not logged-out
			if (getDictionaryCookie(sCHANGECOOKIE, keyLILOSTATUS) == 'True')
			{
				// If the UCDMs are different
				var sOldUcdm = getDictionaryCookie(sCHANGECOOKIE, keyUCDM);
				if (sOldUcdm != null && sOldUcdm != '' && sOldUcdm != sUcdm)
				{
					try
					{
						// Throw exception (call handler on shared stack)
						var args = '&olducdm=' + sOldUcdm + '&newucdm=' + sUcdm +
										'&oldpageurl=' + getDictionaryCookie(sCHANGECOOKIE, keyURL) + '&newpageurl=' + sCurrentLocation +
										'&other=' + getDictionaryCookie(sCHANGECOOKIE, keyTIMESTAMP) + ',' + dtNow;
						_HNav.callLoader('AttHandler', args, null, null);
					}
					catch (e)
					{
						// We're throwing a sytax error for some reason.  Something about callLoader
						// doesn't like the args.  (Is it because we're passing urls on the query string?)
					}
				}
			}
		}
	}

	// Save information for the current page
	setDictionaryCookieEx(sCHANGECOOKIE, keyUCDM, sUcdm, '/', sExpires, sDomain);
	setDictionaryCookieEx(sCHANGECOOKIE, keyURL, sCurrentLocation, '/', sExpires, sDomain);
	setDictionaryCookieEx(sCHANGECOOKIE, keyTIMESTAMP, dtNow, '/', sExpires, sDomain);
	setDictionaryCookieEx(sCHANGECOOKIE, keyLILOSTATUS, _HNavUserLoggedIn, '/', sExpires, sDomain);
});*/


/*==================================== Style Sheets ======================================================= */

