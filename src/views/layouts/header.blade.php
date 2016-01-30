@if(Session::has('success'))
    <div class="dialog dialog-success flash-response">
      {!! Session::get('success') !!}
      <button class="btn btn-default btn-xs" data-dismiss="alert">
        <span class="fui-cross"></span>
      </button>
    </div>
@endif

@if(Session::has('error'))
    <div class="dialog dialog-danger flash-response">
      {!! Session::get('error') !!}
      <button class="btn btn-default btn-xs" data-dismiss="alert">
        <span class="fui-cross"></span>
      </button>
    </div>
@endif

@if(Session::has('errors'))
    <div class="dialog dialog-danger flash-response">
      <p><b>There were some problems:</b></p>
      @foreach (Session::get('errors')->all() as $error)
        <li>{!! $error !!}</li>
      @endforeach
      <button class="btn btn-danger btn-xs" data-dismiss="alert">
        <span class="fui-cross"></span>
      </button>
    </div>
@endif

{{-- useful for validator responses when we're not doing a redirect and have no session flashes --}}
@if(isset($ajax_errors) && $ajax_errors && count($ajax_errors) > 0)
    <div class="dialog dialog-danger flash-response">
      <p><b>There were some problems:</b></p>
      @foreach ($ajax_errors as $error)
        <li>{!! $error[0] !!}</li>
      @endforeach
      <button class="btn btn-danger btn-xs" data-dismiss="alert">
        <span class="fui-cross"></span>
      </button>
    </div>
@endif