// JavaScript Document
(function($){
$.fn.extend({
	rcCxt: function(options){
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

			var ids = $(this).attr('data-nc');

			var iX = e.pageX;
			var iY = e.pageY;

			$("."+option.id).css('left', iX);
			$("."+option.id).css('top', iY);

			$("."+option.id).css('background', '#FFFFFF url(img/loading-01.gif) center center no-repeat');
			$("."+option.id).show();


			$("."+option.id).html('');
			$.get('get-rc-contextmenu.php', '&ids=' + ids, function(data){
				$("."+option.id).css('background', '#FFFFFF');
				$("."+option.id).html(data);
			});
		});
	}



});
})(jQuery);