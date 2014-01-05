var sidebar   = null;

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
        $('#deleter-modal').modal('show');
        event.stopPropagation();
        return false;
    });

    /**
     * record deletion has been confirmed.
     * send DELETE request to panel and id route
     * 
     */
    $('.deletion-confirmer').click(function(event) {
        
        $('#deleter-modal').modal('hide');
        var pending_deletion = $('.pending-deletion');
        pending_deletion.css('background-color', '#ffffcc').fadeOut(900);
        pending_deletion.find('td').css('background-color', '#ffffcc');
        var relative_uri = $('.pending-deletion').closest('.station-list').attr('data-relative-uri');

        $.ajax({

            url: relative_uri + '/delete/' + $('.pending-deletion').attr('data-id'),
            type: 'DELETE',
            success: function(result) {
                
                // don't do anything. we will assume deleting was allowed and occured
            }
        });

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

        if ($(window).scrollTop() > 65 && $(window).width() > 980) {
            sidebar.css('position', 'fixed');
            sidebar.css('top', '10px');
            sidebar.css('width', sidebar.parent().width());
        } else {
            sidebar.css('position', 'relative');
            sidebar.css('width', 'auto');
        }
    }