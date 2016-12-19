/*************************************************
Javascript document for ancestryshop.co.uk

uses the jQuery library (jquery.com) version 1.3.2
jQuery validation plugin (http://docs.jquery.com/Plugins/Validation)
jQuery tooltip plugin (http://docs.jquery.com/Plugins/tooltip)

Production version

Created by Paul Smith of Edgeward (Edgeward.co.uk)
Last updated 18/05/2009

*************************************************/

$(document).ready(function(){

/**** Shopping Basket Stuff ****/
	
	/**** Update Totals ****/
	
	//remove the update button
	$('#update_basket').remove();
	qty_update();
	//update the basket qty
	function qty_update(){
		//if qty entered is not a number or is 0, make it 1
		$('input.line_qty').keyup(function(){	
			if(isNaN($(this).val()) || $(this).val() < 1){
				$(this).val(1);
			}
			update_cart_totals();			
		});
		$('input.line_qty').change(function(){
			//if qty entered is not a number or is 0, make it 1
			if(isNaN($(this).val()) || $(this).val() < 1){
				$(this).val(1);
			}
			$("img.ajax_loader").show();
			ajax_update_cart();
			update_cart_totals();
		});
		$('div.inline_radio input.basket_update').click(function(){
			$("img.ajax_loader").show();
			ajax_update_cart();	
			update_cart_totals();					
		});
		
	}
	
	function update_cart_totals(){		
		//update display totals
		var basketTotal = 0;
		var discountTotal = 0;
		var shipTotal = $('input[name=delivery_method]:checked').attr('rev')*1;
		$("tr.line_item").each(function(){
			qty = $(this).find("input.line_qty").val();
			price = $(this).find("span.line_price").text();
			discount = $(this).find("span.line_disc").text().length ? $(this).find("span.line_disc").text() : 0;			
			lineTotal = qty*price;
			lineTotal = (lineTotal-(lineTotal*(discount/100))).toFixed(2);
			basketTotal += lineTotal*1;
			$(this).find("span.line_total").text(lineTotal);
		});
		$("span.basket_total").text((basketTotal-discountTotal).toFixed(2));
		$("span.order_total").text(((basketTotal-discountTotal)+shipTotal).toFixed(2));
		penceAmount = ((((basketTotal-discountTotal)+shipTotal).toFixed(2))*100).toFixed(0)
		$('input[name=amount]').val(penceAmount);	
	}
	
	function ajax_update_cart(){
		//show loading anim
		$("img.ajax_loader").show();
		//remove any previous flash message
		$("div.alert_flash").remove();
		$('#submit_cart').attr('disabled','disabled');
		//do an ajax post to update the cart session
		var data = 'isAjax=1';
		$('#basket_form input').each(function(){
			var fieldVal = $(this).val();
			if($(this).attr('type') == 'radio') fieldVal = $('input[name='+$(this).attr('name')+']:checked').val();
			if($(this).attr('type') == 'checkbox'){
				if($('input[name='+$(this).attr('name')+']:checked').length){
					fieldVal = $('input[name='+$(this).attr('name')+']:checked').val();
				} else {
					fieldVal = '';	
				}
			}
			data += '&'+ $(this).attr('name') + '=' + fieldVal; 						  
		});
		$.ajax({
			type: "POST",
			url: "upd_cart.php",
			data: data,
			success: function(response){
				$("img.ajax_loader").queue(function(){
					//get the response
					r = response.split('&');
					for ( var y in r ){
						vals = r[y].split("=");
						//update quantities
						qInd = vals[0].indexOf("-quantity");
						if(qInd >=0) $("#lnqty_"+vals[0].substring(0,qInd)).val(vals[1]);
						//show any alert message
						if(vals[0] == 'alert' && vals[1]){
							if($('div.alert_flash').length >0){
								alertText = $('div.alert_flash').html();
								if(alertText.indexOf(vals[1]) <0) $('div.alert_flash').html(alertText+vals[1]);
							} else {
								$('#basket_form').before('<div class="alert_flash">'+vals[1]+'</div>');
							}
						}
						if(vals[0] == 'token' && vals[1]){
							$('input[name=token]').val(vals[1]);
						}
					} 
					$(this).dequeue();
				});
				//$("img.ajax_loader").queue(function(){
					update_cart_totals();
					$("img.ajax_loader").hide();
					$('#submit_cart').removeAttr('disabled');
				//});
			}
		});	
	}	
});