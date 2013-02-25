jQuery(function($){
	var zIndexNumber = 1000;
		$('div,.top_menu li').each(function() {
			$(this).css('zIndex', zIndexNumber);
			zIndexNumber -= 10;
		});
		
	$("#top_bar .login_reg_bar").css('opacity','0.7');
	$("#top_bar .login_reg_bar li:last-child").css('border','none');
	$("#top_bar .languge_bar").css('opacity','0.7');
	$("#top_bar .languge_bar li:last-child").css('border','none');
	
	//$(".top_menu li > ul").css('opacity','0.9');
	
	
	$('.news_slide').find('.news_slider_content:eq(1)').css('margin-right','0px');
	
});