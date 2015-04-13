// JavaScript Document
(function($){
	$.fn.extend({
		autocomplete: function(options){
			var optionsDefault = {
				url: '',
				method: 'GET',
				type: 'CL',
				pr: 'DE',
				loading: true,
				nameLoading: '.loading'
			}
			
			var option = $.extend(optionsDefault, options);
			
			var imgLoading = option.nameLoading+' img';
			
			$(this).after('<div class="autocomplete-container"></div>');
			
			$(this).keyup(function(e){
				var text = $(this).prop("value");
				
				if(text.length !== 0 && !/^\s+$/.test(text)){
					$('.autocomplete-container').css('border', '1px solid #333');
					$.ajax({
						url:option.url,
						type:option.method,
						data:'text='+text.toUpperCase()+'&type='+option.type+'&product='+option.pr,
						//dataType:"json",
						async:true,
						cache:false,
						beforeSend: function(){
							$('.autocomplete-container').css({
								'min-height': '200px',
								'background': '#FFFFFF url(img/loading-01.gif) center center no-repeat'
							});
						},
						complete: function(){
							$('.autocomplete-container').css({
								'min-height': '0',
								'background': '#FFFFFF'
							});
						},
						success: function(result){
							$(".autocomplete-container").html(result);
						}
					});
					return false;
				}else{
					$(".autocomplete-container").html('');
					$('.autocomplete-container').css('border', '0 none');
				}
			});
			
			$("body").click(function(e) {
				if(e.target.id !== '.autocomplete-container'){
					$(".autocomplete-container").html('');
					$('.autocomplete-container').css('border', '0 none');
				}
			});
			
		}
	});
})(jQuery);