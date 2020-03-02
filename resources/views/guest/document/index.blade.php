<?php

  $table_sessions = [ Session::get('DOCUMENT_UPDATE_SUCCESS_SLUG'), ];

  $appended_requests = [
                        'e'=> Request::get('e'),
                        'q'=> Request::get('q'),
                        'sort' => Request::get('sort'),
                        'direction' => Request::get('direction'),
                      ];

   $alphas = array_combine(range('A','Z'),range('A','Z'));
   
   $all = [
   			'ALL' => '', 
       		'0' => '0', 
       		'1' => '1', 
       		'2' => '2', 
       		'3' => '3', 
       		'4' => '4', 
       		'5' => '5', 
       		'6' => '6', 
       		'7' => '7', 
       		'8' => '8', 
       		'9' => '9', 
       	];

   $alphas = array_merge($all, $alphas);
   
   function design($file_ext){

   		$icon = "";

   		if ($file_ext == 'pdf') {
   			$icon = "fa-file-pdf-o bg-red";
   		}elseif($file_ext == 'doc' || $file_ext == 'docx'){
   			$icon = "fa-file-word-o bg-blue";
   		}elseif($file_ext == 'ppt' || $file_ext == 'pptx'){
   			$icon = "fa-file-powerpoint-o bg-orange";
   		}elseif($file_ext == 'xls' || $file_ext == 'xlsx' || $file_ext == 'csv'){
   			$icon = "fa-file-excel-o bg-green";
   		}elseif($file_ext == 'png' || $file_ext == 'jpg' || $file_ext == 'jpeg'){
   			$icon = "fa-file-image-o bg-blue";
   		}else{
   			$icon = "fa-file-text-o";
   		}

   		return $icon;

   }

?>


@extends('layouts.guest-master')
@section('content')

	
	<section class="content-header">
		<h1>Search Documents</h1>
	</section>

	<section class="content">


		{{-- Form Start --}}
		<form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('guest.document.index') }}">


			{{-- Advance Filters --}}
		    {!! __html::filter_open() !!}

			    {!! __form::select_static_for_filter(
			      '2', 'alpha', 'Alphabetical', old('pi'), $alphas, 'submit_document_filter', '', ''
			    ) !!}


		      	<div class="col-md-12 no-padding">
		        
			        <h5>Date Filter : </h5>
			        {!! __form::datepicker('3', 'df',  'From', old('df'), '', '') !!}

			        {!! __form::datepicker('3', 'dt',  'To', old('dt'), '', '') !!}

			        <button type="submit" class="btn btn-primary" style="margin:25px;">
			        	Filter Date <i class="fa fa-fw fa-arrow-circle-right"></i>
			        </button>

		      	</div>

		    {!! __html::filter_close('submit_document_filter') !!}


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
			    <th>@sortablelink('file_name', 'Filename')</th>
			    <th>@sortablelink('file_size', 'Size')</th>
			    <th>@sortablelink('updated_at', 'Date Updated')</th>
            	<th style="width: 150px">Action</th>
			  </tr>
			  @foreach($documents as $data) 
			    <tr {!! __html::table_highlighter($data->slug, $table_sessions) !!}>
                  <?php
                   	$design = design($data->file_ext);
                  ?>
			      <td id="mid-vert"><i class="fa {{ $design }}"></i>&nbsp; {{ $data->file_name }}</td>
			      <td id="mid-vert">{{ number_format($data->file_size / 1000)}} KB</td>
			      <td id="mid-vert">{{ __dataType::date_parse($data->updated_at, 'M d, Y - g:i A') }}</td>
	              <td id="mid-vert">
	                <div class="btn-group">
	                  <a type="button" class="btn btn-default" id="show_button" href="{{ route('guest.document.show', $data->slug) }}">
	                    <i class="fa fa-info-circle"></i>
	                  </a>
	                  <a type="button" class="btn btn-default" id="delete_button" data-action="delete" data-url="{{ route('guest.document.destroy', $data->slug) }}">
	                    <i class="fa fa-trash"></i>
	                  </a>
	                </div>
	              </td>
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



@section('modals')

  {!! __html::modal_delete('document_delete') !!}

@endsection 





@section('scripts')

  <script type="text/javascript">

    {{-- CALL CONFIRM DELETE MODAL --}}
    {!! __js::button_modal_confirm_delete_caller('document_delete') !!}

    {{-- DELETE TOAST --}}
    @if(Session::has('DOCUMENT_DELETE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_DELETE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- UPDATE TOAST --}}
    @if(Session::has('DOCUMENT_UPDATE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_UPDATE_SUCCESS'), 'bottom-right') !!}
    @endif

  </script>
    
@endsection