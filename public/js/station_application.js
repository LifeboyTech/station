var sidebar   = null;
var sidebar_last_li_offset = null;
var ids_to_delete = [];

$(document).ready(function() { 

	/**
     * delete button clicked. launch modal and mark for deletion
     * this will work for both list views and subpanel lists within an edit form
     *
     */
    $('.list-record-deleter').click(function(event) {

        var parent = $(this).closest('.station-list');
        var id_to_delete = $(this).closest('tr, li').attr('id');
        $('.pending-deletion').removeClass('pending-deletion');
        $('#' + id_to_delete).addClass('pending-deletion');
        $('#deleter-modal .single-item-name').html(parent.attr('data-single-item-name'));
        $('#deleter-modal .modifier').html('this');
        $('#deleter-modal').modal('show');
        event.stopPropagation();
        return false;
    });

    /**
     * delete bulk item button clicked. launch modal and mark for deletion
     * this will work for both list views only.
     *
     */
    $('body').on('click', '.bulk-record-deleter', function(event) {

        var parent = $('.station-list');
        $('.pending-deletion').removeClass('pending-deletion');

        set_ids_to_delete();

        var n_checked = ids_to_delete.length;
        $('#deleter-modal .single-item-name').html(n_checked > 1 ? parent.data('plural-item-name') : parent.data('single-item-name'));
        $('#deleter-modal .modifier').html(n_checked > 1 ? 'these ' + n_checked : 'this');
        $('#deleter-modal').modal('show');

        event.stopPropagation();
        return false;
    });

    /**
     * record deletion has been confirmed.
     * send DELETE request to panel and id route
     * works for single and bulk-delete requests
     * 
     */
    $('.deletion-confirmer').click(function(event) {
        
        $('#deleter-modal').modal('hide');

        var pending_deletion = $('.pending-deletion');
        pending_deletion.find('td').css('background-color', '#ffffcc');

        pending_deletion.css('background-color', '#ffffcc').fadeOut(900, function(){

            $('.pending-deletion').remove();
        });
        
        var relative_uri = $('.pending-deletion:first').closest('.station-list').attr('data-relative-uri');
        
        set_ids_to_delete();

        var id_string = pending_deletion.length > 1 ? ids_to_delete.join() : $('.pending-deletion').data('id');

        $.ajax({

            url: relative_uri + '/delete/' + id_string,
            type: 'DELETE',
            success: function(result) {
                
                // don't do anything. we will assume deleting was allowed and it occured on the back-end
            }
        });

        // for bulk-delete, make the tooltip go away
        $('.fixed-bottom-tooltip.for-bulk-delete').stop().animate({ 'bottom' : '-100px' });

        return false;
    });

    /**
     * drag and drop reordering of list items
     *
     */
    if ($('.is-reorderable').length) {

        $(".is-reorderable").sortable({

            revert: "invalid",
            cursor: "move",
            axis: "y",

            stop: function(event, ui) { // get the IDs in their NEW order and tell the server

                var parent          = $(ui.item[0].parentNode);
                var ids_in_order    = [];
                var panel_name      = parent.attr('data-panel-name');
                var relative_uri    = parent.attr('data-relative-uri');

                parent.find('li').map(function(){

                    ids_in_order.push($(this).attr('data-id'));
                });

                $.ajax({
                    url: relative_uri + '/reorder',
                    type: 'PUT',
                    data: { ids : ids_in_order },
                    success: function(data) {
                        

                    }
                });
            }
        });

        $( ".is-reorderable" ).disableSelection();
    }

    /**
     * panel searching
     *
     */
    $( ".station-panel-quick-finder" ).autocomplete({

        source: '/' + base_uri + 'panel/' +  curr_panel + '/search',
        minLength: 3,
        select: function( event, ui ) {
            
            if (ui.item.id != 0){
                
                window.location = '/' + base_uri + 'panel/' +  curr_panel + '/update/' + ui.item.id;
            }
        }
    });

    /**
     * boolean switches in list view
     *
     */
    $('body').on('click', '.station-list .switch', function(event) {
        
        event.stopPropagation();
        return false;
    });

    $('body').on('change', '.station-list-boolean', function(event) {
        
        var element_name        = $(this).attr('data-element-name');
        var id                  = $(this).attr('data-id');
        var put_data            = {};
        put_data[element_name]  = $(this).is(':checked') ? '1' : '0'; 

        $.ajax({
            url: $(this).closest('.station-list').attr('data-relative-uri') + '/update_element/' + element_name + '/' + id,
            type: 'PUT',
            dataType: 'json',
            data: put_data,
        })
        .done(function() {
            
            // do nothing.
        }); 
    });

    $('.station-list .switch').parent().addClass('switch-cont');

    $("[data-toggle='switch']").livequery(function(){

        if (!$(this).next().hasClass('switch-left')){

            $(this).wrap('<div class="switch" />').parent().bootstrapSwitch();
        }
    });
    
    $('body').on('click', '.station-list .checkbox', function(event) {
        
        event.stopPropagation();
        var input = $(this).find(':checkbox');

        if (input.is(':checked')) {

            input.checkbox('uncheck');

        } else {

            input.checkbox('check');
        }
        
        return false;
    });
});

