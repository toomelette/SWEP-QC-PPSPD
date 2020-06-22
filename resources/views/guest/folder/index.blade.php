<?php

  $table_sessions = [ Session::get('FOLDER_UPDATE_SUCCESS_SLUG'), ];

  $appended_requests = [
                        'q'=> Request::get('q'),
                        'sort' => Request::get('sort'),
                        'direction' => Request::get('direction'),
                      ];

?>


@extends('layouts.guest-master')

@section('content')

<section class="content">

    <div class="col-md-3">

	    <div class="box box-solid">
	      <div class="box-header with-border">
	        <h2 class="box-title">Add Folder</h2>
	      </div>
			<div class="box-body">

			    <form class="" method="POST" autocomplete="off" action="{{ route('guest.folder.store') }}">
			    		
			    	@csrf

			        {!! __form::textbox(
			        	'12', 'folder_code', 'text', 'Folder Code *', 'Folder Code', old('folder_code'), $errors->has('folder_code'), $errors->first('folder_code'), ''
			        ) !!}   

			        {!! __form::textbox(
			        	'12', 'description', 'text', 'Description', 'Description', old('description'), $errors->has('description'), $errors->first('description'), ''
			        ) !!} 

			         <button class="btn btn-default">Save <i class="fa fa-fw fa-save"></i></button>

			    </form>

	      	</div>
	    </div>	

    </div>


    <div class="col-md-9">
		<div class="box box-solid" id="pjax-container" style="overflow-x:auto;">

			<form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('guest.folder.index') }}">
				<div class="box-header with-border">
					{!! __html::table_search(route('guest.folder.index')) !!}
				</div>
			</form>

			{{-- Table Grid --}}        
			<div class="box-body no-padding">
				<table class="table table-hover">
				  <tr>
				    <th>@sortablelink('folder_code', 'Folder Code')</th>
				    <th>@sortablelink('description', 'Description')</th>
	            	<th style="width: 150px">Action</th>
				  </tr>
				  @foreach($folders as $data) 
				    <tr {!! __html::table_highlighter($data->slug, $table_sessions) !!}>
				      <td id="mid-vert">{{ $data->folder_code }}</td>
				      <td id="mid-vert">{{ $data->description }}</td>
		              <td id="mid-vert">
		                <div class="btn-group">
		                  <a type="button" class="btn btn-default" id="edit_button" fs="{{ $data->slug }}" data-url="{{ route('guest.folder.update', $data->slug) }}">
		                    <i class="fa fa-pencil"></i>
		                  </a>
		                  <a type="button" class="btn btn-default" id="delete_button" data-action="delete" data-url="{{ route('guest.folder.destroy', $data->slug) }}">
		                    <i class="fa fa-trash"></i>
		                  </a>
		                </div>
		              </td>
				    </tr>
				  @endforeach
				 </table>
			</div>
			@if($folders->isEmpty())
				<div style="padding :5px;">
				  <center><h4>No Records found!</h4></center>
				</div>
			@endif
			<div class="box-footer">
				{!! __html::table_counter($folders) !!}
				{!! $folders->appends($appended_requests)->render('vendor.pagination.bootstrap-4')!!}
			</div>
		</div>
    </div>

</section>

@endsection



@section('modals')

  {!! __html::modal_delete('folder_delete') !!}

  <div class="modal fade" id="folder_update" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body" id="update_body"style="margin-bottom:150px; margin-top:100px:">
         <p style="font-size: 17px;">Edit Folder</p>
         <form method="POST" id="update_form">
            @csrf
            <input name="_method" value="PUT" type="hidden">

	        {!! __form::textbox(
	        	'12', 'e_folder_code', 'text', 'Folder Code *', 'Folder Code', old('e_folder_code'), $errors->has('e_folder_code'), $errors->first('e_folder_code'), 'required'
	        ) !!}   

	        {!! __form::textbox(
	        	'12', 'e_description', 'text', 'Description', 'Description', old('e_description'), $errors->has('e_description'), $errors->first('e_description'), ''
	        ) !!} 

          </div>
          <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-default">Update</button>
       	  </div>
          </form>
      </div>
    </div>
  </div>

@endsection 





@section('scripts')

  <script type="text/javascript"> 

  	$(document).on("click", "#edit_button", function () {

		var slug = $(this).attr("fs");
		$("#folder_update").modal("show");
		$("#update_body #update_form").attr("action", $(this).data("url"));

		$.ajax({
			headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
			url: "/api/folder/"+slug+"/edit",
			type: "GET",
			dataType: "json",
			success:function(data) {       
			  $.each(data, function(key, value) {
			    $("#update_form #e_folder_code").val(value.folder_code);
			    $("#update_form #e_description").val(value.description);
			  });
			}

		});
	});

    {{-- CALL CONFIRM DELETE MODAL --}}
    {!! __js::button_modal_confirm_delete_caller('folder_delete') !!}

    {{-- CREATE TOAST --}}
    @if(Session::has('FOLDER_CREATE_SUCCESS'))
      {!! __js::toast(Session::get('FOLDER_CREATE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- UPDATE TOAST --}}
    @if(Session::has('FOLDER_UPDATE_SUCCESS'))
      {!! __js::toast(Session::get('FOLDER_UPDATE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- DELETE TOAST --}}
    @if(Session::has('FOLDER_DELETE_SUCCESS'))
      {!! __js::toast(Session::get('FOLDER_DELETE_SUCCESS'), 'bottom-right') !!}
    @endif

  </script>
    
@endsection