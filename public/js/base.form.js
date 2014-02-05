var manual_crop_is_pending = false;
var curr_crop_coords       = null;
var curr_crop_name         = null;
var curr_crop_element      = null;
var curr_crop_filename     = null;
var jcrop_api              = null;

$(document).ready(function() { 
    
    // give some weights & behaviors to the first few form fields
    var primary_element = $('.station-form input[type="text"]:first').not('.for-custom');
    var primary_element_val = primary_element.val();
    primary_element.addClass('input-hg').focus().val('').val(primary_element_val);

    $('.station-form input[type="text"]:eq(1), .station-form input[type="password"]:eq(0)').not('.for-custom').addClass('input-lg');

    // enable harvest/chosen JS lib on selects
    if ($(".chosen-select").length){

        $(".chosen-select[multiple='multiple']").prepend('<option value=""></option>');

        $(".chosen-select").chosen({
            disable_search_threshold: 10,
            allow_single_deselect: true
        });
    }

    // make radio buttons work with flatUI 
    $('.station-form input[type="radio"]').each(function () {

        $(this).attr('data-toggle', 'radio');
        $(this).radio();
    });

    // use masking plugin (below) to force some formatting
    $(".station-form input.phone").mask("(999) 999-9999? x99999");
    $(".station-form input.money").maskMoney({thousands:'', decimal:'.'});

    // make date fields use datetime plugin 
    $('input.for-date').each(function(){

        var alt_field = '#' + $(this).attr('name');

        $(this).datetimeEntry({
                    
            datetimeFormat:     $(this).hasClass('with-time') ? 'N d Y H:M a' : 'N d Y', 
            altField:           alt_field, 
            altFormat:          $(this).hasClass('with-time') ? 'Y-O-D H:M:S' : 'Y-O-D',
            ampmPrefix:         ' ',
            spinnerSize:        [0,0,0],
            datetimeSeparators: ' ',
            useMouseWheel:      false
        });

        var starting_val = $(alt_field).val() == '' ? new Date() : mysqlTimeStampToDate($(alt_field).val());
        $(this).datetimeEntry('setDatetime', starting_val);

        $('.datetimeEntry_control').hide();
    });

    // enable date picker for date fields:
    $('input.for-date').each(function(){

        // jQuery UI Datepicker JS init
        var datepickerSelector = '#' + $(this).attr('id');
        $(datepickerSelector).datepicker({
          showOtherMonths: true,
          selectOtherMonths: true,
          dateFormat: "MM d yy",
          altField: '#' + $(this).attr('name'),
          altFormat: 'yy-mm-dd'
        });

        // Now let's align datepicker with the prepend button
        $(datepickerSelector).datepicker('widget').css({'margin-left': -$(datepickerSelector).prev('span').outerWidth()});
    });

    // enable spinners
    $('.station-form input.spinner').customspinner({
      min: 0,
      max: 9999
        }).on('focus', function () {
          $(this).closest('.ui-spinner').addClass('focus');
        }).on('blur', function () {
          $(this).closest('.ui-spinner').removeClass('focus');
    });

    /**
     * media modal stuff!
     */
    $('#mediaTab a').live('click',function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $('.station-media').live('click',function()
    {
        var $type = $(this).attr('id').split('_');
        //$('#mediaTab').tab();
        $('#mediaTab a[href="#'+$type[0]+'-tab"]').tab('show');
        //var $elem_name = $(this).parent().next().attr('class').replace('target-','','gi');
        var $elem_name = $(this).closest('.station-file-upload-wrap').find('.station-img-thumbnail:first').attr('id').replace('target-','','gi');
        var $label = $('[for="station-'+$elem_name+'"]').html();
        $('#mediaModal [name="upload_element_name"]').val($elem_name);
        $('#mediaModal .modal-title').html($label);
        $('.station-file-options').hide();

        $('.trigger_img_upload').html('Upload File').show();



        $('#mediaModal').modal();

        if($type[0]=='upload') $('[name="uploaded_file"]').click();
        if($type[0]!='edit') $('#station-fileupload-hud').html('');
        if($type[0]=='edit')
        {
            // update upload button
            $('.trigger_img_upload').html('Upload Different File');

            //display image
            var $parts = $('#target-'+$elem_name).attr('src').split('/station_thumbs_sm/');
            $('#station-fileupload-hud').html('<img src="'+$parts[0]+'/station_thumbs_lg/'+$parts[1]+'">');

            // loading text for controls
            $('.station-file-options').html('<h5><span class="label label-primary">Loading...</span></h5>').show();

            // call func to gen tool buttons
            create_media_side_controls($parts[0],$parts[1],$elem_name);

            $('#mediaTab a:first').click();
        }
    });

    $('.img-hidden-input').each(function()
    {
        var $elem_name = $(this).attr('name');
        //console.log($elem_name);
        var $img_hidden_val = $(this).val();
        if($img_hidden_val!='')
        {
            $('#target-'+$elem_name).attr('src','http://'+$('#target-'+$elem_name).attr('bucket')+'.s3.amazonaws.com/station_thumbs_sm/'+$img_hidden_val);
            $('#edit_for_' + $elem_name + ', #remove_for_' + $elem_name).show();
        }
    });

    $('.trigger_img_upload').live('click',function()
    {
        $('[name="uploaded_file"]').click();
    });

    $('[name="uploaded_file"]').change(function()
    {
        //$('[name=postiframe]').remove();
        $('.trigger_img_upload').html('Processing....').attr('disabled','disabled');
        var $iframe = $('<iframe name="postiframe" id="postiframe" style="display: none" />');
        $("body").append($iframe);


        $('#station-fileupload-form').submit();

        $("#postiframe").load(function () {
                $iframeContents = $("#postiframe")[0].contentWindow.document.body.innerHTML;
                // console.log($iframeContents);
                $results = $.parseJSON($iframeContents);
                if ($results.success) 
                {
                    // console.log('success! ');
                    $('.trigger_img_upload').html('Upload Different File').removeAttr('disabled');
                    $('.station-file-options').show();
                    if(typeof $results.file_uri!='undefined')
                    {
                        // we display the thumb
                        $('#station-fileupload-hud').html('<img src="'+$results.file_uri_stub+'station_thumbs_lg/'+$results.file_name+'">');

                        //populate sidebar info
                        var $elem_name = $('#mediaModal [name="upload_element_name"]').val();

                        create_media_side_controls($results.file_uri_stub,$results.file_name,$elem_name);
                        $(this).remove();


                    }
                    
                }
                else
                {
                    var $errors = response.error_list;
                    // // console.log($errors);
                    var $displayme = '<div class="alert alert-danger">'+$errors+'</div>';
                    //// // console.log($displayme);
                    $('#station-fileupload-hud').html($displayme);
                    scroll(0,0);


                }
        });
    });

    $('.file-upload-save').live('click',function()
    {
        // Display on the main form
        var $elem_name = $('#mediaModal [name="upload_element_name"]').val();

        // we need the img filename and the uri
        var $parts = $('#station-fileupload-hud').children(':first').attr('src').split('/station_thumbs_lg/');
        $('#target-'+$elem_name).attr('src',$parts[0]+'/station_thumbs_sm/'+$parts[1]).show();
        $('#edit_for_' + $elem_name + ', #remove_for_' + $elem_name).show();

        // pop in for hidden element on main form
        $('[name='+$elem_name+']').val($parts[1]);
    });

    $('.file-remover').live('click', function(event) {
        
        var parent = $(this).closest('.station-element-group');
        var element_name = parent.attr('data-element-name');
        $('input[name="' + element_name + '"]').val('');
        parent.find('#edit_for_' + element_name + ', #remove_for_' + element_name).hide();
        parent.find('#target-' + element_name).attr('src', '/packages/canary/station/img/file-placeholder.png');
        return false;
    });

    $('.station-img-thumbnail').live('click', function(event) {
        
        var parent = $(this).closest('.station-file-upload-wrap');
        parent.find('button:visible:first').click();
        return false;
    });
    
    /**
     * manual cropping process using Jcrop
     *
     */
    $('.station-crop-start').live('click', function(event) {
        
        if (manual_crop_is_pending){

            stop_crop_process();
        }

        if ($('#station-fileupload-hud img').length){

            manual_crop_is_pending = true;
            var crop_group         = $(this).closest('.station-crop-size-group');
            var size_string        = crop_group.attr('data-size');
            var size_name          = crop_group.attr('data-size-name');
            curr_crop_name         = size_name;
            curr_crop_element      = crop_group.attr('data-element-name');
            curr_crop_filename     = crop_group.attr('data-file-name');

            $('#station-fileupload-hud img').Jcrop(jcrop_options_for_size(size_string), function(){
                jcrop_api = this;
            });

            $(this).removeClass('btn-primary').addClass('btn-warning');
            $('.station-crop-start').removeClass('btn-primary').addClass('btn-default');

            var crop_saver_html = 

                '<div class="row station-crop-decider">'
                    + '<button type="button" class="pull-right station-crop-saver btn btn-primary btn-sm">Save Crop</button>'
                    + '<button type="button" class="pull-right station-crop-canceler btn btn-default btn-sm">Cancel</button>'
                + '</div>';

            $('#station-fileupload-hud').after(crop_saver_html);
            $('.modal-footer, .trigger_img_upload').hide();
        }

        return false;
    });

    $('.station-crop-saver').live('click', function(event) {
        
        $.ajax({
          url: '/' + base_uri + 'file/crop/',
          type: 'PUT',
          data: { 
            panel_name    : curr_panel, 
            element_name  : curr_crop_element, 
            method        : curr_method,
            filename      : curr_crop_filename,
            coords        : curr_crop_coords,
            size_name     : curr_crop_name
          },

          success: function(data) {
            
          }
        });

        stop_crop_process();
        return false;
    });

    $('.station-crop-canceler').live('click', function(event) {
        
        stop_crop_process();
        return false;
    });

    /**
     * sub-panel methods
     */
    $('.sub-panel-adder').click(function(event) {
        
        $('.sub-panel-adder').removeClass('active');
        $(this).addClass('active');
        $(this).blur().find('span:last').html('loading...');
        submit_form().done(handle_submitted_form_for_sub_panel);
        return false;
    });

    $('.markdown-helper').click(function(event) {
        
        $('#markdown-helper-modal').modal();
    });

    /**
     * URL fetching
     */
    $('.url-fetch-target').change(function(event) {
        
        $('.fetch-status').remove();
        $(this).closest('.input-group').find('.fetcher').append('<span class="fetch-status"> loading...</span>');
        var element_name = $(this).attr('data-element');

        $.ajax({

            url: '/' + base_uri + 'panel/' + curr_panel + '/process_url/' + element_name,
            type: 'PUT',
            dataType: 'json',
            data: {url: $(this).val()},
        })
        .done(function(r) {
            
            $('.fetch-status').remove();
            disperse_fetched_url_parts(r, element_name);
            set_fetched_url_data(element_name);
        });
    });

    $('.url-fetch-target').each(function(index, el) {
        
        $(this).keypress(function(event) { return event.keyCode != 13; });
        var element_name = $(this).attr('data-element');
        set_fetched_url_data(element_name);
    });

    $('.url-fetch-target').click(function(event) {
        
        $(this).select();
    });

    $('.fetcher').click(function(event) {
        
        $(this).closest('.input-group').find('.url-fetch-target').change();
    });
});

/**
 * for url fetching
 */
    function disperse_fetched_url_parts(parts, element_name){

        eval('var mapping = ' + $('.parsed-results[data-element="' + element_name + '"]').attr('data-mapping'));
        
        $.each(mapping, function(index, val) {
            
            if (typeof parts.graph != 'undefined' && typeof parts.graph[index] != 'undefined') {

                $('input[name="' + val + '"]').val(parts.graph[index]);
            
            } else {

                $('input[name="' + val + '"]').val('');
            }

            if (index == 'image'){

                var thumbnail = $('.station-element-group[data-element-name="' + element_name + '"] .station-img-thumbnail');

                if (typeof parts.graph != 'undefined' && typeof parts.graph[index] != 'undefined') {

                    var src = 'http://' + thumbnail.attr('bucket') + '.s3.amazonaws.com/station_thumbs_sm/' + parts.graph[index];
                    
                } else {

                    var src = '/packages/canary/station/img/file-placeholder.png';
                }

                thumbnail.attr('src', src);
            }
        });
    }

    function set_fetched_url_data(element_name){

        eval('var mapping = ' + $('.parsed-results[data-element="' + element_name + '"]').attr('data-mapping'));

        $.each(mapping, function(index, val) {

            var parent = $('.station-element-group[data-element-name="' + element_name + '"]');
            var field_value = $('input[name="' + val + '"]').val();

            if (index == 'title'){

                parent.find('.parsed-results').html('<span class="label label-default">' + field_value + '</span>');
            }

            if (index == 'url'){

                parent.find('.url-fetch-target').val(field_value);

                if (field_value == ''){

                    parent.find('.station-file-upload-controls').hide();
                
                } else {

                    parent.find('.station-file-upload-controls').show();
                }
            }
        });
    }

/**
 * ajax form handling
 */

    function handle_submitted_form_for_sub_panel(r){

        var curr_sub_panel_adder = $('.sub-panel-adder.active');

        if (r.status != 1){ // failed to add or update parent panel record

            var single_item_name = curr_sub_panel_adder.attr('data-single-item-name');
            var initial_label = curr_sub_panel_adder.attr('data-initial-label');

            $('.flash-response, .alert').hide(); // hide any other flashes on the page first
            $('.empty-flash-holder').html(r.flash).show();
            $('.empty-flash-holder b:first').prepend('Before you can add a new ' + single_item_name.toLowerCase() + ', ');
            $('html, body').animate({ scrollTop: 0 }, 200); // scroll to top
            curr_sub_panel_adder.find('span:last').html(initial_label);
        
        } else { // we successfully added / updated the parent record for the sub panel, let's redirect to the child 

            var sub_panel_name = curr_sub_panel_adder.attr('data-panel-name');
            window.location = '/' + base_uri + 'panel/' + sub_panel_name + '/create/for/' + curr_panel + '/' + r.record_id;
        }
    }

    function submit_form(){

        var form   = $('.station-form');
        var method = form.attr('method');
        var action = form.attr('action');

        return $.ajax({
            url: action,
            type: method,
            data: form.serialize(),
            dataType: 'json'
        });
    }

/**
 * cropping related methods
 *
 * @param  type  $param
 * @return void
 */

    function set_crop_coords(c){
        
        curr_crop_coords = c; // format is: c.x, c.y, c.x2, c.y2, c.w, c.h
    }

    function stop_crop_process(){

        jcrop_api.destroy();
        $('.station-crop-decider').remove();
        $('.station-crop-start').removeClass('btn-default').removeClass('btn-warning').addClass('btn-primary');
        $('.modal-footer, .trigger_img_upload').show();
        manual_crop_is_pending = false;
    }

    function jcrop_options_for_size(size_string){

        var size_arr = size_string.split('x');
        var x        = parseInt(size_arr[0]);
        var y        = parseInt(size_arr[1]);
        var padding  = 10;

        if (x == 0 || y == 0){ // not a fixed width

            return {

                onSelect: set_crop_coords, 
                setSelect: [padding, padding, 100 + padding, 100 + padding] 
            }

        } else {

            var forced_height = 100; // let's keep it small in case we have a short and wide image
            var x2 = ((x * forced_height) / y) + padding; // find the width based on our aspect ratio and forced height 
            var y2 = forced_height + padding;

            return {
             
                onSelect: set_crop_coords, 
                setSelect: [10, 10, x2, y2],
                aspectRatio: x / y
            }
        }
    }

/**
 * for uploading
 */

    
    function create_media_side_controls($stub,$filename,$elem_name)
    {
        // now need to dynamically make the column of size buttons on modal and in img form
        var $img_sizes = $.parseJSON($('[name=img_sizes_array]').val());
        var $this_img_sizes = Array();
        //$this_img_sizes = $img_sizes.standard;

        // keep it simple. if we have image sizes for this element, use them...
        if (typeof $img_sizes[$elem_name] != 'undefined'){

            for(i in $img_sizes[$elem_name])
            {
                $this_img_sizes.push([i,$img_sizes[$elem_name][i].label,$img_sizes[$elem_name][i].size]);
            }
        
        } else { // if we don't have image sizes for this element, use the standards. no overrides.

            for(i in $img_sizes.standard)
            {
                $this_img_sizes.push([i,$img_sizes.standard[i].label,$img_sizes.standard[i].size]);
            }
        }

        // good lord, now we can set the hidden element to the img upload form
        $('[name=img_sizes]').val(JSON.stringify($this_img_sizes));

        // now we need to create the buttons in sidenav for resizing
        var $button_html = '';
        for(var $j=0;$j<$this_img_sizes.length;$j++)
        {
            var has_size = typeof $this_img_sizes[$j][2] != 'undefined';
            var size_class = !has_size ? 'w-no-size' : '';

            $button_html +=

                '<div class="btn-group station-crop-size-group ' + size_class + '" data-file-name="' + $filename + '" '
                        + 'data-element-name="' + $elem_name + '" data-size-name="' + $this_img_sizes[$j][0] + '" data-size="' + $this_img_sizes[$j][2] + '">\n'
                    + '<button type="button" class="btn btn-xs btn-primary station-crop-start" id="station-filecrop-'+$this_img_sizes[$j][0]+'">'
                        + '<span class="glyphicon glyphicon-pencil"></span>'
                    + '</button>\n'
                    + '<a target="_blank" href="' + $stub + '/' + $this_img_sizes[$j][0] + '/' + $filename + '" class="btn btn-xs btn-default" '
                            + 'id="station-fileview-'+ $this_img_sizes[$j][0] + '">'
                        + '<span class="glyphicon glyphicon-eye-open img-crop-version-eyeball">&nbsp;</span> '
                        + '<span class="img-crop-version-label">' + $this_img_sizes[$j][1] + '</span>'
                    + '</a>\n'
                + '</div>\n';        
        }   

        $('.station-file-options').html($button_html);

        $('.btn-group.w-no-size .station-crop-start').remove();
        //console.log($this_img_sizes);
        //console.log($img_sizes);
    }

/**
 * misc 
 */
    function mysqlTimeStampToDate(timestamp) {
        
        if (timestamp == '0000-00-00'){

            return '';
        }

        if (timestamp.length > 10){

            var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
            var parts=timestamp.replace(regex,"$1 $2 $3 $4 $5 $6").split(' ');

            return new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
        
        } else {
            
            var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) ?$/;
            var parts=timestamp.replace(regex,"$1 $2 $3").split(' ');
            return new Date(parts[0],parts[1]-1,parts[2]);
        }
    }

