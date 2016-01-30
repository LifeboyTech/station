<div class="dd">

	<?php 
		$curr_depth = 0;
		$last_node_index = count($data['data']) -1;
	?>

	@foreach($data['data'] as $index => $row)

	  	<?php $row = (array) $row;	?>

	  	{{-- level down? or the first one --}}
	  	@if ($row['depth'] > $curr_depth || $index == 0)
	  		<ol class="dd-list">
	  	@endif

	  	{{-- level up? --}}
	  	@if ($row['depth'] < $curr_depth)
	  		<?php echo str_repeat('</ol></li>', $curr_depth - $row['depth']) ?>
	  	@endif

	  	{{-- always open a node --}}
	  		<li class="dd-item" data-id="{{ $row['id'] }}" id="nestable-record-{{ $row['id'] }}">
	  			
	  			<span class="fui-new" data-link="{{ $panel_data['relative_uri'].'/update/'.$row['id'] }}"></span>

	  			@if ($user_can_delete)
		  			<a href="{{ $panel_data['relative_uri'].'/update/'.$row['id'] }}" 
						class="pull-right btn btn-xs btn-danger list-record-deleter" 
						data-target="#deleter-modal" 
						data-toggle="modal">
						<i class="fui-cross"></i>
					</a>
		  		@endif

		  		@if (FALSE && $user_can_create)
		  			<a class="nestable-adder" href="javascript:;">add</a>
		  		@endif

		  		<div class="dd-handle" id="{{ $curr_panel }}-record-{{  $row['id'] }}">
		  			@include('station::partials.list_item_row_content', compact('row'))
		  		</div>

		{{-- does this node NOT have children? if not, close it --}}
	  	@if ($index != $last_node_index && $data['data'][$index + 1]['depth'] <= $row['depth'])
			</li>  	      	
	  	@endif

	  	{{-- set the current depth level to this depth --}}
	  	<?php $curr_depth = $row['depth'] ?>

	  	{{-- is this the last node? --}}
	  	@if ($index == $last_node_index)
	  		</ol>
	  		<?= str_repeat('</li></ol>', $curr_depth) ?>
	  	@endif
	  
	@endforeach
</div>