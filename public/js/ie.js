jQuery(function($){
	
	if($.browser.msie)
	{
		
		if($.browser.version == '7.0')
		{
			//$('.news_slider_content_link').uncorner();
			$('.news_slider_content h4 a').css({
				'font-size':'10px'
			});
			$('.news_slide_body h1').css({
				'margin-bottom':'0px'
			});
			$('.home_slider').css({'margin':'10px 10px 0 0'});
		}
	}
	
});