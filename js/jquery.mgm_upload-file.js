// JavaScript Document
(function($){
$.fn.extend({
	uploadFile: function(options){
		var optionsDefault = {
			product: '',
			action: 'upload-file.php',
			browser: ''
		}
		
		var option = $.extend(optionsDefault, options);
		
		switch(option.product){
			case 'AU':
				option.action = 'au-upload-file.php';
				break;
			case 'TR':
				option.action = 'tr-upload-file.php';
				break;
		}
		
		option.browser = checkBrowser();
		
		$(this).change(function(){
			if(option.product.length > 0){
				var id_att = $(this).prop('id');
				
				var explorer = option.browser.split('|');
				if(explorer[0] == 5 && explorer[2] < 10){
					var fso = new ActiveXObject("Scripting.FileSystemObject");
					var filepath = document.getElementById(this.id).value;
					alert(filepath);
					var thefile = fso.getFile(filepath);
					var sizeinbytes = thefile.size;
					//alert("File size:"+ sizeinbytes + " bytes");
					
				}else{
					var formData = new FormData();
					
					var file = this.files[0];
					formData.append(formData.name, file);
				}
				
				alert(formData);
				$.ajax({
					url:option.action,
					type:'GET',
					data:formData,
					async:true,
					cache:false,
					contentType: false,
					processData: false,
					success: function(result){
						alert(result);
					}
				});
				return false;
			}
		});
		
	}
});
})(jQuery);