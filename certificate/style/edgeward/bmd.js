/*************************************************
Javascript document for ancestryshop.co.uk

uses the jQuery library (jquery.com) version 1.3.2
and jQuery validation plugin (http://docs.jquery.com/Plugins/Validation)

Prototype version 1

Created by Paul Smith of Edgeward (Edgeward.co.uk)
Last updated 27/5/2009

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
	
	// Get the country information			   
	var countryData = $('#country_data').val();
	var country = "UK";
	if(countryData){
		//extract the country data - currency symbol and extra copy price
		countryData = countryData.split('|');
		country = countryData[0];
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
	if(country == "AU$"){
		jQuery.validator.addMethod("postcode", function(value, element) { 
		  return this.optional(element) || /^([0-9]{4})$/.test(value); 
		}, "Invalid postcode.");
		postcodeReq = false;
	} else {
		jQuery.validator.addMethod("postcode", function(value, element) { 
		  return this.optional(element) || /^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) {0,1}[0-9][A-Za-z]{2})$/.test(value); 
		}, "Invalid postcode.");	
	}
	
	/* Basic validation rules */
	$("form.shop_form").validate({
		rules: {
			birth_surname: {required: true, alpha: true},
			birth_forename: {required: true, alpha: true},
			birth_year: {required: true,digits: true, min: 1837, max:year},
			birth_place: {alpha: true},
			DOB_d: {required: true},
			DOB_m: {required: true},
			DOB_y: {required: true,digits: true, min: 1837, max:year},
			GROI_year: {required: true,digits: true, min: 1837, max:year},
			GROI_quarter: {required: true,digits: true, min: 1, max:4},
			GROI_district: {required: true, alpha: true},
			GROI_volume_digits: {required: true},
			GROI_page_digits: {required: true,digits: true, min: 1, max:9999},
			GROI_reg_month: {required: true,digits: true, min: 1, max:12},
			GROI_reg_year: {required: true,digits: true, min: 1837, max:year},
			no_of_certs: {required: true,digits: true, min: 1},
			title: {required: true, alpha: true},
			forename: {required: true, alpha: true},
			surname: {required: true, alpha: true},
			address_1: {required: true},
			town: {required: true, alpha: true},
			county: {required: true, alpha: true},
			postcode: {required:{depends: function(element){return postcodeReq}},postcode: true },
			tel: {required: true, phone: true},
			email: {required: true,	email: true	},
			marriage_year: {required: true,digits: true, min: 1837, max:year},
			marriage_man_surname: {alpha: true},
			marriage_man_forename: {alpha: true},
			marriage_woman_surname: {alpha: true},
			marriage_woman_forename: { alpha: true},
			death_surname: {required: true, alpha: true},
			death_forename: {required: true, alpha: true},
			death_year: {required: true,digits: true, min: 1837, max:year},
			DOD_y: {digits: true, min: 1837, max:year},
			death_age: {required: true, digits: true,min:0, max:130},
			b_title: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_forename: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_surname: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_address_1: {required: {depends: function(element){return $("#billing_different:checked").length}}},
			b_town: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_county: {required: {depends: function(element){return $("#billing_different:checked").length}}, alpha: true},
			b_postcode: {required: {depends: function(element){return ($("#billing_different:checked").length && postcodeReq)}},postcode: true },
			// display/disabled fields (only activate thru ajax)
			d_birth_surname: {required: true, alpha: true},
			d_birth_forename: {required: true, alpha: true},
			d_birth_year: {required: true,digits: true, min: 1837, max:year},
			d_birth_place: {alpha: true},
			d_DOB_d: {required: true},
			d_DOB_m: {required: true},
			d_DOB_y: {required: true,digits: true, min: 1837, max:year},
			d_GROI_year: {required: true,digits: true, min: 1837, max:year},
			d_GROI_quarter: {required: true,digits: true, min: 1, max:4},
			d_GROI_district: {required: true, alpha: true},
			d_GROI_volume_digits: {required: true},
			d_GROI_page_digits: {required: true,digits: true},
			d_GROI_reg_month: {required: true,digits: true, min: 1, max:12},
			d_GROI_reg_year: {required: true,digits: true, min: 1837, max:year},
			d_no_of_certs: {required: true,digits: true, min: 1},
			d_title: {required: true},
			d_forename: {required: true, alpha: true},
			d_surname: {required: true, alpha: true},
			d_address_1: {required: true},
			d_town: {required: true, alpha: true},
			d_county: {required: true, alpha: true},
			d_postcode: {required:{depends: function(element){return postcodeReq}}, postcode: true},
			d_tel: {required: true, phone: true},
			d_email: {required: true,	email: true	},
			d_marriage_year: {required: true,digits: true, min: 1837, max:year},
			d_marriage_man_surname: {alpha: true},
			d_marriage_man_forename: { alpha: true},
			d_marriage_woman_surname: { alpha: true},
			d_marriage_woman_forename: { alpha: true},
			d_death_surname: {required: true, alpha: true},
			d_death_forename: {required: true, alpha: true},
			d_death_year: {required: true,digits: true, min: 1837, max:year},
			d_DOD_y: {digits: true, min: 1837, max:year},
			d_death_age: {required: true, digits: true,min:0, max:130},
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
	
	//add terms to summary
	$('label[for=terms]').append("<br>Tick the box if you agree to be bound by our Terms &amp; Conditions.")
	.after("<input name=\"terms\" type=\"checkbox\" id=\"terms\" />");
	
	$("#tc_form").validate({
		rules: {
			terms: {required: true}
		},
		messages: {
			terms: {required: "You must accept the Terms &amp; Conditions in order to place your order."}
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
/*		if(!val){
			//fieldObj.nextAll("label.valid").removeClass('valid');
			fieldObj.parents("div.input").addClass('error').removeClass('valid');	
			return false;
		} else {
			fieldObj.parents("div.input").removeClass('error').addClass('valid');	
			return true;
		}*/
	}


	
	/**** Accordion Functionality ****/
	
	$('div.accordion span.legend').each(function(){
		var thisHeight = $(this).parents("div.accordion").height();
		$(this).parents("div.accordion").attr({'rev':thisHeight});
		if(!$(this).hasClass('acc_open')) accClose($(this),false);
		//add text
		if(!$(this).hasClass('notag')) $(this).text($(this).text()+" [Click to review]");
	});
	$('div.accordion span.legend').hover(function () {
		$('body').css({"cursor":"pointer"}); 
		$(this).css({"background-position":"0 -26px"}); 
	},function () {
		$('body').css('cursor', 'default');
		$(this).css({"background-position":"0 0"}); 
	});
	/*$('div.accordion span.legend_mid').hover(function () {
		$(this).css({"background":"url(img/fieldset_mid_hl.png) repeat-x"});
	},function () {
		$(this).css({"background":"url(img/fieldset_head_mid.png) repeat-x"});
	});*/

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
			, 800,null,function(){moveHelptext(-(thisHeight-25))});
		} else {
			accObj.parents("div.accordion").height(25);
			moveHelptext();
		}
		accObj.removeClass('acc_open');
		accObj.addClass('acc_closed');			
	}
	function accOpen(accObj,bolAnimate){
		var thisHeight = accObj.parents("div.accordion").attr('rev');
		if(bolAnimate){
			accObj.parents("div.accordion").animate( 
				{height: thisHeight+"px"}
			, 800,null,function(){moveHelptext(thisHeight-25)});
		} else {
			accObj.parents("div.accordion").height(thisHeight);
			moveHelptext();
		}
		accObj.removeClass('acc_closed');	
		accObj.addClass('acc_open');
	}
	
	/**** Accordian Tooltip ****/
	
	$('div.accordion span.legend').tooltip({
		bodyHandler: function() {return 'Click to open/close';},
	   showURL: false,
	   track: true	
	});

	
	/**** Activate billing address ****/

	$("#billing_different").click(function(){										   

		if($('#acc_billing_add').hasClass('acc_closed') && $("#billing_different:checked").length){
			accOpen($('#acc_billing_add'),true);
		} else if(!$('#acc_billing_add').hasClass('acc_closed') && !$("#billing_different:checked").length){
			accClose($('#acc_billing_add'),true);	
		}
	});
	

	
	/**** Delivery Total ****/
	updateDelTotal(); // on page load
	$('input[name$=no_of_certs],select[name$=scan_and_send]').change(function(){
		updateDelTotal();
	}).keyup(function(){
		updateDelTotal();
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
			var scan = $('select[name$=scan_and_send]').val();
			if(isNaN(del)) del = $('input #del_1').val();
			var delMulti = exP[srv];

			var dtotal = (q*delMulti)+(del*1)+(scan*1);
			if(isNaN(dtotal)){
				$("#del_total").text('');
			} else {
				t = dtotal.toFixed(2);
				$("#del_total").text(t);	
				amount = (t*100).toFixed(0)
				$('input[name=amount]').val(amount);
			}
			if(!$('#del_total').length){
				totalCode = '<strong>Order Total: '+symbol+'<span id="del_total">'+dtotal.toFixed(2)+'</span></strong>';
				$('#del_total_wrapper').html(totalCode);
			}
		}
	}
	
	/**** Help Text Popups ****/
	
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
	}
	
	/**** Ajax editable summary form ****/
	
	$('.edit').click(function(){
		$("img.ajax_loader").show();
		var postType = $(this).attr('id').substr(5);
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
		var data = 'isAjax=1&token='+$('input[name=token]').val();
		//if the button="save" and form is valid, do ajax save
		if($(this).hasClass('save') && $("form.shop_form").valid()){
			var btnObj = $(this);
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
					data += '&'+ $(this).attr('name').substr(2) + '=' +	fieldVal; 						
				});
			} 
			$.ajax({
				type: "POST",
				url: formUrl,
				data: data,
				success: function(response){
					$("img.ajax_loader").queue(function(){
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
						} 
						$('.'+postType+'_field').each(function(){
							$(this).attr("disabled","disabled");
						});
						btnObj.text('Edit').removeClass('save');
						$('label.error').remove();
						$("div.input").removeClass('error').removeClass('valid');
						$(this).dequeue();
					});
					$("img.ajax_loader").hide();
					//enable the form submit button
					$('#form_submit').removeAttr("disabled").removeClass('btn_disabled');
				}
			});					
		} else { //edit button	
			//enable the fields in this fieldset
			$('.'+postType+'_field').each(function(){
				$(this).removeAttr("disabled");
			});
			//change the edit button
			$(this).text('Save').addClass('save');
			//disable the form submit button
			$('#form_submit').attr("disabled","disabled").addClass('btn_disabled');
			$("img.ajax_loader").hide();
		}
		
		return false;
	});

			   
});