/*
    Masked Input plugin for jQuery
    Copyright (c) 2007-2013 Josh Bush (digitalbush.com)
    Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license)
    Version: 1.3.1

    // Used when element format = 'phone' // TODO: break out into conditional load.
*/
    (function(e){function t(){var e=document.createElement("input"),t="onpaste";return e.setAttribute(t,""),"function"==typeof e[t]?"paste":"input"}var n,a=t()+".mask",r=navigator.userAgent,i=/iphone/i.test(r),o=/android/i.test(r);e.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},dataName:"rawMaskFn",placeholder:"_"},e.fn.extend({caret:function(e,t){var n;if(0!==this.length&&!this.is(":hidden"))return"number"==typeof e?(t="number"==typeof t?t:e,this.each(function(){this.setSelectionRange?this.setSelectionRange(e,t):this.createTextRange&&(n=this.createTextRange(),n.collapse(!0),n.moveEnd("character",t),n.moveStart("character",e),n.select())})):(this[0].setSelectionRange?(e=this[0].selectionStart,t=this[0].selectionEnd):document.selection&&document.selection.createRange&&(n=document.selection.createRange(),e=0-n.duplicate().moveStart("character",-1e5),t=e+n.text.length),{begin:e,end:t})},unmask:function(){return this.trigger("unmask")},mask:function(t,r){var c,l,s,u,f,h;return!t&&this.length>0?(c=e(this[0]),c.data(e.mask.dataName)()):(r=e.extend({placeholder:e.mask.placeholder,completed:null},r),l=e.mask.definitions,s=[],u=h=t.length,f=null,e.each(t.split(""),function(e,t){"?"==t?(h--,u=e):l[t]?(s.push(RegExp(l[t])),null===f&&(f=s.length-1)):s.push(null)}),this.trigger("unmask").each(function(){function c(e){for(;h>++e&&!s[e];);return e}function d(e){for(;--e>=0&&!s[e];);return e}function m(e,t){var n,a;if(!(0>e)){for(n=e,a=c(t);h>n;n++)if(s[n]){if(!(h>a&&s[n].test(R[a])))break;R[n]=R[a],R[a]=r.placeholder,a=c(a)}b(),x.caret(Math.max(f,e))}}function p(e){var t,n,a,i;for(t=e,n=r.placeholder;h>t;t++)if(s[t]){if(a=c(t),i=R[t],R[t]=n,!(h>a&&s[a].test(i)))break;n=i}}function g(e){var t,n,a,r=e.which;8===r||46===r||i&&127===r?(t=x.caret(),n=t.begin,a=t.end,0===a-n&&(n=46!==r?d(n):a=c(n-1),a=46===r?c(a):a),k(n,a),m(n,a-1),e.preventDefault()):27==r&&(x.val(S),x.caret(0,y()),e.preventDefault())}function v(t){var n,a,i,l=t.which,u=x.caret();t.ctrlKey||t.altKey||t.metaKey||32>l||l&&(0!==u.end-u.begin&&(k(u.begin,u.end),m(u.begin,u.end-1)),n=c(u.begin-1),h>n&&(a=String.fromCharCode(l),s[n].test(a)&&(p(n),R[n]=a,b(),i=c(n),o?setTimeout(e.proxy(e.fn.caret,x,i),0):x.caret(i),r.completed&&i>=h&&r.completed.call(x))),t.preventDefault())}function k(e,t){var n;for(n=e;t>n&&h>n;n++)s[n]&&(R[n]=r.placeholder)}function b(){x.val(R.join(""))}function y(e){var t,n,a=x.val(),i=-1;for(t=0,pos=0;h>t;t++)if(s[t]){for(R[t]=r.placeholder;pos++<a.length;)if(n=a.charAt(pos-1),s[t].test(n)){R[t]=n,i=t;break}if(pos>a.length)break}else R[t]===a.charAt(pos)&&t!==u&&(pos++,i=t);return e?b():u>i+1?(x.val(""),k(0,h)):(b(),x.val(x.val().substring(0,i+1))),u?t:f}var x=e(this),R=e.map(t.split(""),function(e){return"?"!=e?l[e]?r.placeholder:e:void 0}),S=x.val();x.data(e.mask.dataName,function(){return e.map(R,function(e,t){return s[t]&&e!=r.placeholder?e:null}).join("")}),x.attr("readonly")||x.one("unmask",function(){x.unbind(".mask").removeData(e.mask.dataName)}).bind("focus.mask",function(){clearTimeout(n);var e;S=x.val(),e=y(),n=setTimeout(function(){b(),e==t.length?x.caret(0,e):x.caret(e)},10)}).bind("blur.mask",function(){y(),x.val()!=S&&x.change()}).bind("keydown.mask",g).bind("keypress.mask",v).bind(a,function(){setTimeout(function(){var e=y(!0);x.caret(e),r.completed&&e==x.val().length&&r.completed.call(x)},0)}),y()}))}})})(jQuery);

