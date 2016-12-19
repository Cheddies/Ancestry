/*************************************************
Javascript document for ancestryshop.co.uk

uses the jQuery library (jquery.com) version 1.3.2
jQuery validation plugin (http://docs.jquery.com/Plugins/Validation)
jQuery tooltip plugin (http://docs.jquery.com/Plugins/tooltip)

Production version

Created by Paul Smith of Edgeward (Edgeward.co.uk)
Last updated Nov 09

*************************************************/

$(document).ready(function(){
						   
	/**** Form Validation ****/	
	
	//custon validation method for phone number, allows for numbers and spaces only
	jQuery.validator.addMethod("phone", function(value, element) { 
	  return this.optional(element) || !/[^\s*^\d*]/.test(value); 
	}, "Enter a valid phone number.");
	//custon validation method for alphabetical, disallows digits
	jQuery.validator.addMethod("alpha", function(value, element) { 
	  return this.optional(element) || !/[^\D*]/.test(value); 
	}, "Alphabetical characters only.");
	//postcode
	jQuery.validator.addMethod("postcode", function(value, element) { 
	  return this.optional(element) || /^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/.test(value); 
	}, "Invalid UK postcode.");	
	var validYear = "Enter a valid year";
	var validMonth = "Enter a valid month (01-12)";
	var validQuarter = "Enter 1 to 4";
	var d = new Date();
	var year = d.getFullYear();
	/* Basic validation rules */
	$("form.shop_form").validate({
		//debug: true,
		rules: {
			title: {required: true},
			forename: {required: true, alpha: true, maxlength: 15},
			surname: {required: true, alpha: true, maxlength: 20},
			house_no: {required: true, maxlength: 20},
			street_name: {required: true, maxlength: 40, alpha: true},
			address_1: {required: true, maxlength: 40},
			address_2: {maxlength: 40},
			town: {required: true, alpha: true, maxlength: 20},
			county: {required: true, alpha: true},
			country: {required: true},
			postcode: {required: true, maxlength: 10, postcode:true},
			tel: {required: true, phone: true, maxlength: 14},
			email: {required: true,	email: true, maxlength: 50	},
			b_title: {required: {depends: function(element){return $("#billing_different:checked").length}}},
			b_forename: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true, maxlength: 15},
			b_surname: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true, maxlength: 20},
			b_house_no: {required: {depends: function(element){return $("#billing_different:checked").length}}, maxlength: 20},
			b_street_name: {required: {depends: function(element){return $("#billing_different:checked").length}}, maxlength: 40, alpha: true},
			b_address_1: {required: {depends: function(element){return $("#billing_different:checked").length}}, maxlength: 40},
			b_address_2: {maxlength: 40},
			b_town: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true, maxlength: 20},
			b_county: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_postcode: {required: {depends: function(element){return $("#billing_different:checked").length}}, maxlength: 10, postcode:true},
			terms: {required: true}
		},
		messages: {
			forename: { maxlength: jQuery.format("Maximum {0} characters.") },
			surname: { maxlength: jQuery.format("Maximum {0} characters.") },
			address_1: { maxlength: jQuery.format("Maximum {0} characters.") },
			address_2: { maxlength: jQuery.format("Maximum {0} characters.") },
			town: { maxlength: jQuery.format("Maximum {0} characters.") },
			postcode: { maxlength: jQuery.format("Maximum {0} characters.") },
			forename: { maxlength: jQuery.format("Maximum {0} characters.") },
			email: { email: "Invalid email address.", maxlength: jQuery.format("Maximum {0} characters.")	},
			tel: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_forename: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_surname: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_address_1: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_address_2: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_town: { maxlength: jQuery.format("Maximum {0} characters.") },
			b_postcode: { maxlength: jQuery.format("Maximum {0} characters.") },
			terms: {required: "You must accept the Terms &amp; Conditions in order to place your order."}
		},
		success: function(label) {
			if(!label.prev('input').hasClass('noval')) label.addClass("valid").text("OK.");
		},
		highlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')) $(element).parents("div.input").removeClass('valid').addClass(errorClass);
		},
		unhighlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')) $(element).parents("div.input").removeClass(errorClass).addClass('valid');
		}

	});
	
	//add terms to summary
	$('label[for=terms]').append("<br>Tick the box if you agree to be bound by our Terms &amp; Conditions.")
	.after("<input name=\"terms\" type=\"checkbox\" id=\"terms\" />");
	
	//validation for final T&C parts
	$("form#tc_form").validate({
		//debug: true,
		rules: {
			terms: {required: true}
		},
		messages: {
			terms: {required: "You must accept the Terms &amp; Conditions in order to place your order."}
		},
		success: function(label) {
			if(!label.prev('input').hasClass('noval')) label.addClass("valid").text("OK.");
		},
		highlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')) $(element).parents("div.input").removeClass('valid').addClass(errorClass);
		},
		unhighlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')) $(element).parents("div.input").removeClass(errorClass).addClass('valid');
		}
	});
	
	/* Validation Alerts */
	$("input, select").blur(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
			if(val) validField($(this));
		}
	}).keyup(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
			if(val) validField($(this));
		}
	}).change(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
			if(val) validField($(this));
		}
	});
	function checkValidField(fieldObj){
		val = fieldObj.valid();		
	}
	function validField(fieldObj){
	}

	
	/**** Accordion Functionality ****/
	
	$('div.accordion span.legend').each(function(){
		var thisHeight = $(this).parents("div.accordion").height();
		$(this).parents("div.accordion").attr({'rev':thisHeight});
		if(!$(this).hasClass('acc_open')) accClose($(this),false);
		//add text
		$(this).text($(this).text()+" [Click to review]");
	});
	$('div.accordion span.legend').hover(function () {
		$('body').css({"cursor":"pointer"}); 
		$(this).css({"background-position":"0 -26px"}); 
	},function () {
		$('body').css('cursor', 'default');
		$(this).css({"background-position":"0 0"}); 
	});
	
	//open or clise accordion on click
	$('div.accordion span.legend').click(function(){
		if(!$(this).hasClass('acc_closed')){
			accClose($(this),true);
		} else {
			accOpen($(this),true);
		}
	});	
	
	function accClose(accObj,bolAnimate){
		var thisHeight = accObj.parents("div.accordion").attr('rev');
		if(bolAnimate){
			accObj.parents("div.accordion").animate( 
				{height: "25px"}
			, 800,null,function(){$('#tooltip_box').hide();});
		} else {
			accObj.parents("div.accordion").height(25);
			$('#tooltip_box').hide();
		}
		accObj.removeClass('acc_open');
		accObj.addClass('acc_closed');			
	}
	function accOpen(accObj,bolAnimate){
		var thisHeight = accObj.parents("div.accordion").attr('rev');
		if(bolAnimate){
			accObj.parents("div.accordion").animate( 
				{height: thisHeight+"px"}
			, 800,null,function(){$('#tooltip_box').hide();});
		} else {
			accObj.parents("div.accordion").height(thisHeight);
			$('#tooltip_box').hide();
		}
		accObj.removeClass('acc_closed');	
		accObj.addClass('acc_open');
	}	
	/**** Accordion Tooltip ****/
	
	$('div.accordion span.legend').tooltip({
		bodyHandler: function() {return 'Click to open/close';},
	   showURL: false,
	   track: true	
	});

	
	/**** Activate billing address 
	
	$("#billing_different").click(function(){										   
		if($('#acc_billing_add').hasClass('acc_closed') && $("#billing_different:checked").length){
			accOpen($('#acc_billing_add'),true);
		} else if(!$('#acc_billing_add').hasClass('acc_closed') && !$("#billing_different:checked").length){
			accClose($('#acc_billing_add'),true);	
		}
	}).change(function(){										   
		if($('#acc_billing_add').hasClass('acc_closed') && $("#billing_different:checked").length){
			accOpen($('#acc_billing_add'),true);
		} else if(!$('#acc_billing_add').hasClass('acc_closed') && !$("#billing_different:checked").length){
			accClose($('#acc_billing_add'),true);	
		}
	});
	
 Activate billing address ****/
	
	if(!$("[id$=billing_different]:checked").length) $('#bil_add_fields').hide().addClass('hidden');
	
	$("#billing_different").click(function(){										   
		
		
			if($("[id$=billing_different]:checked").length && $('#bil_add_fields').children('h2').hasClass('section_header')){
				$('#bil_add_fields').children('h2.section_header').addClass('section_acc').children('a').before(' (click to open)');
			} 
			//hide/show
			if($('#bil_add_fields').hasClass('hidden') && $("[id$=billing_different]:checked").length){
				$('#bil_add_fields').removeClass('hidden');
				$('#bil_add_fields').show();
			} else {
				$('#bil_add_fields').addClass('hidden');
				$('#bil_add_fields').hide();
			}
	});	
	
	/**** Delivery Total ****/
	updateDelTotal(); // on page load
	$('input[name$=no_of_certs]').change(function(){
		updateDelTotal();
	}).keyup(function(){
		updateDelTotal();
	});
	$('input[name$=delivery_method]').click(function(){
		updateDelTotal();
	});
	function updateDelTotal(){
		//make the total input visible
		$('#del_total_wrapper').show();
		//number of certs 
		var q = $('input[name$=no_of_certs]').val()*1;	
		q = q-1;
		//cost of delivery
		var del = $('input[name$=delivery_method]:checked').attr('rev')*1;
		//the type of service 1/2
		var srv = $('input[name$=delivery_method]:checked').val();
		if(isNaN(del)) del = $('input #del_1').val();
		var delMulti = srv === '1' ? 10 : 23.5;
		//alert(del+'|'+srv+'|'+delMulti);
		var dtotal = (q*delMulti)+(del*1);
		if(isNaN(dtotal)){
			$("#del_total").text('');
		} else {
			$("#del_total").text('\xA3'+dtotal.toFixed(2));	
		}
	}
	
	/**** Help Text Popups ****/
	
