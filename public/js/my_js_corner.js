/*CORNER HOVER FUNCTION START*/
function My_corner_hover(hover_path,add_class,check_cluss_exist)
{
	var corner_default_settings = {
		      tl: { radius: 10 },
		      tr: { radius: 10 },
		      bl: { radius: 10 },
		      br: { radius: 10 },
		      antiAlias: true
		    };
	
	$(hover_path).live({
		mouseover:
			function(){
			if( $(this).hasClass(check_cluss_exist))
			{
				return false;
			}
			 var lang_width = $(this).find('.autoPadDiv').width() + 3;
			 $(this).find('.autoPadDiv').css('width',lang_width+"px");
			 
			$(this).addClass(add_class);
			curvyCorners(corner_default_settings, this);
			
			$("#top_bar .languge_bar .hover_select").css('opacity','0.8');
		},
		mouseleave:
			function(){
			if($(this).hasClass(check_cluss_exist))
			{
				return false;
			}
			str = $(this).find('.autoPadDiv').text();
			if($(this).find('img').is('*'))
			{
				clone = $(this).find('img').clone();
				$(this).find('div').remove();
				$(this).append(clone);
			}
			else
			{
				$(this).find('div').remove();
			}
			$(this).append(str);
			$(this).removeClass();
			$(this).removeAttr('style');
		}
	});
}

/*CORNER HOVER FUNCTION END*/



jQuery(function($){
	
	 var corner_default_settings = {
		      tl: { radius: 10 },
		      tr: { radius: 10 },
		      bl: { radius: 10 },
		      br: { radius: 10 },
		      antiAlias: true
		    };
	 /***** Language bar corner START ************/
	 
	
	 curvyCorners(corner_default_settings, '#top_bar .languge_bar');
	 curvyCorners(corner_default_settings, '#top_bar .login_reg_bar');
	 var lang_width = $('#top_bar .languge_bar .autoPadDiv').width() + 3;
	 $('#top_bar .languge_bar .autoPadDiv').css('width',lang_width+"px");
	 
	 /*Language bar select START*/
	 curvyCorners(corner_default_settings, '#top_bar .languge_bar .lang_select');
	 var lang_width = $('#top_bar .languge_bar li a').find('.autoPadDiv').width() + 3;
	$('#top_bar .languge_bar li a').find('.autoPadDiv').css('width',lang_width+"px");
	 
	 /*Language bar select END*/
	 
	 /*Language bar hover START*/
	 My_corner_hover('#top_bar .languge_bar li a','lang_hover','lang_select');
	 /*Language bar hover END*/
	 
	 /**** Language bar corner END *******/
	 
	 /*************SITE CORNER START*****************/
	 
	 var site_bar_corner_settings = {
				tl: { radius: 40 },
			    tr: { radius: 40 },
			    bl: { radius: 0 },
			    br: { radius: 0 },
			    antiAlias: true
		};
		
	 curvyCorners(site_bar_corner_settings, '#site_content_bar');
		
		var header_bar_corner_settings = {
				tl: { radius: 33 },
			    tr: { radius: 33 },
			    bl: { radius: 0 },
			    br: { radius: 0 },
			    antiAlias: true
		};
		curvyCorners(header_bar_corner_settings, '#header_bar');	
	 
	 /*************SITE CORNER END*****************/
		
		
	/***** Top menu corner START ******/
		curvyCorners(corner_default_settings, '.top_menu_select');	
		My_corner_hover('.top_menu > ul > li > a', 'top_menu_hover','top_menu_select');
		
		/*Top menu sub*/
		var corner_top_menu_sub_seettings = {
				tl: { radius: 0 },
			    tr: { radius: 0 },
			    bl: { radius: 10 },
			    br: { radius: 10 },
			    antiAlias: true
		};
		curvyCorners(corner_top_menu_sub_seettings, '.top_menu li ul' );
		My_corner_hover('.top_menu > ul > li li a','top_submenu_hover','top_submenu_select');
	/***** Top menu corner END *********/
		
	/****** News slider corner START *******/
		var corner_news_slider_seettings = {
				tl: { radius: 0 },
			    tr: { radius: 10 },
			    bl: { radius: 10 },
			    br: { radius: 10 },
			    antiAlias: true
		};
		curvyCorners(corner_news_slider_seettings, '#news_slide_body' );
		curvyCorners(corner_default_settings, '#news_slide_body h1 a' );
		curvyCorners(corner_default_settings, '.news_slider_content_corner' );
		curvyCorners(corner_default_settings, '.news_slider_content_img' );
	/****** News slider corner END *******/

});