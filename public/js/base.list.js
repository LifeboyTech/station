var station_load_more_locked = false;
var nestable = false;
var checkboxes_locked = false;

$(document).ready(function() { 
	
	/**
	 * a click on a list row will trigger the edit button for that row
	 *
	 */
	$('body').on('click', '.station-list tr td', function(event) {
		
		var first_link = $(this).closest('tr').find('a:first');
		if (first_link.length) window.location = first_link.attr('href');
		return false;
	});

	/**
	 * pagination for station-list
	 *
	 */
	$('.station-list-load-more button').click(function(event) {
		
		if (!station_load_more_locked){

			station_load_more_locked = true;
			$(this).data('orig_html', $(this).html());
			$(this).html('loading...');

			var curr_url 	= document.location.href;
			var url_append 	= 'station_page=next';
			var target_url	= curr_url.indexOf('?') > -1 ? curr_url + '&' + url_append : curr_url + '?' + url_append;

			$.ajax({
				url: target_url,
				type: 'GET',
				dataType: 'html'
			})
			.done(function(r) {

				$('.station-list').append(r);
				station_load_more_locked = false;
				var orig_html = $('.station-list-load-more button').data('orig_html');
				$('.station-list-load-more button').html(orig_html);
			});
		}

		$(this).blur();
		return false;
	});

	/**
	 * filtering methods
	 *
	 */
	if(window.location.href.indexOf('?')>0)
	{
		// Has some filters already, so we need to set the dropdowns.
		var $url_parts = window.location.href.split('?');
		var $filters = $url_parts[1];
		var $filter_array = new Array();
		if($filters.indexOf('&')>0)
		{
			// means more than one filter, need to split!
			for(var $i=0;$i<$filters.length;$i++)
			{
				$filter_array[$i] = $filters[$i].split('=');
			}
		}
		else
		{
			// just one filter
			$filter_array[0] = $filters.split('=');
		}


		for(var $i = 0; $i < $filter_array.length;$i++)
		{
			if (typeof $filter_array[$i][0] != 'undefined' && typeof $filter_array[$i][1] != 'undefined'){

				$('[name=filter-'+$filter_array[$i][0]+']').val($filter_array[$i][1]);
			}
		}
	}

	/**
	 * user filtering of data
	 */
	$('body').on('change', '.station-list .table-filter', function()
	{
		var $get_data = new Array();
		var $url_without_hash = window.location.href.split('#')[0];
		var $url_parts = $url_without_hash.split('?');
		var $target_url = $url_parts[0];

		//$get_data.push(['tags',4]); testing for more than one filter
		$('.station-list .table-filter').each(function()
		{

			//add to the string
			var $table = $(this).attr('name').split('-');
			$table = $table[1];
			$get_data.push([$table,$(this).val()]);
		});

		if($get_data.length>0)
		{
			// have filters to add!
			var $get = '?';

			for(var $i = 0;$i < $get_data.length;$i++)
			{
				$get += $get_data[$i][0]+'='+$get_data[$i][1]+'&';
			}
			$target_url += $get.substring(0,($get.length-1));
		}

		window.location = $target_url;
	});

	$('.station-list .row-link').click(function(event) {
		
		event.stopPropagation();
	});

	// enable harvest/chosen JS lib on selects
    if ($(".chosen-select").length){

        $(".chosen-select").chosen({
            disable_search_threshold: 10,
            allow_single_deselect: true
        });
    }

    /**
     * nested sortable behaviors
     */
    if ($('.is-nestable').length) {

    	nestable = $('.is-nestable .dd');
	    nestable.nestable();
	    nestable.on('change', function(){

	    	var relative_uri = nestable.closest('.station-list').attr('data-relative-uri');
	    	var ids = JSON.stringify($('.is-nestable .dd').nestable('serialize'));

	    	$.ajax({
                url: relative_uri + '/reorder_nested',
                type: 'PUT',
                data: { nested_ids : ids },
                success: function(data) {
                    
                	// assume all went well for now. TODO: handle error.
                }
            });
	    });

	    $('.is-nestable .fui-new').click(function(event) {
	    	
	    	window.location = $(this).attr('data-link');
	    	return false;
	    });

	    $('body').on('click', 'a.nestables-collapse-all', function(event) {
	    	
	    	$('.dd').nestable('collapseAll');
	    	$('.nestables-collapse-all, .nestables-expand-all').toggle();
	    });

	    $('body').on('click', 'a.nestables-expand-all', function(event) {
	    	
	    	$('.dd').nestable('expandAll');
	    	$('.nestables-collapse-all, .nestables-expand-all').toggle();
	    });

	    if ($('.dd-item .dd-item').length > 75 && !$('.flash-response.dialog-success .more-edits').length){

	    	$('.dd').nestable('collapseAll');
	    	$('.nestables-expand-all').show();
	    	$('.nestables-collapse-all').hide();
	    
	    } else {

	    	$('.nestables-expand-all').hide();
	    	$('.nestables-collapse-all').show();
	    }

	    $('.dd-handle span a').each(function(index, el) {
	    	
	    	var parent_id = $(this).closest('li').attr('id');
	    	$(this).addClass('virtual-field').appendTo('#' + parent_id);
	    });
	}

	/**
	 * lists of foreign data items in row
	 */
	$('.st-it-more').click(function(event) {
		
		$(this).parent().find('span.st-it').show();
		$(this).remove();
		return false;
	});

	/**
	 * inline list links w/ outbound targets
	 */
	$('.station-list td a[target="_blank"]').click(function(event) {
		
		event.stopPropagation();
		window.open($(this).attr('href'));
		return false;
	});

	/**
	 * checkbox behaviors
	 */
	$('.bulk-check-all').click(function(event) {
		
		var toggle_all = $(this).find(':checkbox');

		if (!toggle_all.is(':checked')){

			checkboxes_locked = true;
			$('.row-checkbox :checkbox').checkbox('check');
			toggle_all.checkbox('uncheck');
			audit_checkboxes();
			checkboxes_locked = false;

		} else {

			checkboxes_locked = true;
			$('.row-checkbox :checkbox').checkbox('uncheck');
			toggle_all.checkbox('check');
			audit_checkboxes();
			checkboxes_locked = false;
		}

	});

	$('body').on('change', '.row-checkbox :checkbox', function(event) {
		
		audit_checkboxes();
		audit_bulk_delete_tooltip();
	});
});

