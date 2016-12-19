/*************************************************
Javascript document for ancestryshop.co.uk

uses the jQuery library (jquery.com) version 1.3.2
and jQuery validation plugin (http://docs.jquery.com/Plugins/Validation)

Prototype version 1

Created by Paul Smith of Edgeward (Edgeward.co.uk)

*************************************************/

//change default validation messages
jQuery.extend(jQuery.validator.messages, {
	maxlength: jQuery.format("Maximum {0} characters."),
	minlength: jQuery.format("Minimum {0} characters."),
	email: "Invalid email address.",
	number: "Must be a number.",
	digits: "Digits only.",
	max: jQuery.format("Maximum {0}."),
	min: jQuery.format("Minimum {0}.")
});

$(document).ready(function(){
	
	//get the current page name
	var curPage = currentPage();
	// Get the country information			   
	var countryData = $('#country_data').val();
	var country = "073";
	country = $('#country').val(); //country code
	if(countryData){
		//extract the country data - currency symbol and extra copy price
		countryData = countryData.split('|');
		countryCurrency = countryData[0];
	}
						   
	/**** Form Validation ****/	
	
	var d = new Date();
	//date minus 18 months
	d.setDate(d.getDate()-547);
	var year = d.getFullYear();
	
	var validYear = "Enter a valid year 1837-"+year;
	var validMonth = "Enter a valid month (01-12)";
	var validQuarter = "Enter 1 to 4";
	
	//custon validation method for phone number, allows for numbers and spaces only
	jQuery.validator.addMethod("phone", function(value, element) { 
	  return this.optional(element) || !/[^\s*^\d*]/.test(value); 
	}, "Enter a valid phone number.");
	//custon validation method for alphabetical, disallows digits
	jQuery.validator.addMethod("alpha", function(value, element) { 
	  return this.optional(element) || !/[^\D*]/.test(value); 
	}, "Alphabetical characters only.");
	//custon validation method for postcodes
	var postcodeReq = true;
	
	switch (country)
	{
		case "012": //Australia
			jQuery.validator.addMethod("postcode", function(value, element) { 
			  return this.optional(element) || /^([0-9]{4})$/.test(value); 
			}, "Invalid postcode.");
			//postcodeReq = false;
		break;
		case "001": //US
			jQuery.validator.addMethod("postcode", function(value, element) { 
			  return this.optional(element) || /^[0-9]{5}([- /]?[0-9]{4})?$/.test(value); 
			}, "Invalid Zip Code.");
			//postcodeReq = false;
		break;
		case "034": //canada
			jQuery.validator.addMethod("postcode", function(value, element) { 
			  return this.optional(element) || /^[ABCEGHJKLMNPRSTVXYabceghjklmnprstvxy]{1}\d{1}[A-Za-z]{1} *\d{1}[A-Za-z]{1}\d{1}$/.test(value); 
			}, "Invalid Postal Code.");
			//postcodeReq = false;
		break;
		case "133": //NZ
			jQuery.validator.addMethod("postcode", function(value, element) { 
			  return this.optional(element) || /^([0-9]){4}?$/.test(value); 
			}, "Invalid Postal Code.");
			//postcodeReq = false;
		break;
		default: //UK
			jQuery.validator.addMethod("postcode", function(value, element) { 
			  return this.optional(element) || /^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/.test(value); 
			}, "Invalid postcode.");	
		break;
		
	}
	
	/* Basic validation rules */
	$("form.shop_form").validate({
		rules: {
			birth_surname: {required: true, alpha: true},
			birth_forename: {required: true, alpha: true},
			GROI_year: {required: true,digits: true, min: 1837, max:year},
			GROI_quarter: {required: true,digits: true, min: 1, max:4},
			GROI_district: {required: true},
			GROI_volume_number: {required: true},
			GROI_page_number: {required: true,digits: true, min: 1, max:9999},
			GROI_entry_number: {required: true,digits: true, min: 1, max:9999},
			GROI_reg_number: {required: true},
			GROI_district_number: {required: true},
			GROI_reg_month: {required: true,digits: true, min: 1, max:12},
			GROI_reg_year: {required: true,digits: true, min: 1837, max:year},
			no_of_certs: {required: true,digits: true, min: 1},
			title: {required: {depends: function(element){return $("#country").val() != '001';}}, alpha: true},
			forename: {required: true, alpha: true},
			surname: {required: true, alpha: true},
			house_no: {required:true},
			street_name: {required:true , alpha: true},
			address_1: {required: true},
			town: {required: true, alpha: true},
			county: {required: {depends: function(element){return $("#country").val() != '133'}}, alpha: true},
			postcode: {required:{depends: function(element){return postcodeReq}},postcode: true },
			tel: {required: {depends: function(element){return $("#country").val() != '001'}}, phone: true},
			email: {required: true,	email: true	},
			marriage_year: {required: true,digits: true, min: 1837, max:year},
			marriage_man_surname: {alpha: true,required: {depends: function(element){return !(($("#marriage_woman_surname").val().length || $("#marriage_woman_forename").val().length) && !$("#marriage_man_forename").val().length)}}},
			marriage_man_forename: {alpha: true,required: {depends: function(element){return !(($("#marriage_woman_surname").val().length || $("#marriage_woman_forename").val().length) && !$("#marriage_man_surname").val().length)}}},
			marriage_woman_surname: {alpha: true,required: {depends: function(element){return !(($("#marriage_man_forename").val().length || $("#marriage_man_surname").val().length) && !$("#marriage_woman_forename").val().length)}}},
			marriage_woman_forename: { alpha: true,required: {depends: function(element){return !(($("#marriage_man_forename").val().length || $("#marriage_man_surname").val().length) && !$("#marriage_woman_surname").val().length)}}},
			death_surname: {required: true, alpha: true},
			death_forename: {required: true, alpha: true},
			b_title: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_forename: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_surname: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_house_no: {required:{depends: function(element){return $("#billing_different:checked").length}}},
			b_street_name: {required:{depends: function(element){return $("#billing_different:checked").length}} , alpha: true},
			b_address_1: {required: {depends: function(element){return $("#billing_different:checked").length}}},
			b_town: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_county: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_postcode: {required: {depends: function(element){return ($("#billing_different:checked").length && postcodeReq)}},postcode: true },
			// display/disabled fields (only activate thru ajax)
			d_birth_surname: {required: true, alpha: true},
			d_birth_forename: {required: true, alpha: true},
			d_GROI_year: {required: true,digits: true, min: 1837, max:year},
			d_GROI_quarter: {required: true,digits: true, min: 1, max:4},
			d_GROI_district: {required: true},
			d_GROI_volume_number: {required: true},
			d_GROI_page_number: {required: true,digits: true},
			d_GROI_reg_month: {required: true,digits: true, min: 1, max:12},
			d_GROI_reg_year: {required: true,digits: true, min: 1837, max:year},
			d_GROI_entry_number: {required: true,digits: true, min: 1, max:9999},
			d_GROI_reg_number: {required: true},
			d_GROI_district_number: {required: true},
			d_no_of_certs: {required: true,digits: true, min: 1},
			d_title: {required: true},
			d_forename: {required: true, alpha: true},
			d_surname: {required: true, alpha: true},
			d_address_1: {required: true},
			d_town: {required: true, alpha: true},
			d_county: {required: {depends: function(element){return $("#country").val() != '133'}}, alpha: true},
			d_postcode: {required:{depends: function(element){return postcodeReq}}, postcode: true},
			d_tel: {required: true, phone: true},
			d_email: {required: true,	email: true	},
			d_marriage_year: {required: true,digits: true, min: 1837, max:year},
			d_marriage_man_surname: {alpha: true,required: {depends: function(element){return !(($("#marriage_woman_surname").val().length || $("#marriage_woman_forename").val().length) && !$("#marriage_man_forename").val().length)}}},
			d_marriage_man_forename: {alpha: true,required: {depends: function(element){return !(($("#marriage_woman_surname").val().length || $("#marriage_woman_forename").val().length) && !$("#marriage_man_surname").val().length)}}},
			d_marriage_woman_surname: {alpha: true,required: {depends: function(element){return !(($("#marriage_man_forename").val().length || $("#marriage_man_surname").val().length) && !$("#marriage_woman_forename").val().length)}}},
			d_marriage_woman_forename: { alpha: true,required: {depends: function(element){return !(($("#marriage_man_forename").val().length || $("#marriage_man_surname").val().length) && !$("#marriage_woman_surname").val().length)}}},
			d_death_surname: {required: true, alpha: true},
			d_death_forename: {required: true, alpha: true},
			d_b_title: {required: {depends: function(element){return $("#billing_different:checked").length}}},
			d_b_forename: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			d_b_surname: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			d_b_address_1: {required: {depends: function(element){return $("#billing_different:checked").length}}},
			d_b_town: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			d_b_county: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			d_b_postcode: {required: {depends: function(element){return ($("#billing_different:checked").length && postcodeReq)}},postcode: true }
		},
		messages: {
			GROI_reg_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			d_GROI_reg_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			GROI_reg_month: {digits: validMonth, min: validMonth, max:validMonth},
			d_GROI_reg_month: {digits: validMonth, min: validMonth, max:validMonth},
			GROI_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			d_GROI_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			marriage_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			d_marriage_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			birth_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			d_birth_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			death_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},
			d_death_year: {digits: validYear, min: validYear, max:validYear,maxlength:validYear},	
			death_age: {min:jQuery.format("Minimum {0}"), max:jQuery.format("Maximum {0}")},
			d_death_age:{min:jQuery.format("Minimum {0}"), max:jQuery.format("Maximum {0}")},
			no_of_certs: {min: "Minimum 1 certificate."},
			d_no_of_certs: {min: "Minimum 1 certificate."},
			DOB_y: {digits: validYear, min: validYear, max:validYear},
			d_DOB_y: {digits: validYear, min: validYear, max:validYear},
			DOD_y: {digits: validYear, min: validYear, max:validYear},
			d_DOD_y: {digits: validYear, min: validYear, max:validYear},
			GROI_quarter: {digits: validQuarter, min: validQuarter, max:validQuarter},
			d_GROI_quarter: {digits: validQuarter, min: validQuarter, max:validQuarter}
		},
		groups: {
			DOB: "DOB_y DOB_m DOB_d",
			DOD: "DOD_y DOD_m DOD_d",
			d_DOB: "d_DOB_y d_DOB_m d_DOB_d",
			d_DOD: "d_DOD_y d_DOD_m d_DOD_d"
		},
		success: function(label) {
			if(!$('#content_wrapper').hasClass('new_bmd')){
				if(!label.prev('input').hasClass('noval')) label.addClass("valid").text("OK");
			}
		},
		highlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')){
				$(element).parents("div.input").removeClass('valid').addClass(errorClass);
				$(element).css({ backgroundColor:"#FFE6E8",border:"1px solid #990000"});
			}
		},
		unhighlight: function(element, errorClass) {
			if(!$(element).hasClass('noval')){
				$(element).parents("div.input").removeClass(errorClass).addClass('valid');
				if (!$('#content_wrapper').hasClass('new_bmd')) { //if old style forms
					$(element).css({
						backgroundColor: "#FFF",
						border: "1px solid #B9AD93"
					});
				} else { //if new style forms
					$(element).css({
						backgroundColor: "#FFF",
						border: "1px solid #546C11"
					});
				}
				
			}
		}

	});
	
	//add terms to summary
	$('label[for=terms]').append("<br>Tick the box if you agree to be bound by our Terms &amp; Conditions.")
	.after("<input name=\"terms\" type=\"checkbox\" id=\"terms\" />");
	//attach validation to terms
	validateTerms();
	
	function validateTerms(){
		$("#tc_form").validate({
			rules: {
				terms: {required: true}
			},
			messages: {
				terms: {required: "In order to proceed you will need to accept our Terms & Conditions to place your order."}
			},
			success: function(label) {
				if(!label.prev('input').hasClass('noval')) label.addClass("valid").text("OK");
			},
			highlight: function(element, errorClass) {
				if(!$(element).hasClass('noval')){
					$(element).parents("div.input").removeClass('valid').addClass(errorClass);
					$(element).css({ backgroundColor:"#FFE6E8",border:"1px solid #990000"});
				}
			},
			unhighlight: function(element, errorClass) {
				if(!$(element).hasClass('noval')){
					$(element).parents("div.input").removeClass(errorClass).addClass('valid');
					$(element).css({ backgroundColor:"#FFF",border:"1px solid #B9AD93"});
				}
			}
	
		});
	}
	
	
	/* Validation Alerts */
	$("input, select").blur(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
		}
	}).keyup(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
		}
	}).change(function() {
		if(!$(this).hasClass('noval')){
			val = checkValidField($(this));
		}
	});
	function checkValidField(fieldObj){
		val = fieldObj.valid();		
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
	
	/*** New BMD sections ****/
	
	if(curPage == 'summary_2.php'){
		//if billing address is different make the box closeable
		if($("[id$=billing_different]:checked").length) $('#bil_add_fields').children('h2.section_header').addClass('section_acc');
		var sectionHeaders = $('h2.section_acc');
		//close at page load
		sectionHeaders.addClass('section_closed').next('div.fieldset_section').hide();
		sectionHeaders.each(function(){
			if ($(this).children('a').length) {
				$(this).children('a').before(' (click to open)');
			} else {
				$(this).append(' (click to open)');
			}
			
		});
		sectionHeaders.live('mouseover',function(){
			$(this).css({cursor:'pointer'});
		}).live('mouseout',function(){
			$(this).css({cursor:'default'});
		}).live('click',function(){
			if($(this).hasClass('section_closed')){
				$(this).html($(this).html().replace('open','close')).removeClass('section_closed').next('div.fieldset_section').show();
			} else {
				$(this).html($(this).html().replace('close','open')).addClass('section_closed').next('div.fieldset_section').hide();
			}
		});
	}
	
	
	/**** Accordian Tooltip ****/
	
	/*$('div.accordion span.legend').tooltip({
		bodyHandler: function() {return 'Click to open/close';},
	   showURL: false,
	   track: true	
	});*/

	
	/**** Activate billing address ****/
	
	if(!$("[id$=billing_different]:checked").length) $('#bil_add_fields').hide().addClass('hidden');
	
	$("#billing_different, #d_billing_different").click(function(){										   
		if($('#content_wrapper').hasClass('new_bmd')){ //if new style 2 column
			//make ready for closeable section
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
		} else {
			if($('#acc_billing_add').hasClass('acc_closed') && $("#billing_different:checked").length){
				accOpen($('#acc_billing_add'),true);
			} else if(!$('#acc_billing_add').hasClass('acc_closed') && !$("#billing_different:checked").length){
				accClose($('#acc_billing_add'),true);	
			}
		}
		
	});
	

	
	/**** Delivery Total ****/
	updateDelTotal(); // on page load
	
	$('input[name$=no_of_certs],select[name$=scan_and_send]').bind('change keyup', function() {
		updateDelTotal();
	});
	$('input[name$=scan_and_send]').click(function(event) {
		if ($(event.target).is('[type=checkbox]') ) {			
			updateDelTotal();
		}
	});
	$('input[name$=delivery_method]').click(function(){
		updateDelTotal();
	});
	function updateDelTotal(){
		var countryData = $('#country_data').val();
		if(countryData){
			//extract the country data - currency symbol and extra copy price
			countryData = countryData.split('|');
			var symbol = countryData[0];
			var exPrice = countryData[1].split(',');
			var exP = new Array()
			for ( var i in exPrice ){
				p = exPrice[i].split('-');
				exP[p[0]] = p[1];
			} 
			//make the total input visible
			$('#del_total_wrapper').show();
			//number of certs 
			var q = $('input[name$=no_of_certs]').val()*1;	
			q = q-1;
			//cost of delivery
			var del = $('input[name$=delivery_method]:checked').attr('rev')*1;
			//the type of service
			var srv = $('input[name$=delivery_method]:checked').val();
			
			//scan and send?
			var scanOption = $('[name$=scan_and_send]');
			var scan = 0;
			if(scanOption.attr('type') == 'checkbox'){
				scan = scanOption.is(':checked') ? scanOption.val() : 0;
			} else {
				scan = scanOption.val();
			}
			if(isNaN(del)) del = $('input #del_1').val();
			var delMulti = exP[srv];
			//discount code amount?
			var discount = $('#order_discount').val() ? $('#order_discount').val() : 0;
			var discTotal = ((q*delMulti)+(del*1))/100 * discount;
			if(discount) $('#disc_total').text(discTotal.toFixed(2) + ' (' + discount + '%)');
			//show discount if applicable
			if(discount >0) $('#order_discount_total').removeClass('hidden');
			//total
			var dtotal = (q*delMulti)+(del*1)+(scan*1)-discTotal;
			if(isNaN(dtotal)){
				$("#del_total").text('');
			} else {
				//update secure trading field
				t = dtotal.toFixed(2);
				$("#del_total").text(t);	
				amount = (t*100).toFixed(0)
				$('input[name=amount]').val(amount);
			}
			if(!$('#del_total').length){
				//ast = symbol != '&pound;' ? '**' : '';
				ast = '';
				totalCode = '<strong>Order Total: ' + symbol + '<span id="del_total">'+dtotal.toFixed(2)+'</span> ' + ast + '</strong>';
				$('#del_total_wrapper').html(totalCode);
			}
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
	
	/**** Ajax editable summary form ****/
	
	$('.edit, #apply_discount').click(function(){
		//if we're on the cert details page, dont do ajax
		if(!$('#edit_del').length) return true;
		var btnObj = $(this);
		btnObj.after('<img id="ajax_loader" src="../images/edgeward/ajax-loader-1.gif"/>');
		
		var btnId = btnObj.attr('id');
		//if using apply discount btn, make the callback apply to the save btn
		if (btnId == 'apply_discount') {
			btnObj = $('#edit_del');
			btnId = 'edit_del';
		}
		var postType = btnId.substr(5);
		
		var formset = "";
		var formUrl = postType =='add' ? 'upd_address.php' : 'upd_cert.php';
		var editArray = new Array();
		if(postType== 'par' || postType == 'gro' || postType == 'del'){
			formset = 'details';
			editArray = ['par','gro','del'];
		}
		if(postType == 'add'){
			formset = 'address';
			editArray = ['add'];
		}
		var data = 'btn=Save&isAjax=1&token=' + $('input[name=token]').val();
		//if the button="save" and form is valid, do ajax save
		if( btnObj.hasClass('save') && $("form.shop_form").valid()){
			for ( var i in editArray ){			
				$('.'+editArray[i]+'_field').each(function(){
					var fieldVal = $(this).val();
					if($(this).attr('type') == 'radio') fieldVal = $('input[name='+$(this).attr('name')+']:checked').val();
					if($(this).attr('type') == 'checkbox'){
						if($('input[name='+$(this).attr('name')+']:checked').length){
							fieldVal = $('input[name='+$(this).attr('name')+']:checked').val();
						} else {
							fieldVal = '';	
						}
					}
                    fieldName = $(this).attr('name').substr(0,2) == 'd_' ? $(this).attr('name').substr(2): $(this).attr('name');
                    if(data.indexOf('&'+fieldName+'=') <0)	data += '&'+ fieldName + '=' +	fieldVal; 						
				});
			} 
			$.ajax({
				type: "POST",
				url: formUrl,
				data: data,
				success: function(response){
					//hide discount field until we need it
					$('#order_discount_total').addClass('hidden');
					$('#offer_code_error').addClass('hidden'); //hide error
					$('#order_discount').val('0'); //set discount to 0
					//callback
					$("#ajax_loader").queue(function(){
						//get the response
						r = response.split('&');
						for ( var y in r ){
							vals = r[y].split("=");
							if(vals[0] == 'token' && vals[1]){
								$('input[name=token]').val(vals[1]);
							}
							if(vals[0] == 'qty' && vals[1]){
								$('#no_of_certs,#d_no_of_certs').val(vals[1]);
							}
							if(vals[0] == 'inv_disc' && vals[1]){
								//invalid discount code, show error								
								$('#offer_code_error').removeClass('hidden');
								$('#d_discount_code, #discount_code').val('');
							}
							if(vals[0] == 'disc_amount') {
								//valid code, show discount								
								$('#order_discount').val(vals[1]);
								$('#order_discount_total').removeClass('hidden');
							}
						} 
						//disable relevant fields
						$('.'+postType+'_field').each(function(){
							$(this).attr("disabled","disabled");
						});
						//change the edit button to save
						btnObj.text('Edit').removeClass('save');
						//remove all validation errors
						$('label.error').remove();
						$("div.input").removeClass('error').removeClass('valid');
						//update the fields on secure trading form
						$("#tc_form input[name=name]").val($("#d_b_forename").val()+" "+$("#d_b_surname").val());
						if($("#d_address_1").length){
							$("#tc_form input[name=address]").val($("#d_b_address_1").val()+", "+$("#d_b_address_2").val());
						} else {
							$("#tc_form input[name=address]").val($("#d_b_house_no").val()+" " + $("#d_b_street_name").val() + ", "+$("#d_b_address_2").val());
						}
						$("#tc_form input[name=town]").val($("#d_b_town").val());
						$("#tc_form input[name=county]").val($("#d_b_county").val());
						$("#tc_form input[name=postcode]").val($("#d_b_postcode").val());
						$("#tc_form input[name=email]").val($("#d_b_email").val());
						$("#tc_form input[name=telephone]").val($("#d_b_tel").val());
						$(this).dequeue();
						updateDelTotal();
					});
					$("#ajax_loader").remove();
					//hide apply discount btn
					$('#apply_discount').addClass('hidden');
					//enable the form submit button
					$('#form_submit').removeAttr("disabled").removeClass('btn_disabled');
				}
			});					
		} else { //edit button	
			//enable the fields in this fieldset
			$('.'+postType+'_field').each(function(){
				$(this).removeAttr("disabled");
			});
			//show apply discount btn
			if(postType == 'del') $('#apply_discount').removeClass('hidden');
			//change the edit button
			btnObj.text('Save').addClass('save');
			//disable the form submit button
			$('#form_submit').attr("disabled","disabled").addClass('btn_disabled');
			$("#ajax_loader").remove();
		}
		
		return false;
	});
	
	/**** Form v3 ajax submit ****/
	
	//change the submit behaviour
	$('#d3_form_submit').click(function(){
		$('#d3_form_submit').after('<img class="ajax_loader" src="../images/edgeward/ajax-loader-1.gif"/>');

		//validate form
		if (!$('#cert_details_form').valid()) {
			$("img.ajax_loader").remove();
			return false;
		}
		//disable the form submit button
		$('#d3_form_submit').addClass('btn_disabled').attr("disabled", true);
		var data = 'btn=Save&isAjax=1&' + $('#cert_details_form').serialize();
		//save data via ajax
		$.ajax({
			type: "POST",
			url: 'update_order.php',
			data: data,
			success: function(response){
				//hide discount field until we need it
				$('#order_discount_total').addClass('hidden');
				$('#offer_code_error').addClass('hidden'); //hide error
				$('#order_discount').val('0'); //set discount to 0
				var error = false;
				var saved = false;
				//callback
				$("#ajax_loader").queue(function(){					
					//get the response
					r = response.split('&');
					for ( var y in r ){
						vals = r[y].split("=");
						if(vals[0] == 'token' && vals[1]){
							$('input[name=token]').val(vals[1]);
						}
						if(vals[0] == 'qty' && vals[1]){
							$('#no_of_certs,#d_no_of_certs').val(vals[1]);
						}
						if(vals[0] == 'inv_disc' && vals[1]){
							//invalid discount code, show error								
							$('#offer_code_error').removeClass('hidden');
							$('#d_discount_code, #discount_code').val('');
						}
						if(vals[0] == 'disc_amount') {
							//valid code, show discount								
							$('#order_discount').val(vals[1]);
							$('#order_discount_total').removeClass('hidden');
						}						
						if(vals[0] == 'alert' && vals[1]) {
							error = vals[1];
						}
						if(vals[0] == 'saved' && vals[1]) {
							saved = true;
						}			
					} 
					//if data was saved and there are no errors, load modal
					if(saved && !error){
						//load in the terms modal
						loadTerms('cert_terms.php');						
					}
					
					//remove all validation errors
					$('label.error').remove();
					$("div.input").removeClass('error').removeClass('valid');
					$(this).dequeue();					
					updateDelTotal();
				});
				
				//enable the form submit button
				$('#d3_form_submit').removeAttr("disabled").removeClass('btn_disabled');
				
				if(error) alert('Error: ' + error);
				$("img.ajax_loader").remove();	
			}
		});	
		return false;
	});
	
	function showTermsModal(){
		$('#window_trans, #mod_wrapper').delay(300).show();
		//positioning
		var top = $(window).scrollTop() + 5 + 'px';
		$('#mod_wrapper').css({
			'top': top,
			'margin-top':'0'
		});
	}
	
	function hideTermsModal(){
		$('#window_trans, #mod_wrapper').hide();
	}
	
	function loadTerms($href){
		$.get($href, {}, function(data) {
			if(!$('#mod_wrapper').length){
				//add wrapper for modal window
				$('body').append('<div id="mod_wrapper"/>');
				$('body').append('<div id="window_trans" class="trans"/>');
			}
		    var $response = $('<div />').html(data.replace(/<script(.|\s)*?\/script>/g, ""));
		    var $content = $response.find('#terms_modal');
			$('#mod_wrapper').empty().append($content.html());    
			//add tickbox to terms
			$('label[for=terms]').append("<br><strong>Tick the box if you agree to be bound by our Terms &amp; Conditions.</strong>")
			.after("<input name=\"terms\" type=\"checkbox\" id=\"terms\" />");
			//add cancel buttons
			//$('#terms_submit').after('<a id="terms_cancel" href="#" class="close_modal">Edit details</a>');
			$('#back_action').html('<a href="#" class="close_modal">edit the details</a>');
			//attach validation to terms
			validateTerms();
			//update token on cert details form
			$('#cert_details_form input[name=token]').val($('#mod_wrapper input[name=token]').val());
			//show modal
			showTermsModal();
		},'html');
		//clicking outside the modal window closes it
		$('#window_trans, .close_modal').live('click',function(){
			hideTermsModal();
			return false;
		});
	}
	
	//populate fields for testing
	/*var testFields = { 
		birth_surname : 'Bourne', 
		birth_forename : 'Jason' , 
		GROI_quarter : '1',
		GROI_district : 'Greenwich',
		GROI_volume_number : '321',
		GROI_page_number : '2',
		title : 'Mr',
		forename : 'Fred',
		surname : 'Bourne',
		email : 'fred@bourne.com',
		tel : '02089876542',
		house_no : '214',
		postcode : 'w2 2rr',
		street_name : 'Accacia Ave',
		town : 'Ealing',
		county : 'London'
		}
	$('h1.header').click(function(){
		for(var prop in testFields) {
		    if(testFields.hasOwnProperty(prop)){
				//console.log(prop,testFields[prop]);
				$('#' + prop).val(testFields[prop]);
			}
		}

	});*/
			   
	/**** Ajax postcode lookup ****/
	
	
	if((curPage == 'delivery.php' || curPage == 'delivery_2.php' || curPage == 'cert_details_3.php') && country == '073'){
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
		$loaderFile = (curPage == 'delivery_2.php' || curPage == 'cert_details_3.php') ? 'ajax-loader-1.gif' : 'ajax-loader-pcode.gif';
		$('#lookup_pcode').after('<img id="ajax_loader" src="../images/edgeward/' + $loaderFile + '" style="display:none;width:12px;height:12px;padding-left:5px;"/>');
		$('#lookup_b_pcode').after('<img id="b_ajax_loader" src="../images/edgeward/' + $loaderFile + '" style="display:none;width:12px;height:12px;padding-left:5px;"/>');
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
		// IMPORTANT! TESING ONLY REMOVE NEXT LINE ON LIVE
		//postcode = 'zz99'; //testing only, remove on live
		data += postcode;
		var anim = $('#' + fldPre + 'ajax_loader');
		anim.show();
		
		//send to lookup script via ajax		
		$.ajax({
			type: "POST",
			url: "../get_postcode.php",
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
				}
				anim.hide();
				if(!addrReturned) alert('No address information could be found for this postcode.');
			}
		});			
		return false;
	}
	
	//google analytics, cross domain tracking stuff
	$('#tc_form').live('submit',function(){
		if(_gaq) _gaq.push(['_linkByPost', this]);
	});
	
	
			   
}); //end of document.ready

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


