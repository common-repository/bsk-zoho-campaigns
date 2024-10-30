
function bsk_campaigns_pages_select_change( object ) {
	var select_id = object.id;
	var select_name = object.name;
	var instance_prefix = select_id.replace('bsk_campaigns_top_level_page', '');
	var option_val = jQuery("#" + select_id).val();
	var option_txt = jQuery("#" + select_id + " option:selected").text();
	var container_id = instance_prefix + 'bsk_campaigns_top_level_page_added_container';
	var hidden_id = instance_prefix + 'bsk_campaigns_hidden_saved_pages';
	
	if (option_val < 1){
		return;
	}
	
	var hidden_pages_val = jQuery("#" + hidden_id).val();
	if (hidden_pages_val.length){
		hidden_pages_val = hidden_pages_val + ',' + option_val;
	}else{
		hidden_pages_val = option_val;
	}
	jQuery("#" + hidden_id).val(hidden_pages_val);
	//use ajax to get all sorted
	var data = {
		action: 'wpcpmwgetsortedpages',
		pagesid: hidden_pages_val,
		prefix: instance_prefix
	};
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#" + container_id).html(response);
	});
}

function bsk_campaigns_pages_remove(pageid, instance_prefix ) {
	var instance_prefix = instance_prefix.replace('a', '');
	var select_id = instance_prefix + 'bsk_campaigns_remove_' + pageid;
	var hidden_id = instance_prefix + 'bsk_campaigns_hidden_saved_pages';
	var container_id = instance_prefix + 'bsk_campaigns_top_level_page_added_container';
	
	var hidden_pages_val = jQuery("#" + hidden_id).val();
	var hidden_pages_array = hidden_pages_val.split(",");
	var new_val_str = '';
	
	for(var i = 0; i < hidden_pages_array.length; i++){
		if ( hidden_pages_array[i] == pageid ){
			continue;
		}
		if (hidden_pages_array[i] > 0){
			//new_val_array.push( hidden_pages_array[i] );
			new_val_str = new_val_str + hidden_pages_array[i] + ',';
		}
	}
	if (new_val_str.charAt(new_val_str.length - 1) == ','){
		new_val_str.substr(0, new_val_str.length - 1);
	}
	
	
	jQuery("#" + hidden_id).val(new_val_str);
	//use ajax to get all sorted
	var data = {
		action: 'wpcpmwgetsortedpages',
		pagesid: new_val_str,
		prefix: instance_prefix
	};
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		jQuery("#" + container_id).html(response);
	});
}
