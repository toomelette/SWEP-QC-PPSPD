<?php
   
   $folders = Storage::directories();

   $available_ext = ['pdf', 'jpeg', 'jpg', 'png', 'txt', 'cvs'];

?>

@extends('layouts.guest-master')

	
@section('content')

	<section class="content">

	    <div class="box box-solid">
	    
	      <div class="box-header with-border">
	        <h3 class="box-title"  style="padding-top: 5px;">Details</h3>
	        <div class="pull-right">
	            {!! __html::back_button(['guest.document.index']) !!}
	        </div> 
	      </div>

	        <div class="box-body">

				<table class="table table-striped">
						
					<tr>
						<td>Filename</td>
						<td>{{ $document->file_name }}</td>
					</tr>
						
					<tr>
						<td>Filesize</td>
						<td>{{ number_format($document->file_size / 1000) }} KB</td>
					</tr>
						
					<tr>
						<td>File Extension</td>
						<td>.{{ $document->file_ext }}</td>
					</tr>
						
					<tr>
						<td>Time Created</td>
						<td>{{ __dataType::date_parse($document->created_at, 'M d, Y - g:i:s A') }}</td>
					</tr>
						
					<tr>
						<td>Time Updated</td>
						<td>{{ __dataType::date_parse($document->updated_at, 'M d, Y - g:i:s A') }}</td>
					</tr>

				</table>          
	          
	        </div>

	        <div class="box-footer">

	        	@if(in_array($document->file_ext, $available_ext))
		        	<a href="{{ route('guest.document.view_file', $document->slug) }}" type="button" class="btn btn-default" target="_blank">
		        		View <i class="fa fa-fw fa-eye"></i>
		        	</a>
	        	@endif

	        	&nbsp;
	        	<a href="{{ route('guest.document.download', $document->slug) }}" type="button" class="btn btn-default" target="_blank">
	        		Download <i class="fa fa-fw fa-download"></i>
	        	</a>

	        </div>

	    </div>

	 </section>


@endsection
    