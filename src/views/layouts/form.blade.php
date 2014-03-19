<?php
	Form::macro('date', function($element_name, $default_value, $attributes)
	{	
		$attribs = array(

			'id'			=>'station-'.$element_name, 
			'class'			=>'form-control for-date '.$attributes['class'], 
			'autocomplete'	=> 'off',
			'data-name'		=> $element_name
		);

	    return '<div class="input-group">'
	    		. '<span id="calendar-drop-for-'.$element_name.'" class="input-group-addon"><span class="fui-calendar"></span></span>'
	    		. Form::text($element_name.'-fake', '' , $attribs)
	    		. '</div>'
	    		. Form::hidden($element_name, $default_value, ['id' => $element_name.'-fake']);
	});

	Form::macro('rawLabel', function($name, $value = null, $options = array())
	{
	    $label = Form::label($name, '%s', $options);

	    return sprintf($label, $value);
	});

	$needs_media = FALSE;
	$has_markdown = FALSE;
?>

@extends('station::layouts.base')

@section('content')
	
	<div id="form-for-{{ $curr_panel }}">
		
		<?php 
			if (isset($subpanel_parent_uri) && $subpanel_parent_uri){

				$anchor = isset($curr_record_id) ? '#'.$curr_subpanel.'-record-'.$curr_record_id : '';
				$go_back_uri = $subpanel_parent_uri.$anchor;

			} else {

				$anchor = isset($curr_record_id) ? '#'.$curr_panel.'-record-'.$curr_record_id : '';
				$go_back_uri = $panel_data['relative_uri'].$anchor;
			}
		?>
		
		{{-- show alert manually if desired --}}
		@if (isset($n_items_in_panel) && $n_items_in_panel == 0 && isset($panel_data['panel_options']['no_data_alert']))
			<div class="alert">
	          <button type="button" class="close fui-cross" data-dismiss="alert"></button>
	          <h4>{{ $panel_data['panel_options']['no_data_alert']['header'] }}</h4>
	          <p>{{ $panel_data['panel_options']['no_data_alert']['body'] }}</p>
	        </div>
		@endif

		{{-- header bar --}}
		<div class="clearfix station-form-header">
			<div class="pull-left"><h5>{{ $layout_title }}</h5></div>

			<div class="station-form-options">
				@if (isset($adjacents) && $adjacents && (!isset($subpanel_parent_uri) || !$subpanel_parent_uri))
					<div class="pull-right">
						<a href="{{ '/'.$base_uri.'panel/'.$curr_panel.'/update/'.$adjacents['next'] }}" class="btn btn-xs btn-default list-new">
							Next {{ $single_item_name }} <span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</div>
					<div class="pull-right">
						<a href="{{ '/'.$base_uri.'panel/'.$curr_panel.'/update/'.$adjacents['prev'] }}" class="btn btn-xs btn-default list-new">
							<span class="glyphicon glyphicon-chevron-left"></span> Prev
						</a>
					</div>
				@endif
				@if (isset($n_items_in_panel) && $n_items_in_panel > 0)
					<div class="pull-right">
						<a href="{{ $go_back_uri }}" class="btn btn-xs btn-default list-new">
							<span class="glyphicon glyphicon-chevron-up"></span> Back To List
						</a>
					</div>
				@endif
				@if (isset($subpanel_parent_uri) && $subpanel_parent_uri)
					<div class="pull-right">
						<a href="{{ $go_back_uri }}" class="btn btn-xs btn-default list-new">
							<span class="fui-arrow-left"></span> Back
						</a>
					</div>
				@endif
				@if (isset($passed_model->preview_url))
					<div class="pull-right">
						<a href="{{ $passed_model->preview_url }}" class="btn btn-xs btn-inverse list-new" target="_blank">
							<span class="glyphicon glyphicon-new-window"></span>&nbsp; Preview
						</a>
					</div>
				@endif
			</div>
		</div>

		@if($form_purpose=='create')

			{{ Form::open(array('class' => 'station-form', 'role'=>'form', 'url' => $form_action, 'method' => $form_method, 'autocomplete' => 'off')) }}

		@else

			{{ Form::model($passed_model, array('class' => 'station-form', 'url' => $form_action, 'method' => $form_method, 'autocomplete' => 'off')) }}

		@endif

			@foreach($panel_data['elements'] as $element_name => $element_info)

				<div class="form-group station-element-group" data-element-name="{{ $element_name }}" {{ $element_info['type'] == 'hidden' ? 'style="display: none;"' : '' }}>
					
					<?php 
						$id				= 'station-'.$element_name;
						$help			= isset($element_info['help']) && $element_info['help'] != '' ? $element_info['help'] : FALSE;
						$helper			= isset($element_info['helper']) && $element_info['helper'] != '' ? $element_info['helper'] : FALSE;
						$help_append 	= $helper == 'markdown' ? '<span class="markdown-helper">Help Formatting</span>' : '';
						$has_markdown 	= $helper == 'markdown' ? TRUE : $has_markdown;
						$is_required 	= isset($element_info['rules']) && strpos($element_info['rules'], 'required') !== FALSE;
						$label			= $element_info['label'];
						$label 			.= $is_required ? '<sup>*</sup>' : '';
						$append_classes	= isset($element_info['format']) ? $element_info['format'] : '';
						$default_value 	= isset($element_info['default']) ? $element_info['default'] : null; // important to keep this as null
					?>

					{{-- show label if not a hidden field --}}
					@if ($element_info['type'] != 'hidden')
						<div class="label-wrap">
							{{ Form::rawLabel($id, $label) }}
							{{ $help || $helper ? '<span>'.$help.' '.$help_append.'</span>' : '' }}
						</div>
					@endif
					

					{{-- plain jane text entry, textarea, or hidden field --}}
					@if(in_array($element_info['type'],['integer','text','email','date','time','datetime','textarea','hidden','float','tags']))
						<?php 
							$with_spinner 		= $append_classes == 'spinner';
							$with_append		= isset($element_info['append']) && $element_info['append'] != '' ? 'with-append' : FALSE;
							$with_prepend		= isset($element_info['prepend']) && $element_info['prepend'] != '' ? 'with-prepend' : FALSE;
							$with_prepend_icon	= isset($element_info['prepend_icon']) && $element_info['prepend_icon'] != '' ? $element_info['prepend_icon'] : FALSE;
							$with_append_icon	= isset($element_info['append_icon']) && $element_info['append_icon'] != '' ? $element_info['append_icon'] : FALSE;
							$with_input_wrap	= $with_append_icon || $with_prepend_icon || $with_append || $with_prepend;
							$append_classes 	.= $element_info['type'] == 'datetime' ? ' with-time' : '';
							$append_classes 	.= $element_info['type'] == 'tags' ? ' tagsinput tagsinput-primary' : '';
							$attributes			= array('id' => $id, 'class'=>'form-control '.$append_classes, 'autocomplete' => 'off');
							
							if ($element_info['type'] == 'textarea' && isset($element_info['rows'])) $attributes['rows'] = $element_info['rows']; 
							if ($element_info['type'] == 'float' || $element_info['type'] == 'integer') $element_info['type'] = 'text';
							if ($element_info['type'] == 'datetime') $element_info['type'] = 'date';
							if ($element_info['type'] == 'tags') $element_info['type'] = 'text';
							if (isset($element_info['disabled']) && $element_info['disabled']) $attributes['disabled'] = 'disabled'; 
						?>
						{{ $with_input_wrap ? '<div class="input-group '.$with_append.'">' : '' }}
						{{ $with_spinner ? '<div class="control-group">' : '' }}

							@if ($with_prepend)
								<span class="input-group-addon">{{ $element_info['prepend'] }}</span>
							@endif

							@if ($with_prepend_icon)
								<span class="input-group-addon"><span class="{{ $with_prepend_icon }}"></span></span>
							@endif

							{{ Form::$element_info['type']($element_name, $default_value, $attributes) }}

							@if ($with_append)
								<span class="input-group-addon">{{ $element_info['append'] }}</span>
							@endif

							@if ($with_append_icon)
								<span class="input-group-addon"><span class="{{ $with_append_icon }}"></span></span>
							@endif

						{{ $with_input_wrap || $with_spinner ? '</div>' : '' }}
					@endif

					{{-- boolean / checkbox --}}
					@if ($element_info['type'] == 'boolean')
						<?php $default_value = isset($element_info['default']) ? $element_info['default'] : null; ?>
						{{ Form::checkbox($element_name, '1', $default_value, ['id' => $id, 'data-toggle' => 'switch']) }}
					@endif

					{{-- password field --}}
					@if($element_info['type']=='password')
						{{ Form::password($element_name,array('id' => $id,'class' => 'form-control')) }}
					@endif

					{{-- multiselect using foreign data --}}
					@if($element_info['type']=='multiselect' && isset($foreign_data[$element_name]))
						{{ Form::select($element_name.'[]', 
							$foreign_data[$element_name], 
							(Input::old($element_name) || !isset($passed_model)) ? null : $passed_model->$element_name->lists('id'), 
							['multiple' => 'multiple','class'=>'chosen-select', 'style' => 'width: 400px', 'id' => $id, 'data-placeholder' => 'Please choose...']) }}
					@endif

					{{-- radio buttons --}}
					@if ($element_info['type'] == 'radio' && (isset($foreign_data[$element_name]) || (isset($element_info['data']['options']))))
						<?php $options = isset($foreign_data[$element_name]) ? $foreign_data[$element_name] : $element_info['data']['options'] ?>
						<div class="radio-wrap">
							@foreach ($options as $item_id => $item_val)
								<label class="radio">{{ Form::radio($element_name, $item_id, null, ['id' => $id.'_'.$item_id]) }} {{ $item_val }}</label>
							@endforeach
						</div>
					@endif

					{{-- single select --}}
					@if ($element_info['type'] == 'select' && (isset($foreign_data[$element_name]) || (isset($element_info['data']['options']))))
						<?php $options = isset($foreign_data[$element_name]) ? $foreign_data[$element_name] : $element_info['data']['options'] ?>
						<?php $options = array('' => '') + $options ?> {{-- this is needed to display the harvest/chosen placeholder --}}
						{{ Form::select($element_name, $options, null,['class'=>'chosen-select', 'style' => 'width: 400px', 'id' => $id, 'data-placeholder' => 'Please choose one...']) }}
					@endif

					{{-- Image/gallery handling --}}
					@if (in_array($element_info['type'],['image','image_gallery']))

					<?php 
						$attributes	= array('id' => $id, 'class'=>'img-hidden-input form-control '.$append_classes, 'autocomplete' => 'off');
						$needs_media = TRUE;
						$bucket_name = $app_data['media_options']['AWS']['bucket'];
						?>
						<div class="station-file-upload-wrap row">
							<div class="col-sm-2">
								<img width="100px" height="100px" src="/packages/canary/station/img/file-placeholder.png" bucket="{{ $bucket_name }}" class="img-thumbnail station-img-thumbnail" id="target-{{ $element_name }}">
							</div>
							@if (isset($element_info['fetch_url']))
								<div class="col-sm-8 station-parsed-url-controls">
									<div class="input-group input-group-sm">
										<span class="input-group-addon">Source URL</span>
										<input type="text" data-element="{{ $element_name }}" class="url-fetch-target form-control" id="{{ $element_name.'_url' }}" />
										<span class="input-group-addon fetcher"><span class="glyphicon glyphicon-cloud-download"></span></span>
									</div>
									<div class="parsed-results" data-element="{{ $element_name }}" data-mapping='{{{ json_encode($element_info['fetch_url']) }}}'></div>
								</div>
							@endif
							<div class="col-sm-8 station-file-upload-controls">
								<div class="btn-group">
									<button type="button" class="btn btn-default station-media" style="display:none;" id="edit_for_{{ $element_name }}">
										<span class="fui-new"></span>&nbsp;
										Edit
									</button>
									<button type="button" class="btn btn-default file-remover" style="display:none;" id="remove_for_{{ $element_name }}">
										<span class="fui-cross-inverted"></span>&nbsp;
										Remove Image
									</button>
								</div>								
								<div class="btn-group">								
									<button type="button" class="btn btn-default station-media" id="upload_for_{{ $element_name }}">
										<span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;
										Upload
									</button>
									<button type="button" class="btn btn-default station-media" id="gallery_for_{{ $element_name }}">
										<span class="glyphicon glyphicon-time"></span>&nbsp;
										Use A Recent Upload
									</button>
								</div>
							</div>
						</div>
						{{ Form::hidden($element_name, $default_value, $attributes) }}
					@endif

					{{-- sub panel list --}}
					@if ($element_info['type'] == 'subpanel')
						<?php 
							$sub_panel_data = [

								'panel_name'		=> $element_name, 
								'panel_definition'	=> $element_info,
								'parent_panel'		=> $curr_panel,
								'parent_id'			=> isset($passed_model['id']) ? $passed_model['id'] : null,
								'data'				=> isset($passed_model[$element_name]) ? $passed_model[$element_name]->toArray() : array(), 
								'config'			=> $foreign_panels[$element_name]
							];
						?>
						@include('station::layouts.sub-panel-list', $sub_panel_data)
					@endif

				</div>

			@endforeach

			<div class="pull-left">
				<button class="btn btn-success btn-hg station-form-submit">
					@if(isset($button_override))
					{{ $button_override['save'] }} <i class="fui-arrow-right"></i>
					@else
					{{ $submit_value }} <i class="fui-arrow-right"></i>
					@endif
				</button>

				{{-- we check if they are logged in because this appears on the register form! --}}
				@if ($form_purpose == 'create' && Auth::check())
						@if(isset($button_override))
							@if($button_override['save_add']!==0)
							<button name="after_save" value="create" class="btn btn-success btn-hg station-form-submit">
								{{ $button_override['save_add'] }}
							</button>	
							@endif							
						@else
							<button name="after_save" value="create" class="btn btn-success btn-hg station-form-submit">
								Save and add another
							</button>
						@endif
				@endif
				
				@if ($form_purpose != 'create')
					@if(isset($button_override))
							@if($button_override['save_add']!==0)
							<button name="after_save" value="stay" class="btn btn-success btn-hg station-form-submit">
								{{ $button_override['save_add'] }}
							</button>	
							@endif							
						@else
							<button name="after_save" value="stay" class="btn btn-success btn-hg station-form-submit">
								Apply changes but stay here
							</button>
						@endif
					</button>								
				@endif
			
				<a href="{{ $go_back_uri }}" class="list-new form-canceler">
					or cancel
				</a>
			</div>

			@if($needs_media)
				{{ Form::hidden('img_sizes_array', json_encode($img_size_data)) }}
			@endif

		{{ Form::close() }}

		@if($needs_media)
			@include('station::layouts.media')
		@endif

		@if ($has_markdown)
			@include('station::partials.markdown_helper')
		@endif

		@include('station::partials.deleter')

	</div>
	
@stop