$(window).load(function() {
    
    /**
     * if we had a successful flash from an update to a record, 
     * scroll to the record and animate it's background color
     * 
     * this can exist in both form and list views
     */
    $('.flash-response.dialog-success .more-edits').each(function(){
        
        var panel_name = $(this).attr('data-panel-name');
        var id = $(this).attr('data-id');
        var target_record = $("#" + panel_name + "-record-" + id);

        if (target_record.length){

            $('html, body').animate({
                scrollTop: target_record.offset().top - 75
            }, 0);

            target_record.css('background-color', '#38e1b2');
            target_record.animate({ 'background-color' : '#FFFFFF' }, 700);
        }
    });
});

function set_ids_to_delete(){

    ids_to_delete = [];

    $('.td-for-bulk-delete :checkbox:checked').each(function(index, el) {
        
        var item = $(this).closest('tr, li');
        ids_to_delete.push(item.data('id'));
        item.addClass('pending-deletion');
    });

    return ids_to_delete;
}

/**
 * sidebar behaviors
 */

    $(function() {

        sidebar   = $("#sidebar");
        sidebar_last_li_offset   = $("#sidebar li:last").offset().top;

        if (sidebar.length){

            $(window).resize(function() {
                
                audit_sidebar();
            });

            $(window).resize(function() {
                
                audit_sidebar();
            });
        }

        $('.nav-header').click(function(event) {
            
            $('.nav-header').nextUntil('.nav-header').hide();
            $(this).nextUntil('.nav-header').show();
        });

        audit_sidebar();
    });

    function audit_sidebar(){

        if (($(window).height() - 20) < sidebar_last_li_offset){

            $('.nav-header').nextUntil('.nav-header').hide();
            $('#sidebar li.active').show();
            $('#sidebar li.active').prevUntil('.nav-header').show();
            $('#sidebar li.active').nextUntil('.nav-header').show();
        
        } else {

            $('#sidebar li').show();
        }
    }

/*! Copyright (c) 2010 Brandon Aaron (http://brandonaaron.net)
 * Dual licensed under the MIT (MIT_LICENSE.txt)
 * and GPL Version 2 (GPL_LICENSE.txt) licenses.
 *
 * Version: 1.1.1
 * Requires jQuery 1.3+
 * Docs: http://docs.jquery.com/Plugins/livequery
 */
(function($){$.extend($.fn,{livequery:function(type,fn,fn2){var self=this,q;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&type==query.type&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid))return(q=query)&&false});q=q||new $.livequery(this.selector,this.context,type,fn,fn2);q.stopped=false;q.run();return this},expire:function(type,fn,fn2){var self=this;if($.isFunction(type))fn2=fn,fn=type,type=undefined;$.each($.livequery.queries,function(i,query){if(self.selector==query.selector&&self.context==query.context&&(!type||type==query.type)&&(!fn||fn.$lqguid==query.fn.$lqguid)&&(!fn2||fn2.$lqguid==query.fn2.$lqguid)&&!this.stopped)$.livequery.stop(query.id)});return this}});$.livequery=function(selector,context,type,fn,fn2){this.selector=selector;this.context=context;this.type=type;this.fn=fn;this.fn2=fn2;this.elements=[];this.stopped=false;this.id=$.livequery.queries.push(this)-1;fn.$lqguid=fn.$lqguid||$.livequery.guid++;if(fn2)fn2.$lqguid=fn2.$lqguid||$.livequery.guid++;return this};$.livequery.prototype={stop:function(){var query=this;if(this.type)this.elements.unbind(this.type,this.fn);else if(this.fn2)this.elements.each(function(i,el){query.fn2.apply(el)});this.elements=[];this.stopped=true},run:function(){if(this.stopped)return;var query=this;var oEls=this.elements,els=$(this.selector,this.context),nEls=els.not(oEls);this.elements=els;if(this.type){nEls.bind(this.type,this.fn);if(oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)$.event.remove(el,query.type,query.fn)})}else{nEls.each(function(){query.fn.apply(this)});if(this.fn2&&oEls.length>0)$.each(oEls,function(i,el){if($.inArray(el,els)<0)query.fn2.apply(el)})}}};$.extend($.livequery,{guid:0,queries:[],queue:[],running:false,timeout:null,checkQueue:function(){if($.livequery.running&&$.livequery.queue.length){var length=$.livequery.queue.length;while(length--)$.livequery.queries[$.livequery.queue.shift()].run()}},pause:function(){$.livequery.running=false},play:function(){$.livequery.running=true;$.livequery.run()},registerPlugin:function(){$.each(arguments,function(i,n){if(!$.fn[n])return;var old=$.fn[n];$.fn[n]=function(){var r=old.apply(this,arguments);$.livequery.run();return r}})},run:function(id){if(id!=undefined){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id)}else $.each($.livequery.queries,function(id){if($.inArray(id,$.livequery.queue)<0)$.livequery.queue.push(id)});if($.livequery.timeout)clearTimeout($.livequery.timeout);$.livequery.timeout=setTimeout($.livequery.checkQueue,20)},stop:function(id){if(id!=undefined)$.livequery.queries[id].stop();else $.each($.livequery.queries,function(id){$.livequery.queries[id].stop()})}});$.livequery.registerPlugin('append','prepend','after','before','wrap','attr','removeAttr','addClass','removeClass','toggleClass','empty','remove','html');$(function(){$.livequery.play()})})(jQuery);