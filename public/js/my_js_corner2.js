jQuery(function($){
	var corner_default_settings = {
		      tl: { radius: 10 },
		      tr: { radius: 10 },
		      bl: { radius: 10 },
		      br: { radius: 10 },
		      antiAlias: true
		    };
	 /***** Language bar corner START ************/
	 
	 DD_roundies.addRule('#top_bar .languge_bar','10px',true);	
	 DD_roundies.addRule('#top_bar .login_reg_bar','10px',true);
	 DD_roundies.addRule('#top_bar .languge_bar li a','10px',true);
	 /**** Language bar corner END *******/
	 
	 /*************SITE CORNER START*****************/
	 
	 DD_roundies.addRule('#site_content_bar','40px 40px 0px 0px',true);
	 var header_bar_corner_settings = {
				tl: { radius: 33 },
			    tr: { radius: 33 },
			    bl: { radius: 0 },
			    br: { radius: 0 },
			    antiAlias: true
		};
	 
	//* curvyCorners(header_bar_corner_settings, '#header_bar');
		
	 /*************SITE CORNER END*****************/
		
	/***** Top menu corner START ******/
	//DD_roundies.addRule('.top_menu > ul > li > a','10px',true);
	DD_roundies.addRule('.top_menu>ul>li li a','10px',true);
	
	/***** Top menu corner END *********/
	
	/****** News slider corner START *******/
	

	DD_roundies.addRule('.news_slide_body','10px 10px 10px 10px',true);
//*	curvyCorners(corner_default_settings, '.news_slide_body h1 a');
//	DD_roundies.addRule('#news_slide_body h1 a','10px',true);
//*	 curvyCorners(corner_default_settings, '.news_slider_content');
	//DD_roundies.addRule('.news_slider_content','10px',true);

	//$.fn.corner.defaults.useNative = false;
	$('.news_slider_content_link').corner('cc:#ffffff');
	
	/****** News slider corner END *******/
	
	
});