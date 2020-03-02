<?php
   $folders =Storage::directories();
?>

@extends('layouts.guest-master')


@section('extras')
  <link type="text/css" rel="stylesheet" href="{{ asset('template/plugins/jquery-ui/jquery-ui.css') }}">
@endsection


@section('content')

	<section class="content">

	    <div class="box box-solid">
	    
	      <div class="box-header with-border">
	        <h3 class="box-title">Reports</h3>
	        <div class="pull-right">
	            <code>Fields with asterisks(*) are required</code>
	        </div> 
	      </div>
	      
	      <form role="form" method="GET" autocomplete="off" action="{{ route('guest.document.reports_print') }}" target="__blank">

	        <div class="box-body">
	     
	          <h5>Date Filter: </h5>
		        {!! __form::datepicker('3', 'df',  'From', old('df'), '', '') !!}

		        {!! __form::datepicker('3', 'dt',  'To', old('dt'), '', '') !!}
		          
	        </div>

	        <div class="box-footer">
	          <button type="submit" class="btn btn-default">Print <i class="fa fa-fw fa-save"></i></button>
	        </div>

	      </form>

	    </div>

	 </section>

@endsection


@section('scripts')

  <script type="text/javascript">

  </script> 

@endsection
    