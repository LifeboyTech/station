<?
	$can_create = isset($panel_definition['permissions']) && strpos($panel_definition['permissions'], 'C') !== FALSE;
?>

@if (count($data) == 0 || !$data)

	@if ($can_create)
		<button type="button" class="btn btn-default sub-panel-adder" 
				data-panel-name="{{ $panel_name }}" 
				data-single-item-name="{{ $config['panel_options']['single_item_name'] }}"
				data-initial-label="Add a New {{{ $config['panel_options']['single_item_name'] }}}">
			<span class="fui-plus"></span>&nbsp;
			<span>Add a New {{ $config['panel_options']['single_item_name'] }}</span>
		</button>
	@endif

@else

	@if ($can_create)
		<button type="button" class="btn btn-default btn-xs sub-panel-adder for-sub-list-header" 
				data-panel-name="{{ $panel_name }}" 
				data-single-item-name="{{ $config['panel_options']['single_item_name'] }}"
				data-initial-label="Add a New {{{ $config['panel_options']['single_item_name'] }}}">
			<span class="fui-plus"></span>&nbsp;
			<span>Add a New {{ $config['panel_options']['single_item_name'] }}</span>
		</button>
	@endif

	<? 
		$is_reorderable = count($data) > 1 && isset($config['panel_options']['reorderable_by']) && $config['panel_options']['reorderable_by'];
		$reorder_class = $is_reorderable ? 'is-reorderable' : ''; 
	?>

	<ul data-panel-name="{{ $panel_name }}" 
		class="list-group {{ $reorder_class }} station-list in-subpanel" 
		data-relative-uri="{{ '/'.$base_uri.'panel/'.$panel_name.'/'.$parent_panel.'/'.$parent_id }}" 
		data-single-item-name="{{ $config['panel_options']['single_item_name'] }}">
		@foreach ($data as $row)
			<li class="list-group-item" id="{{ $panel_name }}-record-{{  $row['id'] }}" data-id="{{  $row['id'] }}">

				{{-- We need our button for edit/update --}}
				<span class="td-for-edit col-0">
					<a href="{{ '/'.$base_uri.'panel/'.$panel_name.'/update/'.$row['id'].'/for/'.$parent_panel.'/'.$parent_id }}" class="btn btn-xs btn-default">
						<i class="fui-new"></i>
					</a>
				</span>

				<? $c = 1; ?>

				@foreach($config['elements'] as $elem_name => $elem_data)
					
					@if (isset($elem_data['display']) && strpos($elem_data['display'], 'L') !== FALSE)
						
						{{-- If the data is an array, we need to loop though THAT and get the names --}}
						@if(isset($row[$elem_name]) && is_array($row[$elem_name]))
							<span class="col-{{ $c }}">
								@foreach($row[$elem_name] as $i => $sub_data)
									{{ $i > 0 ? '| ' : '' }}{{ $sub_data['name'] }} 
								@endforeach
							</span>

						{{-- we have some static options in an array --}}
						@elseif (isset($row[$elem_name]) && isset($elem_data['data']['options'][$row[$elem_name]]))
							
							<span>
								{{ $elem_data['data']['options'][$row[$elem_name]] }}
							</span>

						{{-- display the ones that are belongsTo --}}
						@elseif (isset($foreign_data[$elem_name]))
							<span class="col-{{ $c }}">
								{{ $foreign_data[$elem_name][$row[$elem_name]] }}
							</span>

						{{-- just show the value --}}
						@elseif (isset($row[$elem_name]))
							<span class="col-{{ $c }}">
								{{ $row[$elem_name] }}
							</span>
						@endif

						<? $c++; ?>

					@endif
			
				@endforeach

				{{-- We need our button for delete --}}
				<span class="td-for-delete col-delete">
					<a href="javascript:;" 
						class="btn btn-xs btn-danger list-record-deleter pull-right" 
						data-target="#deleter-modal" 
						data-toggle="modal">
						<i class="fui-cross"></i>
					</a>
				</span>
			</li>
		@endforeach
	</ul>
@endif