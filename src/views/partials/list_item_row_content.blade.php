<?php $c = 1; ?>

@foreach($data['config']['elements'] as $elem_name => $elem_data)
	
	@if (strpos($elem_data['display'], 'L') !== FALSE)
		
		{{-- If the data is an array, we need to loop though THAT and get the names --}}
		@if(isset($row[$elem_name]) && is_array($row[$elem_name]))
		  <{{ $item_element }} class="col-{{ $c }}">
		  	<?php $n_visible = 3 ?>
		    @foreach($row[$elem_name] as $i => $sub_data)
		      <span class="st-it" {{ $i > ($n_visible - 1) ? 'style="display: none;"' : '' }}>
		      	{{ $i > 0 ? '| ' : '' }}{{ $sub_data['name'] }}
		      </span>
		      @if ($i == ($n_visible + 1))
		      	<a href="javascript:;" class="st-it-more">&nbsp; more...</a>
		      @endif
		    @endforeach
		  </{{ $item_element }}>

		{{-- we have some static options in an array --}}
		@elseif (isset($row[$elem_name]) && isset($elem_data['data']['options'][$row[$elem_name]]))
		  
		  <{{ $item_element }}>
		    {{ $elem_data['data']['options'][$row[$elem_name]] }}
		  </{{ $item_element }}>

		{{-- display the ones that are belongsTo --}}
		@elseif (isset($foreign_data[$elem_name]))
		  <{{ $item_element }} class="col-{{ $c }}">
		    {{ $row[$elem_name] > 0 && isset($foreign_data[$elem_name][$row[$elem_name]]) ? $foreign_data[$elem_name][$row[$elem_name]] : '' }}
		  </{{ $item_element }}>

		{{-- just show the value --}}
		@elseif (isset($row[$elem_name]))
		  <{{ $item_element }} class="col-{{ $c }}">
		    @if ($elem_data['type'] == 'date')
		      {{ date('m/j/y',strtotime($row[$elem_name])) }}
		    @elseif ($elem_data['type'] == 'datetime')
		      {{ date('m/j/y g:ia',strtotime($row[$elem_name])) }}
		    @elseif ($elem_data['type'] == 'image')
		      @if ($row[$elem_name] != '')
		        <img class="inline-thumb" width="100px" height="100px" src="{{ $base_img_uri.'station_thumbs_sm/'.$row[$elem_name] }}" />             
		      @else
		        <img class="inline-thumb" width="100px" height="100px" src="/packages/canary/station/img/file-placeholder.png" />
		      @endif
		    @elseif ($elem_data['type'] == 'boolean' && strpos($elem_data['display'], 'U') !== FALSE)
		      {{ Form::checkbox($data['config']['panel_options']['table'].'_'.$elem_name.'_'.$row['id'], '1', $row[$elem_name], ['data-element-name' => $elem_name, 'data-id' => $row['id'], 'class' => 'station-list-boolean', 'data-toggle' => 'switch']) }}
		    @else
		      {{ $row[$elem_name] }}  
		    @endif
		  </{{ $item_element }}>
		@endif

		<?php $c++; ?>
	
	@endif

@endforeach