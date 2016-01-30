<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog"  style="width:800px;">
    <div class="modal-content" style="width:800px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Upload a File</h4>
      </div>
      <div class="modal-body">
      <div class="row">
      	<div class="col-sm-12"> <!--  tab row -->
      		<ul class="nav nav-tabs" id="mediaTab" >
	        	<li class="active"><a href="#upload-tab" data-toggle="tab"><span class="glyphicon glyphicon-cloud-upload"></span> Upload File</a></li>
	        	<li><a href="#gallery-tab" data-toggle="tab"><span class="glyphicon glyphicon-time"></span> Recent Uploads (Coming Soon)</a></li>
	        </ul>
      	</div>
      </div>
      <div class="row">
      	<div class="col-sm-8"> <!--  main window, normally the grid -->
      		<div class="tab-content" style="padding-bottom:18px;">
	            <div class="tab-pane active" id="upload-tab">
                  {!! Form::open(array('target'=>'postiframe','files'=>true,'class' => 'image-form', 'role'=>'form', 'url' => $base_uri.'file/upload', 'method' => 'POST', 'autocomplete' => 'off', 'id'=>'station-fileupload-form')) !!}
                  {!! Form::file('uploaded_file',['style'=>'display:none;']) !!}
                  
                  <div id="station-fileupload-hud" style="margin:auto;text-align:center;"></div>
                  <button type="button" class="btn btn-primary btn-block trigger_img_upload">Upload File</button>

                  {!! Form::hidden('panel_name', $panel_name) !!}
                  {!! Form::hidden('parent_panel_name', $parent_panel_name) !!}
                  {!! Form::hidden('upload_element_name', null) !!}
                  {!! Form::hidden('img_sizes', null) !!}
                  {!! Form::hidden('method', $method) !!}
                  {!! Form::close() !!}
	            </div>
	            <div class="tab-pane" id="gallery-tab">
	              	<p>Nothing here yet.</p>
	            </div>
        	</div>
      	</div>
      	<div class="col-sm-4"> <!--  right sidebar -->
          <div class="station-file-options" style="display:none;">

              
          </div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success file-upload-save" data-dismiss="modal">Save &amp; Close</button>

      </div>
    </div><!-- /.modal-body -->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->