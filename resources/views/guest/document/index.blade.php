<?php

  $table_sessions = [];

  $appended_requests = [
                        'q'=> Request::get('q'),
                        'sort' => Request::get('sort'),
                        'direction' => Request::get('direction'),
                      ];

?>


@extends('layouts.guest-master')
@section('content')

	
	<section class="content-header">
		<h1>Search Documents</h1>
	</section>

	<section class="content">

		{{-- Form Start --}}
		<form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('guest.document.index') }}">

			<div class="box box-solid" id="pjax-container" style="overflow-x:auto;">

			{{-- Table Search --}}        
			<div class="box-header with-border">
			{!! __html::table_search(route('guest.document.index')) !!}
			</div>

		{{-- Form End --}}  
		</form>

		{{-- Table Grid --}}        
		<div class="box-body no-padding">
			<table class="table table-hover">
			  <tr>
			    <th>View</th>
			    <th>@sortablelink('filename', 'Filename')</th>
			    <th>@sortablelink('updated_at', 'Last Updated')</th>
			  </tr>
			  @foreach($documents as $data) 
			    <tr>
			      <td>
	                @if(Storage::disk('local')->exists($data->file_location))
	                  <a href="{{ route('guest.document.view_file', $data->slug) }}" class="btn btn-sm btn-success" target="_blank">
	                    <i class="fa fa-file-pdf-o"></i>
	                  </a>
	                @else
	                  <a href="#" class="btn btn-sm btn-warning"><i class="fa fa-exclamation-circle"></i></a>
	                @endif
                  </td>
			      <td id="mid-vert">{{ $data->file_name }}</td>
			      <td id="mid-vert">{{ $data->updated_at->diffForHumans() }}</td>
			    </tr>
			    @endforeach
			 </table>
		</div>

		@if($documents->isEmpty())
		<div style="padding :5px;">
		  <center><h4>No Records found!</h4></center>
		</div>
		@endif

		<div class="box-footer">
		{!! __html::table_counter($documents) !!}
		{!! $documents->appends($appended_requests)->render('vendor.pagination.bootstrap-4')!!}
		</div>

		</div>

	</section>



@endsection