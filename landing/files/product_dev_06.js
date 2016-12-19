var setDomains = (function(){
	storeOrigin = (typeof storeOrigin === "undefined" || storeOrigin.indexOf("#") > -1)? "http://store.ancestry.com" : storeOrigin;
	ancOrigin = (typeof ancOrigin === "undefined" || ancOrigin.indexOf("#") > -1)? "http://www.ancestry.com" : ancOrigin;
}());
// FUNCTION PARSES A PRODUCT LIST OBJECT AND INJECTS PRICE INFORMATION INTO DOM
if(setPrices === undefined){var setPrices = {};};
setPrices.useProductList = (function() {
	var productList = setPrices.getProductList;
	$(function() {
		// SIMPLE VALIDATION FOR productList OBJECT
		if(typeof productList !== "undefined" && productList.hasOwnProperty("length")) {
			if(productList.length >= 1) {
		
			var pricingElements = $(".pricing");
			// IF THERE ARE MORE th.pricing ELEMENTS THAN productList ARRAY ELEMENTS
			if(pricingElements.length > productList.length) {
				// REMOVE PRICING FOR ELEMENTS WITHOUT productList COUNTERPART
				pricingElements.filter(function() {return ($(this).index(".pricing") > productList.length - 1);}).remove();
			}
			
			var currencyCode = (function () {
				var cc = productList[0].currencyCode || "USD";
				switch(cc)
				{	
					case"USD": return "$";
					case"EUR": return "&euro;";
					case"GBP": return "&pound;";
					default: return "$";
				}
			}());
			
			pricingElements.each(function(pricingIndex){
				$(this).children(":lt(2)").each(function(priceTypeIndex) { // CHILDREN SHOULD GET FIRST 2 ELEMENTS WITH CLASSES: 'regularPrice','discountedPrice'
					var priceType = $(this).attr("class").split(" ")[0]; // CACHE THE STRING FOR USE INSIDE INNER LOOP
					$(this).children().each(function(pricePartsIndex) {// CHILDREN SHOULD GET ELEMENTS WITH CLASSES: 'currency','whole','dec'
						// TEST FOR UNDEFINED productList PROPERTY (EITHER: 'regularPrice', or 'discountedPrice')
						var price = productList[pricingIndex][priceType];
						if(price === undefined) {
							// REMOVE MISSING PRICETYPE FROM HTML, STYLE REMAINING PRICETYPE
							pricingElements.filter(function() {return ($(this).index(".pricing") === pricingIndex);})
								.find("."+priceType).remove().end().children().attr("class","redPrice");
						}
						else {
							switch($(this).attr("class").split(" ")[0])
							{
								case"currency": $(this).html(currencyCode);break;
								case"whole": $(this).text(parseInt(price) + ".");break;
								case"dec":
									var decStr = price.toFixed(2).toString();
									if(decStr.indexOf(".") === -1) {$(this).text("00");}// IF DECIMAL POINT IS NOT FOUND USE 00 FOR CENTS
									else{$(this).text(decStr.split(".")[1]);}
									break;
								default:break;
							}
						}
					});
				}).end().siblings(".buyNow.ancBtn").attr("href",function() { // TRAVERSE TO BUY NOW BUTTON, SET THE href ATTRIBUTE
					return storeOrigin + "/Index.aspx?p=" + productList[pricingIndex].offerId + "&action=addproduct&ReturnURL="+location.href;
				}).click(function(event){ // FIRE OMNITURE EVENT ON BUY NOW BUTTON CLICK
					if(typeof s_account === "undefined"){s_account = "myfamilyancestry";}
					var pageName = location.pathname.substring(location.pathname.lastIndexOf("/") + 1);
					var label = pageName + " " + document.title + " Buy Now " + productList[pricingIndex].offerId;
					var s = s_gi(s_account); 
					s.tl(this,'o',label);
				});
			});
			}
		}
	});
}());
	//	MODAL 
var Modal = (function(){	
	function Modal (options) {
		var that = {};
		var onStage = false;
		var defaults = {
			type:"image",
			alwaysShowNav:false,
			preventDefault:false,
			animate:250
		}
				
		var openModal = function(target) {
			if(onStage){return;}else {onStage = true;};
			if(typeof arguments[1] === "object" && spec.preventDefault){arguments[1].preventDefault();};
			$("<div/>",{"class":"overlayBG",text:"Loading..."}).css({"opacity":0}).appendTo("body").animate({"opacity":".5"},spec.animate);
			switch(spec.type)
			{
				case"image":
					$("<img/>",{"src":target,"class":"modalImg"}).one("load",function(){
						$(this).wrap("<div id='overlayContent'></div>").parent().appendTo("body").css({"opacity":0}).animate({"opacity":"1"},(spec.animate * 2));
						setPos();
						drawCloseButton();							
					}).each(function(){if(this.complete) {$(this).trigger("load");}}); // AFTER BROWSER CACHES IMAGE, MANUALLY TRIGGER LOAD EVENT
					break;
				case"html":
					var htmlContent = $(target).clone().css("display","block");
					$("<div/>",{"id":"overlayContent"}).append(htmlContent).appendTo("body");
					setPos();
					drawCloseButton();
					break;
			}			
			// 	TODO: SET HOVER FUNCTIONALITY FOR LEFT AND RIGHT HALF OF OVERLAYCONTENT, TO OPEN NAV BUTTONS
			$(".overlayBG,.closeButton").live("click",function(){
				if(!onStage){return;}else {
					$(".overlayBG,#overlayContent").remove();
					onStage = false;
				};
			});
		}
		
		var drawCloseButton = function() {
			$("#overlayContent").append("<div class='closeButton'>close</div>");
		}
		
		// CENTER THE MODAL CONTENT ON THE SCREEN
		var setPos = function(){
			$("#overlayContent").css({"margin-left":function(){
				return "-" + $("#overlayContent").outerWidth()/2 + "px";
			},"margin-top":function(){
				return "-" + $("#overlayContent").outerHeight()/2 + "px";							
			},"left":"50%","top":"50%"});
		}
		
		try {
		if(options != undefined && typeof options === "object") {
			var spec = $.extend({},defaults,options);
		}
		else {spec = defaults;}		
		var init = (function () {
			// SIMPLE VALIDATION
			if(typeof spec.selector === "undefined" || typeof spec.selector !== "string"){throw {name:"init Selector Error",message:"Selector must be defined"};}
			if(typeof spec.targets === "undefined" || spec.targets.length === undefined){throw {name:"init Targets Error",message:"Targets must be defined, and have length."};}
			
			$(spec.selector).each(function(index){
				var target = ($.isArray(spec.targets) && typeof spec.targets[index] === "string") ? spec.targets[index] : spec.targets;
				$(this).click(function(event){openModal(target,event);});
			});	
		}());
		}
		catch(e) {
			that.error = e;
			return that;
		}
		
		// PUBLIC INTERFACE METHODS
		that.openModal = openModal;
		
		return that;			
	}
	return Modal;	
}());

