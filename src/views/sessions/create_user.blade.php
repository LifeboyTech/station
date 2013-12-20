@extends('station::layouts.base')

@section('main_content')
    {{ Form::open(array('class'=>'well ajaxme','role'=>'form')) }}

    @foreach($panel_data['elements'] as $element_name => $element_info)
        <div class="form-group">
            <label for="{{ $element_name }}">{{ $element_info['label'] }}</label>
        @if($element_info['type']=='password' || $element_info['type']=='text' || $element_info['type']=='email' || $element_info['type']=='date')
            <input type="{{ $element_info['type'] }}" name="{{ $element_name }}" class="form-control" id="{{ $element_name }}">
        @endif
        </div>
    @endforeach

    <button type="submit" class="btn btn-default">{{ $title }}</button>

    {{ Form::close() }}
@stop