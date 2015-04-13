// JavaScript Document
function checkBrowser(){
	var is_safari = navigator.userAgent.toLowerCase().indexOf('safari/') > -1;
	var is_chrome= navigator.userAgent.toLowerCase().indexOf('chrome/') > -1;
	var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox/') > -1;
	var is_opera = navigator.userAgent.toLowerCase().indexOf('opr/') > -1;
	var is_ie = navigator.userAgent.toLowerCase().indexOf('msie ') > -1;
	
	if (is_safari && !is_chrome){
		var posicion = navigator.userAgent.toLowerCase().indexOf('Version/');
		var ver_safari = navigator.userAgent.toLowerCase().substring(posicion+9, posicion+12);
		ver_safari = parseFloat(ver_safari);
		return ('1|Safari|'+ver_safari);
	}
	
	if (is_chrome && !is_opera){
		var posicion = navigator.userAgent.toLowerCase().indexOf('chrome/');
		var ver_chrome = navigator.userAgent.toLowerCase().substring(posicion+7, posicion+11);
		ver_chrome = parseFloat(ver_chrome);
		return ('2|Google Chrome|'+ver_chrome);
	}
	
	if (is_opera){
		var posicion = navigator.userAgent.toLowerCase().indexOf('opr/');
		var ver_opera = navigator.userAgent.toLowerCase().substring(posicion+4, posicion+15);
		ver_opera = parseFloat(ver_opera);
		return ('3|Opera|'+ver_opera);
	}
	
	if (is_firefox){
		var posicion = navigator.userAgent.toLowerCase().lastIndexOf('firefox/');
		var ver_firefox = navigator.userAgent.toLowerCase().substring(posicion+8, posicion+12);
		ver_firefox = parseFloat(ver_firefox); 
		return ('4|Firefox|'+ver_firefox);
	}
	
	if (is_ie){
		var posicion = navigator.userAgent.toLowerCase().lastIndexOf('msie ');
		var ver_ie = navigator.userAgent.toLowerCase().substring(posicion+5, posicion+10);
		ver_ie = parseFloat(ver_ie);
		return ('5|Internet Explorer|'+ver_ie);
	}
}

function sidebarMenu(){
	var menu = $('#menu-container');
	var menu_offset = menu.offset();
	
	$(window).on('scroll', function() {
		if($(window).scrollTop() > menu_offset.top) {
			menu.addClass('set-menu');
		} else {
			menu.removeClass('set-menu');
		}
	});
}

function get_tinymce(element){
	tinymce.init({
		selector: "textarea#"+element,
		theme: "modern",
		width: 550,
		height: 150,
		menubar : false,
		plugins: [
			 "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			 "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			 "save table contextmenu directionality emoticons template paste textcolor"
	   ],
	   content_css: "css/content.css",
	   toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor", 
	   style_formats: [
			{title: 'Bold text', inline: 'b'},
			{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
			{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
			{title: 'Example 1', inline: 'span', classes: 'example1'},
			{title: 'Example 2', inline: 'span', classes: 'example2'},
			{title: 'Table styles'},
			{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
		],
		statusbar: false,
	});
}

function go_to_home(){
	$("header .logo-01").click(function(e){
		e.preventDefault();
		location.href = 'index.php';
	});
}

function set_ajax_upload(field, product){
	var action = '';
	if(product === 'AU' || product === 'TRM' || product === 'DE' || product === 'US'){
		//action = 'upload-file.php?product='+product+'&attached='+attached;
		action = 'upload-file.php?product='+product;
	}
	
	var button = $('#a-'+field), interval;
	new AjaxUpload(button,{
		action: action,
		name: 'attached',
		onSubmit : function(file, ext){
			this.setData({ attached: $('#'+field).prop('value')} );
			// cambiar el texto del boton cuando se selecicione la imagen
			button.text('Subiendo');
			// desabilitar el boton
			this.disable();
			
			interval = window.setInterval(function(){
				var text = button.text();
				if (text.length < 11){
					button.text(text + '.');
				} else {
					button.text('Subiendo');
				}
			}, 200);
		},
		onComplete: function(file, response){
			window.clearInterval(interval);
			
			// Habilitar boton otra vez
			//this.enable();
			//alert(response)
			result = response.split('|');
			
			if(parseInt(result[0]) === 1){
				$('#'+field).prop('value', result[1]);
				$('#dc-attached-copy').prop('value', result[1]);
                button.text('Archivo Subido con Exito');
			}else{
                button.text('Adjuntar documentación nuevamente');
				alert(result[1]);
				this.enable();
			}
		}
	});
}

function set_tooltip(element) {
	$(element).tooltip({
		position: {
			my: "center bottom-20",
			at: "center top",
			using: function( position, feedback ) {
				$( this ).css( position );
				$( "<div>" )
					.addClass( "arrow" )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.appendTo( this );
			}
		}
	});
}

function redirect(url, delay) {
	delay = delay * 1000;
	setTimeout(function(){
		location.href = url;
	}, delay);
}