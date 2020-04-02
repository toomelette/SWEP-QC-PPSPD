<?php

	$table_sessions = [];

	$appended_requests = [
		                'q'=> Request::get('q'),
		                'sort' => Request::get('sort'),
		                'direction' => Request::get('direction'),

		                'df' => Request::get('df'),
		                'dt' => Request::get('dt'),
		                'alpha' => Request::get('alpha'),
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
		<h1>Search Archives</h1>
	</section>

	<section class="content">


		{{-- Form Start --}}
		<form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('guest.document.archives') }}">


			<div class="box box-solid" id="pjax-container" style="overflow-x:auto;">

			{{-- Table Search --}}        
			<div class="box-header with-border">
				{!! __html::table_search(route('guest.document.archives')) !!}
			</div>

		{{-- Form End --}}  
		</form>

		{{-- Table Grid --}}        
		<div class="box-body no-padding">
			<table class="table table-hover">
			  <tr>
			    <th>@sortablelink('file_name', 'Filename')</th>
			    <th>@sortablelink('file_size', 'Size')</th>
			    <th>@sortablelink('updated_at', 'Date Deleted')</th>
            	<th style="width: 150px">Action</th>
			  </tr>
			  @foreach($documents as $data) 
			    <tr>
                  <?php
                   	$design = design($data->file_ext);
                  ?>
			      <td id="mid-vert"><i class="fa {{ $design }}"></i>&nbsp; {{ $data->file_name }}</td>
			      <td id="mid-vert">{{ number_format($data->file_size / 1000) }} KB</td>
			      <td id="mid-vert">{{ __dataType::date_parse($data->updated_at, 'M d, Y - g:i A') }}</td>
	              <td id="mid-vert">
	                <div class="btn-group">
	                  <a type="button" class="btn btn-default" id="restore_button" data-action="restore" data-url="{{ route('guest.document.restore', $data->slug) }}">
	                    <i class="fa fa-refresh"></i>
	                  </a>
	                  <a type="button" class="btn btn-danger" id="delete_button" data-action="delete" data-url="{{ route('guest.document.destroy_hard', $data->slug) }}">
	                    <i class="fa fa-trash-o"></i>
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

	<form id="frm-restore" method="POST" style="display: none;">
		@csrf
	</form>

@endsection




@section('modals')

	<div class="modal fade" id="document_delete" data-backdrop="static">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button class="close" data-dismiss="modal">
	            <span aria-hidden="true">&times;</span>
	          </button>
	          <h4 class="modal-title"><i class="fa fa-exclamation-circle "></i> Delete ?</h4>
	        </div>
	        <div class="modal-body" id="delete_body">
	          <form method="POST" id="form">
	            @csrf
	            <input name="_method" value="DELETE" type="hidden">
	            <p style="font-size: 17px;">Are you sure, you want to <b>Permanently Delete</b> this record?</p>
	          </div>
	          <div class="modal-footer">
	            <button class="btn btn-default" data-dismiss="modal">Close</button>
	            <button type="submit" class="btn btn-danger">Delete</button>
	          </form>
	        </div>
	      </div>
	    </div>
	</div>

@endsection 




@section('scripts')

  <script type="text/javascript">

    {{-- CALL CONFIRM DELETE MODAL --}}
    {!! __js::button_modal_confirm_delete_caller('document_delete') !!}

    $(document).on("click", "#restore_button", function () {
      if($(this).data("action") == "restore"){
        $("#frm-restore").attr("action", $(this).data("url"));
        $("#frm-restore").submit();
      }
    });

    {{-- RESTORE TOAST --}}
    @if(Session::has('DOCUMENT_RESTORE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_RESTORE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- DELETE TOAST --}}
    @if(Session::has('DOCUMENT_DELETE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_DELETE_SUCCESS'), 'bottom-right') !!}
    @endif

  </script>
    
@endsection