$('a.help').removeAttr('title').bind({
		mouseover: function() {
			showTooltip($(this));
		},
		mouseout: function() {
			$('#tooltip_box').hide();
		},
		click: function(){
			return false;
		}
	});
	
	function showTooltip(btnObj){
		var offset = btnObj.offset();
		tipDiv = btnObj.attr('id').replace('help_','helptext_');
		tipText = $('#' + tipDiv).html();
		$('#tt_container').html(tipText);
		tipHeight = $('#tooltip_box').height();
		$('#tooltip_box').css({'top': offset.top - tipHeight,'left': offset.left -258}).fadeIn(200);
	}
	
	
	
	/*
	//set some default css for help boxes
	hideHelp();
	//activate help text on hover
	hoverHelp()
	
	// on hover show help
	function hoverHelp(){
		var helpBtn = null;
		$('a.help').hover(function(){	
			helpBtn = $(this);
			showHelp($(this));
		},function(){
			hideHelp();
		}).click(function(){
			return false;
		});		
	}
	
	function showHelp(btnObj){
		//when help button is clicked hide any current help boxes, set the correct 
		//positioning and then fade in
		btnObj.removeAttr('title');
		var offset = btnObj.offset();
		textName = '#helptext_' + btnObj.attr('id').substr(5);
		if(!btnObj.hasClass('active')){	
			$('div.help').removeClass('active');
			$('div.helptext[id!='+textName+']').hide();
			$(textName).css({'top':(offset.top)+'px','left':(offset.left+48)+'px'})
			.fadeIn('slow');		
		}
	}
	
	function hideHelp(){
		$('a.help').removeClass('active');
		$('div.helptext').css({'position':'absolute','display':'none'});
	}
	
	
	function moveHelptext(offsetDiff){
		//function moves help text boxes when accordians are collapsed or expanded
		//if there are any help buttons active
		if($('div.active').length){
			textName = '#helptext_' + $('div.active').attr('id').substr(5);
			oldOffset = $(textName).offset();
			oldOffsetTop = oldOffset.top;
			offset = $('div.active').offset();
			newOffsetTop = offset.top;
			//animate movement with bounce effect
			if(newOffsetTop > oldOffsetTop){
				$(textName).animate( { 'top':(newOffsetTop)+'px' } , 400 )
				.animate( { 'top':(newOffsetTop-15)+'px' } , 150 )
				.animate( { 'top':(newOffsetTop)+'px' } , 150 )
				.animate( { 'top':(newOffsetTop-8)+'px' } , 100 )
				.animate( { 'top':(newOffsetTop)+'px' } , 100 );
			}
			if(newOffsetTop < oldOffsetTop){
				$(textName).animate( { 'top':(newOffsetTop)+'px' } , 400 )
				.animate( { 'top':(newOffsetTop+15)+'px' } , 150 )
				.animate( { 'top':(newOffsetTop)+'px' } , 150 )
				.animate( { 'top':(newOffsetTop+8)+'px' } , 100 )
				.animate( { 'top':(newOffsetTop)+'px' } , 100 );
			}
		}
	}*/	
	
	/**** Ajax postcode lookup ****/
	
	curPage = currentPage();
	if(curPage == 'delivery.php'){
		addLookupBtns();
	}		
	
	$('#lookup_pcode').click(function(){
		pcodeVal = $("#pcode").children('input').val();
		return lookupAddress(pcodeVal,false);
	});
	
	$('#lookup_b_pcode').click(function(){
		pcodeVal = $("#b_pcode").children('input').val();
		return lookupAddress(pcodeVal,true);
	});
	
	function addLookupBtns(){
		//re-arrange fields, putting house no and postcode near the top
		$('#hno').after($('#pcode'));
		$('#b_hno').after($('#b_pcode'));
		// add lookup button
		$("#pcode").append('<a href="#" id="lookup_pcode" title="Look up my address and populate address fields">Find my address</a>');
		$("#b_pcode").append('<a href="#" id="lookup_b_pcode" title="Look up my address and populate address fields">Find my address</a>');
		//add loading animation
		$('#lookup_pcode').after('<img id="ajax_loader" src="images/edgeward/ajax-loader-pcode.gif" style="display:none;width:12px;height:12px;padding-left:5px;"');
		$('#lookup_b_pcode').after('<img id="b_ajax_loader" src="images/edgeward/ajax-loader-pcode.gif" style="display:none;width:12px;height:12px;padding-left:5px;"');
	}
	
	function mergeAddress1(){
		add1Val = trim($('#house_no').val()) + ' ' + trim($('#address_1').val());
		$('#act_address_1').val(add1Val);
	}
	
	function lookupAddress(postcode,billingFields){
		var fldPre = '';
		if(billingFields) fldPre = 'b_';
		var data = 'isAjax=1&postcode=';
		//validate the postcode
		if(!/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/.test(pcodeVal)){
			alert('The postcode entered is invalid or empty.');
			return false
		}
		//postcode = 'zz99'; //testing only, remove on live
		data += postcode;
		var anim = $('#' + fldPre + 'ajax_loader');
		anim.show();
		
		//send to lookup script via ajax		
		$.ajax({
			type: "POST",
			url: "get_postcode.php",
			data: data,
			success: function(response){				
				//get the response
				r = response.split('&');
				var addrReturned = false;
				for (var y in r) {
					vals = r[y].split("=");
					//update fields
					if (vals[0] == 'address_1') {
						theField = $('input[name=' + fldPre + 'street_name]');
						theField.val(trim(vals[1]));
						theField.change();
					}
					else {
						theField = $('input[name=' + fldPre + vals[0] + ']');
						theField.val(trim(vals[1]));
						theField.change();
					}
					if(vals[1]) addrReturned = true;
					//v = checkValidField(theField);
				}
				anim.hide();
				if(!addrReturned) alert('No address information could be found for this postcode.');
			}
		});			
		return false;
	}
			   
});

function trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function currentPage(){
	sPath = window.location.pathname;
	sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
	return sPage;
}
