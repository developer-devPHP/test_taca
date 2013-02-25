
var current_element;

var sitemapHistory = {
    stack: new Array(),
    temp: null,
    //takes an element and saves it's position in the sitemap.
    //note: doesn't commit the save until commit() is called!
    //this is because we might decide to cancel the move
    saveState: function(item) {
        sitemapHistory.temp = { item: $(item), itemParent: $(item).parent(), itemAfter: $(item).prev() };
    },
    commit: function() {
        if (sitemapHistory.temp != null) sitemapHistory.stack.push(sitemapHistory.temp);
    },
    //restores the state of the last moved item.
    restoreState: function() {

        var h = sitemapHistory.stack.pop();
        if (h == null) return;
        if (h.itemAfter.length > 0) 
        {
            h.itemAfter.after(h.item);
        }
        else 
        {
            h.itemParent.prepend(h.item);
        }
        send_data_to_server(h.item.attr('id'));
		//checks the classes on the lists
		$('#sitemap li.sm2_liOpen').not(':has(li)').removeClass('sm2_liOpen');
		$('#sitemap li:has(ul li):not(.sm2_liClosed)').addClass('sm2_liOpen');
    }
};


//init functions
jQuery(function($) {
	$('#sitemap').disableSelection();
	$('#sitemap li').prepend('<div class="dropzone"></div>');

    $('#sitemap dl, #sitemap .dropzone').droppable({
        accept: '#sitemap li',
        tolerance: 'pointer',
        drop: function(e, ui) {
            var li = $(this).parent();
            var child = !$(this).hasClass('dropzone');
            if (child && li.children('ul').length == 0) {
                li.append('<ul/>');
            }
            if (child) {
                li.addClass('sm2_liOpen').removeClass('sm2_liClosed').children('ul').append(ui.draggable);
            }
            else {
                li.before(ui.draggable);
            }
			$('#sitemap li.sm2_liOpen').not(':has(li:not(.ui-draggable-dragging))').removeClass('sm2_liOpen');
            li.find('dl,.dropzone').css({ backgroundColor: '', borderColor: '' });
            sitemapHistory.commit();
            
            send_data_to_server(current_element);
            
        },
        over: function() {
            $(this).filter('dl').css({ backgroundColor: '#ccc' });
            $(this).filter('.dropzone').css({ borderColor: '#aaa' });
        },
        out: function() {
            $(this).filter('dl').css({ backgroundColor: '' });
            $(this).filter('.dropzone').css({ borderColor: '' });
        }
    });
    $('#sitemap li').draggable({
        handle: ' > dl',
        opacity: .8,
        addClasses: false,
        helper: 'clone',
        zIndex: 100,
        start: function(e, ui) {
            sitemapHistory.saveState(this);
            
            current_element = $(this).attr('id');
        }
    });
   // $('.sitemap_undo').click(sitemapHistory.restoreState);
    $(document).bind('keypress', function(e) {
        if (e.ctrlKey && (e.which == 122 || e.which == 26))
            sitemapHistory.restoreState();
    });
	$('.sm2_expander').live('click', function() {
		$(this).parent().parent().toggleClass('sm2_liOpen').toggleClass('sm2_liClosed');
		return false;
	});
	
	
	
	// CHECK BOX
	$("#sitemap input:checkbox").live('click',function(){
		var checked = $(this).attr('checked');
		var element_id = $(this).parents('li').attr('id');
		if(checked == undefined)
		{
			var all_elements = {};
			$("#"+element_id+" > ul li").each(function(n){
				child_element = $(this).attr('id');
				if(child_element != undefined)
				{
					$('#'+child_element+" input:checkbox").removeAttr('checked');
					all_elements[n] =child_element; 
				}
				
			});
			all_elements_json =$.toJSON(all_elements); 
			$.ajax({
				  type: 'POST',
				  url: '/admin-ajax/insertvisibility',
				  data: {element : element_id, element_childes: all_elements_json, visibility: 0}
				});
		}
		else
		{
			$.ajax({
				  type: 'POST',
				  url: '/admin-ajax/insertvisibility',
				  data: {element : element_id, visibility: 1}
				});
		}
		
	});
	
	
		
});

// Other functions

function send_data_to_server(current_item){
	var tarreri_array = {};
	
	tarreri_array['arajin'] = current_item;
	 //alert($(current_item).attr('id'));
     //alert($(current_item).parents('li').attr('id'));
     
     tarreri_array['erkrord'] = $('#'+tarreri_array['arajin']).parents('li').attr('id');
     
     
     var hertakanutyan_array = {};
     var flag = false;
     if(tarreri_array['erkrord'] != undefined)
     {
         $("#"+tarreri_array['erkrord']+" > ul > li").each(function(n){
         	
         	if($(this).attr('id') != undefined && ($(this).attr('id') != tarreri_array['arajin'] || flag == false))
         	{
         		if($(this).attr('id') == tarreri_array['arajin'])
         		{
         			flag = true;
         		}
         		
         		hertakanutyan_array[$(this).attr('id')] = n+1;
         	}
         	
         });
     }
     else
     {
     	tarreri_array['erkrord'] = '0';
     	$('#sitemap > li').each(function(n){
         	//hertakanutyan_array
     		if($(this).attr('id') != undefined && ($(this).attr('id') != tarreri_array['arajin'] || flag == false))
         	{
         		if($(this).attr('id') == tarreri_array['arajin'])
         		{
         			flag = true;
         		}
         		
         		hertakanutyan_array[$(this).attr('id')] = n+1;
         	}
         });
     	
     }
       
  araji_erkrord = $.toJSON(tarreri_array); //JSON.stringify(tarreri_array);
  hertakanutyan_array = $.toJSON(hertakanutyan_array); //JSON.stringify(hertakanutyan_array);
 
 // alert($('#'+tarreri_array['erkrord']).attr('id'));
  //alert(araji_erkrord);
     $.ajax({
		  type: 'POST',
		  url: '/admin-ajax/insertsortingmenu',
		  data: { dirqy : araji_erkrord, hertakanutyuny: hertakanutyan_array  }
		});
}
