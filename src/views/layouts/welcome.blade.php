@extends('station::layouts.base')

@section('content')

<div class="dashboard-wrap">

	<h5>
		<i class="glyphicon glyphicon-dashboard" style="font-size: 23px; margin: 0 0 40px 0;"></i>  &nbsp; Welcome to Station! &nbsp; 
	</h5>

	<p>
		<strong>If you are reading this message then you have installed Station successfully</strong>
	</p>

	<p>
		The next thing you should do is configure your <strong>/config/packages/lifeboy/station/_app.php</strong> file and begin to create individual "panel" files. Each panel appears in Station's navigation bar and is an area where your users can control data or content.
	</p>

	<p>
		There is plenty more to read about this and other things in <a href="http://station.readthedocs.org/" target="_blank">the documentation</a>.
	</p>
	
</div>

@stop