/*
    * maskMoney plugin for jQuery
    * http://plentz.github.com/jquery-maskmoney/
    * version: 2.1.2
    * Licensed under the MIT license

    // Used when element format = 'money' // TODO: break out into conditional load.
*/
    (function(e){if(!e.browser){e.browser={};e.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());e.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());e.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());e.browser.msie=/msie/.test(navigator.userAgent.toLowerCase())}var t={destroy:function(){var t=e(this);t.unbind(".maskMoney");if(e.browser.msie){this.onpaste=null}return this},mask:function(){return this.trigger("mask")},init:function(t){t=e.extend({symbol:"",symbolStay:false,thousands:",",decimal:".",precision:2,defaultZero:true,allowZero:false,allowNegative:false},t);return this.each(function(){function i(){r=true}function s(){r=false}function o(t){t=t||window.event;var o=t.which||t.charCode||t.keyCode;if(o==undefined)return false;if(o<48||o>57){if(o==45){i();n.val(g(n));return false}else if(o==43){i();n.val(n.val().replace("-",""));return false}else if(o==13||o==9){if(r){s();e(this).change()}return true}else if(e.browser.mozilla&&(o==37||o==39)&&t.charCode==0){return true}else{c(t);return true}}else if(u(n)){return false}else{c(t);var a=String.fromCharCode(o);var f=n.get(0);var l=b(f);var p=l.start;var d=l.end;f.value=f.value.substring(0,p)+a+f.value.substring(d,f.value.length);h(f,p+1);i();return false}}function u(e){var t=e.val().length>=e.attr("maxlength")&&e.attr("maxlength")>=0;var n=b(e.get(0));var r=n.start;var i=n.end;var s=n.start!=n.end&&e.val().substring(r,i).match(/\d/)?true:false;return t&&!s}function a(t){t=t||window.event;var o=t.which||t.charCode||t.keyCode;if(o==undefined)return false;var u=n.get(0);var a=b(u);var f=a.start;var l=a.end;if(o==8){c(t);if(f==l){u.value=u.value.substring(0,f-1)+u.value.substring(l,u.value.length);f=f-1}else{u.value=u.value.substring(0,f)+u.value.substring(l,u.value.length)}h(u,f);i();return false}else if(o==9){if(r){e(this).change();s()}return true}else if(o==46||o==63272){c(t);if(u.selectionStart==u.selectionEnd){u.value=u.value.substring(0,f)+u.value.substring(l+1,u.value.length)}else{u.value=u.value.substring(0,f)+u.value.substring(l,u.value.length)}h(u,f);i();return false}else{return true}}function f(e){var r=v();if(n.val()==r){n.val("")}else if(n.val()==""&&t.defaultZero){n.val(m(r))}else{n.val(m(n.val()))}if(this.createTextRange){var i=this.createTextRange();i.collapse(false);i.select()}}function l(r){if(e.browser.msie){o(r)}if(n.val()==""||n.val()==m(v())||n.val()==t.symbol){if(!t.allowZero){n.val("")}else if(!t.symbolStay){n.val(v())}else{n.val(m(v()))}}else{if(!t.symbolStay){n.val(n.val().replace(t.symbol,""))}else if(t.symbolStay&&n.val()==t.symbol){n.val(m(v()))}}}function c(e){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}function h(e,t){var r=n.val().length;n.val(d(e.value));var i=n.val().length;t=t-(r-i);y(n,t)}function p(){var e=n.val();n.val(d(e))}function d(e){e=e.replace(t.symbol,"");var n="0123456789";var r=e.length;var i="",s="",o="";if(r!=0&&e.charAt(0)=="-"){e=e.replace("-","");if(t.allowNegative){o="-"}}if(r==0){if(!t.defaultZero)return s;s="0.00"}for(var u=0;u<r;u++){if(e.charAt(u)!="0"&&e.charAt(u)!=t.decimal)break}for(;u<r;u++){if(n.indexOf(e.charAt(u))!=-1)i+=e.charAt(u)}var a=parseFloat(i);a=isNaN(a)?0:a/Math.pow(10,t.precision);s=a.toFixed(t.precision);u=t.precision==0?0:1;var f,l=(s=s.split("."))[u].substr(0,t.precision);for(f=(s=s[0]).length;(f-=3)>=1;){s=s.substr(0,f)+t.thousands+s.substr(f)}return t.precision>0?m(o+s+t.decimal+l+Array(t.precision+1-l.length).join(0)):m(o+s)}function v(){var e=parseFloat("0")/Math.pow(10,t.precision);return e.toFixed(t.precision).replace(new RegExp("\\.","g"),t.decimal)}function m(e){if(t.symbol!=""){var n="";if(e.length!=0&&e.charAt(0)=="-"){e=e.replace("-","");n="-"}if(e.substr(0,t.symbol.length)!=t.symbol){e=n+t.symbol+e}}return e}function g(e){var n=e.val();if(t.allowNegative){if(n!=""&&n.charAt(0)=="-"){return n.replace("-","")}else{return"-"+n}}else{return n}}function y(t,n){e(t).each(function(e,t){if(t.setSelectionRange){t.focus();t.setSelectionRange(n,n)}else if(t.createTextRange){var r=t.createTextRange();r.collapse(true);r.moveEnd("character",n);r.moveStart("character",n);r.select()}});return this}function b(e){var t=0,n=0,r,i,s,o,u;if(typeof e.selectionStart=="number"&&typeof e.selectionEnd=="number"){t=e.selectionStart;n=e.selectionEnd}else{i=document.selection.createRange();if(i&&i.parentElement()==e){o=e.value.length;r=e.value.replace(/\r\n/g,"\n");s=e.createTextRange();s.moveToBookmark(i.getBookmark());u=e.createTextRange();u.collapse(false);if(s.compareEndPoints("StartToEnd",u)>-1){t=n=o}else{t=-s.moveStart("character",-o);t+=r.slice(0,t).split("\n").length-1;if(s.compareEndPoints("EndToEnd",u)>-1){n=o}else{n=-s.moveEnd("character",-o);n+=r.slice(0,n).split("\n").length-1}}}}return{start:t,end:n}}var n=e(this);var r=false;n.unbind(".maskMoney");n.bind("keypress.maskMoney",o);n.bind("keydown.maskMoney",a);n.bind("blur.maskMoney",l);n.bind("focus.maskMoney",f);n.bind("mask.maskMoney",p)})}};e.fn.maskMoney=function(n){if(t[n]){return t[n].apply(this,Array.prototype.slice.call(arguments,1))}else if(typeof n==="object"||!n){return t.init.apply(this,arguments)}else{e.error("Method "+n+" does not exist on jQuery.maskMoney")}}})(window.jQuery||window.Zepto)

