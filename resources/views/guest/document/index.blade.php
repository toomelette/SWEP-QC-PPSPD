<?php

  $table_sessions = [ Session::get('DOCUMENT_UPDATE_SUCCESS_SLUG'),
  					  Session::get('DOCUMENT_UPDATE_FOLDER_SUCCESS_SLUG'), ];

  $appended_requests = [
                        'q'=> Request::get('q'),
                        'sort' => Request::get('sort'),
                        'direction' => Request::get('direction'),

		                'alpha' => Request::get('alpha'),
		                'fc' => Request::get('fc'),
		                'df' => Request::get('df'),
		                'dt' => Request::get('dt'),
		                'file_ext' => Request::get('file_ext'),
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

   $filetypes = [
   	'pdf' => 'PDF',
   	'word' => 'Word', 
   	'excel' => 'Excel', 
   	'ppt' => 'PowerPoint', 
   	'pub' => 'Publisher', 
   	'img' => 'Image',
	];

?>


@extends('layouts.guest-master')

@section('content')

<section class="content">

<form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('guest.document.index') }}">

    <div class="col-md-3">

	    <div class="box box-solid">
	      <div class="box-header with-border">
	        <h2 class="box-title">Filters</h2>
	      </div>
			<div class="box-body">

			    {!! __form::select_static_for_filter(
			      '12', 'alpha', 'Alphabetical', old('pi'), $alphas, 'submit_document_filter', '', ''
			    ) !!}

			    {!! __form::select_dynamic_for_filter(
			      '12', 'fc', 'Folder', old('fc'), $global_folders_all, 'folder_code', 'folder_code', 'submit_document_filter', 'select2', 'style="width:100%;"'
			    ) !!}

	            <div class="checkbox col-md-12">
	              <span>Filetypes:</span><br>
	              @foreach($filetypes as $key => $data)
		              <label>
		                <input type="checkbox" class="minimal file_ext" name="file_ext" value="{{ $key }}" {{ old('file_ext') == $key ? 'checked' : '' }}>
		                	&nbsp; {{ $data }}
		              </label><br>
	              @endforeach
	            </div>

	      	</div>
	    </div>	

	    <div class="box box-solid">
	      <div class="box-header with-border">
	        <h2 class="box-title">Date Filter</h2>
	      </div>
			<div class="box-body">
		        {!! __form::datepicker('12', 'df',  'From', old('df'), '', '') !!}
		        {!! __form::datepicker('12', 'dt',  'To', old('dt'), '', '') !!}
		        <button type="submit" class="btn btn-primary">
		        	Filter Date <i class="fa fa-fw fa-arrow-circle-right"></i>
		        </button>
	      		<button type="submit" id="submit_document_filter" style="display:none;">Filter</button>
	      	</div>
	    </div>

    </div>


    <div class="col-md-9">
		<div class="box box-solid" id="pjax-container" style="overflow-x:auto;">
			<div class="box-header with-border">
				{!! __html::table_search(route('guest.document.index')) !!}
			</div>
</form>

			{{-- Table Grid --}}        
			<div class="box-body no-padding">
				<table class="table table-hover">
				  <tr>
				    <th>@sortablelink('file_name', 'Filename')</th>
				    <th>@sortablelink('folder_code', 'Folder')</th>
				    <th>@sortablelink('file_size', 'Size')</th>
				    <th>@sortablelink('updated_at', 'Date Updated')</th>
	            	<th>Action</th>
				  </tr>
				  @foreach($documents as $data) 
				    <tr {!! __html::table_highlighter($data->slug, $table_sessions) !!}>
	                  <?php
	                   	$design = design($data->file_ext);
	                  ?>
				      <td id="mid-vert"><i class="fa {{ $design }}"></i>&nbsp; {{ $data->file_name }}</td>
				      <td id="mid-vert">{{ $data->folder_code }}</td>
				      <td id="mid-vert">{{ number_format($data->file_size / 1000)}} KB</td>
				      <td id="mid-vert">{{ __dataType::date_parse($data->updated_at, 'M d, Y - g:i A') }}</td>
		              <td id="mid-vert">
		                <div class="btn-group">
		                  <a type="button" 
		                  	 class="btn btn-default" 
		                  	 id="update_folder_button" 
		                  	 data-action="update_folder" 
		                  	 data-url="{{ route('guest.document.update_folder', $data->slug) }}"
		                  	 data-folder_code="{{ $data->folder_code }}">
		                    Change Folder
		                  </a>
		                  <a type="button" 
		                  	 class="btn btn-default" 
		                  	 id="show_button" 
		                  	 href="{{ route('guest.document.show', $data->slug) }}">
		                    <i class="fa fa-download"></i>
		                  </a>
		                  <a type="button" 
		                  	 class="btn btn-default" 
		                  	 id="delete_button" 
		                  	 data-action="delete" 
		                  	 data-url="{{ route('guest.document.destroy', $data->slug) }}">
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
    </div>

</section>

@endsection



@section('modals')

  {!! __html::modal_delete('document_delete') !!}

  <div class="modal fade" id="update_folder" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body" id="update_folder_body"style="margin-top:100px:">
         <p style="font-size: 17px;">Change Folder</p>
         <form method="POST" id="update_folder_form">
            @csrf
            <input name="_method" value="PUT" type="hidden">

            <div class="row">

	            {!! __form::select_dynamic(
	              '12', 'folder_code', 'Folder', old('folder_code'), $global_folders_all, 'folder_code', 'folder_code', $errors->has('folder_code'), $errors->first('folder_code'), 'select2', 'style="width:100%;"'
	            ) !!}
            	
            </div>

          </div>
          <div class="modal-footer">
            <button class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-default">Save</button>
       	  </div>
          </form>
      </div>
    </div>
  </div>

@endsection 





@section('scripts')

  <script type="text/javascript">

  	$(document).on("click", "#update_folder_button", function () {
		$('.select2').select2();
		$("#update_folder").modal("show");
		$("#update_folder_body #update_folder_form").attr("action", $(this).data("url"));
		$("#update_folder_form #folder_code").val($(this).data("folder_code")).change();
	});

    {{-- CALL CONFIRM DELETE MODAL --}}
    {!! __js::button_modal_confirm_delete_caller('document_delete') !!}

    $('.file_ext').on('ifChecked', function(event){
      $('input[type="checkbox"]').not(this).iCheck('uncheck');
      $('#submit_document_filter').click();
    });

    $('.file_ext').on('ifUnchecked', function(event){
      $('#submit_document_filter').click();
    });

    {{-- DELETE TOAST --}}
    @if(Session::has('DOCUMENT_DELETE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_DELETE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- UPDATE TOAST --}}
    @if(Session::has('DOCUMENT_UPDATE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_UPDATE_SUCCESS'), 'bottom-right') !!}
    @endif

    {{-- UPDATE FOLDER --}}
    @if(Session::has('DOCUMENT_UPDATE_FOLDER_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_UPDATE_FOLDER_SUCCESS'), 'bottom-right') !!}
    @endif

  </script>
    
@endsection