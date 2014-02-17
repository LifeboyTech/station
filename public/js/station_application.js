var sidebar   = null;
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
    $('.bulk-record-deleter').live('click', function(event) {

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
        pending_deletion.css('background-color', '#ffffcc').fadeOut(900);
        pending_deletion.find('td').css('background-color', '#ffffcc');
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
    $('.station-list .switch').live('click', function(event) {
        
        event.stopPropagation();
        return false;
    });

    $('.station-list-boolean').live('change', function(event) {
        
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
    
    $('.station-list .checkbox').live('click', function(event) {
        
        event.stopPropagation();
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
 * sidebar follow
 */

    $(function() {

        sidebar   = $("#sidebar");

        if (sidebar.length){

            $(window).scroll(function() {
                
                audit_sidebar();
            });

            $(window).resize(function() {
                
                audit_sidebar();
            });
        }
    });

    function audit_sidebar(){

        if ($(window).scrollTop() > 125 && $(window).width() > 980) {
            sidebar.css('position', 'fixed');
            sidebar.css('top', '10px');
            sidebar.css('width', sidebar.parent().width());
        } else {
            sidebar.css('position', 'relative');
            sidebar.css('width', 'auto');
        }
    }