jQuery(function($){

	var fx = {
		"autoWidthHeight" : function(){

			var width_content = $("#content").css("width").replace("px","");
			var margin_content = $("#content").css("margin-top").replace("px","");
			var width_real = width_content - margin_content;
			$("ul").each(function(i){
				var str = $(this).text();
				var length = str.length;
				$(this).width(length*12>width_real? width_real:length*12);
				$(this).height(length*1.2);
			});
		}
	}

	fx.autoWidthHeight();

});