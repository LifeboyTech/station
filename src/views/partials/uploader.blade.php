<div class="station-file-upload-wrap row {{ $is_embedder ? 'snapped-to-textarea' : '' }}">
	<div class="col-sm-2">
		<img width="100px" height="100px" src="/packages/canary/station/img/missing.gif" bucket="{{ $bucket_name }}" class="img-thumbnail station-img-thumbnail" id="target-{{ $original_el_name }}" data-target="{{ $original_el_name }}">
	</div>
	@if (isset($element_info['fetch_url']))
		{{-- Special element which can fetch URLs --}}
		<div class="col-sm-8 station-parsed-url-controls">
			<div class="input-group input-group-sm">
				<span class="input-group-addon">Source URL</span>
				<input type="text" data-element="{{ $el_name }}" class="url-fetch-target form-control" id="{{ $el_name.'_url' }}" />
				<span class="input-group-addon fetcher"><span class="glyphicon glyphicon-cloud-download"></span></span>
			</div>
			<div class="parsed-results" data-element="{{ $el_name }}" data-mapping='{{{ json_encode($element_info['fetch_url']) }}}'></div>
		</div>
	@endif
	<div class="col-sm-8 station-file-upload-controls">
		<div class="btn-group">
			<button type="button" class="btn btn-default station-media" style="display:none;" id="edit_for_{{ $original_el_name }}">
				<span class="fui-new"></span>&nbsp;
				Edit
			</button>
			<button type="button" class="btn btn-default file-remover" style="display:none;" id="remove_for_{{ $original_el_name }}">
				<span class="fui-cross-inverted"></span>&nbsp;
				Remove Image
			</button>
		</div>								
		<div class="btn-group">								
			<button type="button" class="btn btn-default station-media station-media-upload-btn" id="upload_for_{{ $original_el_name }}">
				<span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;
				Upload
			</button>
			<button type="button" class="btn btn-default station-media" id="gallery_for_{{ $original_el_name }}">
				<span class="glyphicon glyphicon-time"></span>&nbsp;
				Use A Recent Upload
			</button>
		</div>
	</div>
</div>
{{ Form::hidden($el_name, $default_val, $attributes) }}