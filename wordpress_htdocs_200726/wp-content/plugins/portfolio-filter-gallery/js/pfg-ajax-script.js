// delete single filter
function pfg_delete_filter(filter_id){
	if(confirm("Are you sure want to delete this filter?")){
		var formData = {
			'action': 'pfg_delete_filter',
			'filter_id': filter_id,
			'security': pfg_ajax_object.ajaxnonce,
		};
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: pfg_ajax_object.ajax_url,
			data: formData,
			success: function(response){
				jQuery( "#record-" + filter_id ).fadeOut( 400, "linear" );
			}
		});
	}
}

// delete all selected filters
function pfg_delete_all_filter(){
	if(confirm("Are you sure want to delete all selected filters?")){
		var AllCategories = [];
			
		//collect all selected article ids
		jQuery('input:checkbox:checked').map(function() {
			if(jQuery.isNumeric(this.value)) {
				AllCategories.push(this.value);
			}
		});
		var formData = {
			'action': 'pfg_delete_all_filter',
			'filter_ids': AllCategories,
			'security': pfg_ajax_object.ajaxnonce,
		};
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: pfg_ajax_object.ajax_url,
			data: formData,
			success: function(response){
				for (i = 0; i < AllCategories.length; i++) {
					jQuery( "#record-" + AllCategories[i] ).fadeOut( 400, "linear" );
				}
				if(AllCategories){
					jQuery( "#hide_me" ).fadeIn( 7000, "linear" );
				}
			}
		});
	}
}