$(document).ready(function(){
	
	var webroot = '/anc_dev/';
	//prod detail page -  show large image
	$('#med_prod_image').fancybox(); 
	
	//product detail page - change large image by clicking on thumbnail
	$('a.prod_thumb').click(function(){
		return changeProdImage($(this));
	});
	
	function changeProdImage(imgLink,fadeSpeed){
		if(!fadeSpeed) fadeSpeed = 400;
		img = imgLink.children('img').attr('src');
		var medImg = img.replace('_S','_MED');
		var lrgImg = img.replace('_S','');
		var med = $('#med_prod_image');
		if(med.children('img').attr('src') == medImg) return false; 
		med.fadeOut(fadeSpeed,function(){
			imgPreloader = new Image();
			// once image is preloaded, show big image
			imgPreloader.onload=function(){
				med.attr('href',lrgImg).children('img').attr('src',medImg);
				med.fadeIn(fadeSpeed);
			};			
			imgPreloader.src = medImg;
		});
		return false;
	}
	
	//Qty select
	$('select.prod_qty').parent('p').show();
	$('select.prod_qty').change(function(){
		btn = $('a.buy_now');
		btnUrl = btn.attr('href');
		btnUrlArray = btnUrl.split('&qty=');
		qty = $(this).val();	
		newBtnUrl = btnUrlArray[0] + '&qty=' + qty;
		$('select.prod_qty').val(qty);
		btn.attr('href',newBtnUrl);
	});
	
	//site search input
	width = $('#search_label').width();
	$('#search_label').width(width).height(1);
	var searchTxt = $('#search_label').text();
	$('#search').val(searchTxt);
	
	$('#search').focus(function(){
		if($('#search').val() == searchTxt){
			$('#search').val('');
		}
	});
	
	$('#product_search').submit(function(){
		if($('#search').val() == searchTxt){
			return false;
		}
	});
	
	//footer - other sites popup
	$('#other_sites_link').click(function(){
		var cont = $('#other_sites_container');
		if (!cont.hasClass('visible')) {
			cont.show();
			cont.addClass('visible');			
		} else {
			cont.hide();
			cont.removeClass('visible');
		}
		return false;
	});
	
	$('#closeFtrSitesX').click(function(){
		var cont = $('#other_sites_container');
		cont.hide();
		cont.removeClass('visible');
		return false;
	});
	
	//Homepage promo banner
	var btns = $('#promo_links li a');
	btns.click(function(){
		return changeImage($(this));		
	});
	
	function changeImage(imgLink){
		var promo = imgLink.parent('li').attr('id');
		promo = promo.split('_');
		promoNo = promo[2];
		$('#promo_links li a').removeClass('current');
		imgLink.addClass('current');		
		$('div.home_promo').hide();
		$('#home_promo_' + promoNo).show();
		return false;
	}
	
	//Show second "add to basket" box on prod details depending on viewport size
	var lowerBuyBox = $('div.buy_box.lower');	
	if($('#content_wrapper').height() < 550){
		lowerBuyBox.hide();
	} else {
		//show back to top links on H2s
		showBacktotop();
	}
	
	function showBacktotop(){
		var headings = $('#prod_content h2');
		noHeadings = headings.size();
		if(noHeadings >1){
			headings.each(function(i) {
				if(i >=1) $(this).append('<a href="#" class="backtotop" style="position:absolute;right:10px;top:3px;color:#fff">Back to top</a>')
				.css({"padding-right": "90px"});
			});
		}
	}
	
	//print page links
	$('a.print_page').click(function(){
		window.print(); return false;
	});
	
	

});