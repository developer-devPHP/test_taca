jQuery(function($){
	/* $('.top_menu li').hover(
		        function () {
		            //show its submenu
		            $('ul', this).stop().slideDown(200);
		 
		        }, 
		        function () {
		            //hide its submenu
		            $('ul', this).stop().slideUp('fast');          
		        }
		    );*/
	
	/*$(".top_menu li").live({
		
		hover:
			function(){
			$('ul',this).stop().slideToggle('medium');
		}
	});*/
	 $('#top_bar .languge_bar li a').each(function(){
		if(!$(this).hasClass('lang_select'))
		{
			$(this).css({'width':'21px','text-indent':'-999999px'});
		}
	 });
	/* $('#top_bar .languge_bar li a').hover(function(){
		 
		 if(!$(this).hasClass('lang_select'))
		 {
			//$(this).removeAttr('style');
		 }
	 },function(){
		 if(!$(this).hasClass('lang_select'))
		 {
			 //$(this).css({'width':'21px','text-indent':'-999999px'});
		 }
	 }
	 );*/
	$('#login_botton').live('click',function(){
					$.fancybox({
						href : 'http://seua.am/en/login',
						autoSize	: false,
						type: 'iframe', //iframe
						//content: 'gdgfdg',
						/*ajax: {
							url: 'http://seua.am/en/login',
							type: 'POST',
							data: {id : 'my id'}
						},*/
						padding : 5,
						height: 300,
						width:  400,
						helpers     : {
				            overlay : {
				                closeClick: false
				            }
				        }
					});
	});
	
	
	/*$('#news_slide_body').easySlider({
			auto: true,
			continuous: true,
			numeric: true,
			speed: 900
			//hoverPause: true
	});
	*/
	
	 $(".news_slide_body").slides({
		 	preload: true,
		 	container: 'news_slides_container',
		 	play: 5000,
	        pause: 2500,
	        hoverPause: true,
	        paginationClass: 'news_slide_pagination',
	        currentClass: 'news_slider_current'
	 });
});
