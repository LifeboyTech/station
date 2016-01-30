@foreach($data['data'] as $row)

	<?php $row = (array) $row; ?>

	{!! $row_opener !!} id="{{ $curr_panel }}-record-{{  $row['id'] }}" data-id="{{  $row['id'] }}">

		@if ($user_can_bulk_delete)
			{{-- We need our bulk-delete checkbox --}}
			<{{ $item_element }} class="td-for-bulk-delete col-0-0">
				<label class="checkbox row-checkbox"><input type="checkbox" value="" id="checkbox1" data-toggle="checkbox"></label>
			</{{ $item_element }}>
		@endif

		@if ($user_can_update)
			{{-- We need our button for edit/update --}}
			<{{ $item_element }} class="td-for-edit col-0">
				<a href="{{ $panel_data['relative_uri'].'/update/'.$row['id'] }}" class="btn btn-xs btn-default">
					<i class="fui-new"></i>
				</a>
			</{{ $item_element }}>
		@endif

		@include('station::partials.list_item_row_content', compact('row'))

		@if ($user_can_delete)
			{{-- We need our button for delete --}}
			<{{ $item_element }} class="td-for-delete col-delete">
				<a href="{{ $panel_data['relative_uri'].'/update/'.$row['id'] }}" 
					class="btn btn-xs btn-danger list-record-deleter" 
					data-target="#deleter-modal" 
					data-toggle="modal">
					<i class="fui-cross"></i>
				</a>
			</{{ $item_element }}>
		@endif
	{!! $row_closer !!}
@endforeach