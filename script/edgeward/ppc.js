$(function(){		
	//faq
	$('#faq').parents('div.tab').removeClass('inactive_tab'); //show the tab in order to get dd heights
	$('#faq dd').wrap('<div class="dd_wrapper"/>');
	$('div.dd_wrapper').each(function(){
		var dd = $(this);
		var height = dd.height();
		dd.data('height',height).height(0);
	});
	$('#faq').parents('div.tab').addClass('inactive_tab');
	firstWrap = $('#faq .dd_wrapper:first');
	firstWrap.height(firstWrap.data('height'));
	$('#faq dt:first').addClass('open');		
	var animSpeed = 300;
	$('#faq dt').click(function(){
		if(!$(this).hasClass('open')){
			closeDef($('#faq dt.open'));
			openDef($(this));
		} else {
			closeDef($('#faq dt.open'));
		}
	}).append('<div class="click_arrow"/>');
	
	function closeDef(dtObj){
		dtObj.next('div.dd_wrapper').animate({
			height: 0
		},animSpeed,function(){
			dtObj.removeClass('open');
		});
	}
	
	function openDef(dtObj){
		dtObj.addClass('open');
		def = dtObj.next('div.dd_wrapper');	
		defHeight = def.data('height');			
		def.animate({
			height: defHeight
		},animSpeed);
	}
	
	//tabs
	var tabControls = $('#nav li a');
	tabControls.click(function(){
		var tabNo = $(this).attr('id');
		tabNo = tabNo.replace('tc','');
		$('#nav li').removeClass('current');
		$(this).parent('li').addClass('current');
		$('.tab').addClass('inactive_tab');
		$('#tab_' + tabNo).removeClass('inactive_tab');
		return false;
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
	
	//Lightbox
	$('.prod_img').fancybox();
	
});