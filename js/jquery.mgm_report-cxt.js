// JavaScript Document
(function($){
$.fn.extend({
	reportCxt: function(options){
		var optionsDefault = {
			product: 'DE',
			context: 'RF',
			menu : '',
			id: 'cxt-cntnr'
		};
		
		var option = $.extend(optionsDefault, options);
		
		$(this).parent().parent().after('<div class="'+option.id+'"></div>');
		
		$(this).hover(function(e) {
			$("."+option.id).hide();
			$(this).addClass('hover');
		},function(){
			$(this).removeClass('hover');
		});
		
		$(this).click(function(e){
			if(e.target.nodeName !== 'A'){
				var rel = parseInt($(this).attr('rel'));
			
				if(rel === 0){
					$(this).addClass('active');
					rel = 1;
				}else if(rel === 1){
					$(this).removeClass('active');
					rel = 0;
				}
				
				$(this).attr('rel', rel);
			}
		});
		
		$(this).on('contextmenu', function (e) {
			e.preventDefault();
			
			var token = parseInt($(this).attr('data-token'));
			var ide = $(this).attr('data-nc');
			var idd = '';
			var idv = '';
			var idp = '';
			var idm = '';
			var issue = $(this).attr('data-issue');
			var quote = 0;
			if($(this).hasClass('quote') === true) {
				quote = 1;
			}
			var bc = parseInt($(this).attr('data-bc'));
			if (bc === 1) {
				idd = '&idd=' + $(this).attr('data-dd');
			}
			
			var ms = '';	var page = '';
			
			if(token === 0){
				ms = $("#ms").prop('value');
				page = $("#page").prop('value');
			}else if(token === 1){
				
			}
			
			var iX = e.pageX;
			var iY = e.pageY;
			
			$("."+option.id).css('left', iX);
			$("."+option.id).css('top', iY);
			
			$("."+option.id).css('background', '#FFFFFF url(img/loading-01.gif) center center no-repeat');
			$("."+option.id).show();
			
			switch (option.product) {
				case 'AU': idv = $(this).attr('data-vh'); break;
				case 'TRD': idp = $(this).attr('data-pr'); break;
				case 'TRM': idm = $(this).attr('data-mt'); break;
			}
			
			
			$("."+option.id).html('');
			$.get('get-contextmenu.php', 'product=' + option.product + '&ide=' + ide 
				+ idd + '&idv=' + idv + '&idp=' + idp + '&idm=' + idm + '&token=' + token 
				+ '&ms=' + ms + '&page=' + page + '&quote=' + quote + '&issue=' + issue, function(data){
				$("."+option.id).css('background', '#FFFFFF');
				$("."+option.id).html(data);
			});
		});
	}
	
	
	
});
})(jQuery);