function audit_checkboxes(){

	var checkboxes = $('.row-checkbox :checkbox');
	var n_checked = checkboxes.filter(':checked').length;

	if (!checkboxes_locked){

	    var checkAll = checkboxes.length == n_checked;
	    $('.bulk-check-all :checkbox').checkbox(checkAll ? 'check' : 'uncheck');
	}
}

function audit_bulk_delete_tooltip(){

	var n_checked = $('.row-checkbox :checkbox:checked').length;

	if (n_checked > 0){

		var url					= '';
		var plural_item_name	= $('.station-list').data('plural-item-name');
		var single_item_name	= $('.station-list').data('single-item-name');
		var item_names			= n_checked > 1 ? plural_item_name : single_item_name;
		var html 				= '<a href="" class="btn btn-xl btn-danger btn-block bulk-record-deleter" data-target="#deleter-modal" data-toggle="modal">'
								+ '<i class="fui-cross pull-left"></i> Delete ' + n_checked + ' Checked ' + item_names + '</a>';

		$('.fixed-bottom-tooltip.for-bulk-delete').stop().animate({ 'bottom' : '19px' }).find('.tooltip-inner').html(html);
	
	} else {

		$('.fixed-bottom-tooltip.for-bulk-delete').stop().animate({ 'bottom' : '-100px' });
	}
}

/**
 * minified version of https://github.com/douglascrockford/JSON-js/blob/master/json2.js
 */
	if(typeof JSON!=='object'){JSON={}}(function(){'use strict';function f(n){return n<10?'0'+n:n}if(typeof Date.prototype.toJSON!=='function'){Date.prototype.toJSON=function(){return isFinite(this.valueOf())?this.getUTCFullYear()+'-'+f(this.getUTCMonth()+1)+'-'+f(this.getUTCDate())+'T'+f(this.getUTCHours())+':'+f(this.getUTCMinutes())+':'+f(this.getUTCSeconds())+'Z':null};String.prototype.toJSON=Number.prototype.toJSON=Boolean.prototype.toJSON=function(){return this.valueOf()}}var cx=/[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,escapable=/[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,gap,indent,meta={'\b':'\\b','\t':'\\t','\n':'\\n','\f':'\\f','\r':'\\r','"':'\\"','\\':'\\\\'},rep;function quote(string){escapable.lastIndex=0;return escapable.test(string)?'"'+string.replace(escapable,function(a){var c=meta[a];return typeof c==='string'?c:'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4)})+'"':'"'+string+'"'}function str(key,holder){var i,k,v,length,mind=gap,partial,value=holder[key];if(value&&typeof value==='object'&&typeof value.toJSON==='function'){value=value.toJSON(key)}if(typeof rep==='function'){value=rep.call(holder,key,value)}switch(typeof value){case'string':return quote(value);case'number':return isFinite(value)?String(value):'null';case'boolean':case'null':return String(value);case'object':if(!value){return'null'}gap+=indent;partial=[];if(Object.prototype.toString.apply(value)==='[object Array]'){length=value.length;for(i=0;i<length;i+=1){partial[i]=str(i,value)||'null'}v=partial.length===0?'[]':gap?'[\n'+gap+partial.join(',\n'+gap)+'\n'+mind+']':'['+partial.join(',')+']';gap=mind;return v}if(rep&&typeof rep==='object'){length=rep.length;for(i=0;i<length;i+=1){if(typeof rep[i]==='string'){k=rep[i];v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}else{for(k in value){if(Object.prototype.hasOwnProperty.call(value,k)){v=str(k,value);if(v){partial.push(quote(k)+(gap?': ':':')+v)}}}}v=partial.length===0?'{}':gap?'{\n'+gap+partial.join(',\n'+gap)+'\n'+mind+'}':'{'+partial.join(',')+'}';gap=mind;return v}}if(typeof JSON.stringify!=='function'){JSON.stringify=function(value,replacer,space){var i;gap='';indent='';if(typeof space==='number'){for(i=0;i<space;i+=1){indent+=' '}}else if(typeof space==='string'){indent=space}rep=replacer;if(replacer&&typeof replacer!=='function'&&(typeof replacer!=='object'||typeof replacer.length!=='number')){throw new Error('JSON.stringify')}return str('',{'':value})}}if(typeof JSON.parse!=='function'){JSON.parse=function(text,reviver){var j;function walk(holder,key){var k,v,value=holder[key];if(value&&typeof value==='object'){for(k in value){if(Object.prototype.hasOwnProperty.call(value,k)){v=walk(value,k);if(v!==undefined){value[k]=v}else{delete value[k]}}}}return reviver.call(holder,key,value)}text=String(text);cx.lastIndex=0;if(cx.test(text)){text=text.replace(cx,function(a){return'\\u'+('0000'+a.charCodeAt(0).toString(16)).slice(-4)})}if(/^[\],:{}\s]*$/.test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,'@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,']').replace(/(?:^|:|,)(?:\s*\[)+/g,''))){j=eval('('+text+')');return typeof reviver==='function'?walk({'':j},''):j}throw new SyntaxError('JSON.parse')}}}());