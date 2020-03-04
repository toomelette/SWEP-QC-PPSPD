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
	        <h3 class="box-title">Import Document</h3>
	      </div>
	      
	      <form role="form" method="POST" autocomplete="off" action="{{ route('guest.document.store') }}" enctype="multipart/form-data">

	        <div class="box-body">
	     
	          @csrf

	          <div class="col-md-12" style="margin-bottom:20px;">
                  <small class="text-danger">{{ $errors->has('doc_file') ? $errors->first('doc_file') : '' }}</small>
		          <div class="file-loading">
					   <input id="doc_file" name="doc_file[]" type="file" multiple>
				  </div>	
	          </div>
		          
	        </div>

	        <div class="box-footer">
	          <button type="submit" class="btn btn-default">Save <i class="fa fa-fw fa-save"></i></button>
	        </div>

	      </form>

	    </div>

	 </section>

@endsection



@section('modals')

  {{-- SET DV NO Modal --}}
  <div class="modal fade" id="document_duplicate" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Overwrite File?</h4>
        </div>
        <div class="modal-body no-padding">

        	<table class="table table-hover">
			  <tr>
			    <th>Filename</th>
			    <th>Size</th>
			    <th>Last Modified</th>
			  </tr>

			  @if(Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE'))
			  	<?php 
			  		$imported_file = Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE');
			  	?>
			    <tr {!! $imported_file->is_duplicate == 0 ? 'style="background-color: #D5F5E3;"' : 'style="background-color: #F5B7B1;"' !!} >
			      <td id="mid-vert">
	                  <a href="{{ route('guest.document.view_file', $imported_file->slug) }}" target="_blank">
	                    {{ $imported_file->file_name }}
	                  </a>
	              </td>
			      <td id="mid-vert">{{ number_format($imported_file->file_size / 1000)}} KB</td>
			      <td id="mid-vert">{{ __dataType::date_parse($imported_file->updated_at, 'M d, Y - g:i A') }}</td>
			    </tr>
			  @endif


			  @if(Session::get('DOCUMENT_CREATE_HAS_DUPLICATED_FILE'))
			  	<?php 
			  		$duplicated_file = Session::get('DOCUMENT_CREATE_HAS_DUPLICATED_FILE');
			  	?>
			    <tr {!! $duplicated_file->is_duplicate == 0 ? 'style="background-color: #D5F5E3;"' : 'style="background-color: #F5B7B1;"' !!} >
			      <td id="mid-vert">
	                  <a href="{{ route('guest.document.view_file', $duplicated_file->slug) }}" target="_blank">
	                    {{ $duplicated_file->file_name }}
	                  </a>
	              </td>
			      <td id="mid-vert">{{ number_format($duplicated_file->file_size / 1000)}} KB</td>
			      <td id="mid-vert">{{ __dataType::date_parse($duplicated_file->updated_at, 'M d, Y - g:i A') }}</td>
			    </tr>
			  @endif

			</table>

        </div>
        <div class="modal-footer">

          @if(Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE'))
          	  <?php
          	  	$imported_file = Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE');
          	  ?>
	          <button id ="overwrite_replace"class="btn btn-default" data-url="{{ route('guest.document.overwriteReplace', $imported_file->slug) }}">
	          	<i class="fa fa-fw fa-check"></i> Replace
	          </button>
          @endif

          @if(Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE'))
	      	  <?php
	      	  	$imported_file = Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE');
	      	  ?>
	          <button id ="overwrite_skip"class="btn btn-default" data-url="{{ route('guest.document.overwriteSkip', $imported_file->slug) }}">
	          	<i class="fa fa-fw fa-arrow-right"></i> Skip
	          </button>
          @endif

          @if(Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE'))
	      	  <?php
	      	  	$imported_file = Session::get('DOCUMENT_CREATE_HAS_IMPORTED_FILE');
	      	  ?>
	          <button id ="overwrite_keep_both"class="btn btn-default" data-url="{{ route('guest.document.overwriteKeepBoth', $imported_file->slug) }}">
	          	<i class="fa fa-fw fa-copy"></i> Keep Both
	          </button>
          @endif

        </div>
      </div>
    </div>
  </div>

  	<form id="frm-overwrite_replace" method="POST" style="display: none;">
		@csrf
	</form>

	<form id="frm-overwrite_skip" method="POST" style="display: none;">
		@csrf
	</form>

	<form id="frm-overwrite_keep_both" method="POST" style="display: none;">
		@csrf
	</form>

@endsection



@section('scripts')

  <script type="text/javascript">

  	$(document).on("click", "#overwrite_replace", function () {
        $("#frm-overwrite_replace").attr("action", $(this).data("url"));
        $("#frm-overwrite_replace").submit();
    });

  	$(document).on("click", "#overwrite_skip", function () {
        $("#frm-overwrite_skip").attr("action", $(this).data("url"));
        $("#frm-overwrite_skip").submit();
    });

  	$(document).on("click", "#overwrite_keep_both", function () {
        $("#frm-overwrite_keep_both").attr("action", $(this).data("url"));
        $("#frm-overwrite_keep_both").submit();
    });

    @if(Session::has('DOCUMENT_CREATE_SUCCESS'))
       {!! __js::toast(Session::get('DOCUMENT_CREATE_SUCCESS'), 'bottom-right') !!}
    @endif

    @if(Session::has('DOCUMENT_CREATE_HAS_DUPLICATE'))
	    $( document ).ready(function() {
	    	$('#document_duplicate').modal('show'); 
	    	$("#document_duplicate_form").attr("action", $(this).data("url"));
		});
    @endif

    $("#doc_file").fileinput({
        showUpload: false,
        showCaption: false,
        maxFileCount: 100,
        browseClass: "btn btn-primary btn-md",
    }); 

    var folders = {!! json_encode($folders) !!};
    $('#folder').autocomplete({ 
      source: folders,
    });

  </script> 

 @endsection
    