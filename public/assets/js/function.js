jQuery(function($){

	var func = {

		"inputDate" : function(){

			$.datepicker.setDefaults({
				dateFormat:'yy-mm-dd'
			});
			$("#item_time").datepicker();

		}

	};

	func.inputDate();
});