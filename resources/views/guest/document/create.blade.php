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
	        <div class="pull-right">
	            <code>Fields with asterisks(*) are required</code>
	        </div> 
	      </div>
	      
	      <form role="form" method="POST" autocomplete="off" action="{{ route('guest.document.store') }}" enctype="multipart/form-data">

	        <div class="box-body">
	     
	          @csrf

	          <div class="col-md-12" style="margin-bottom:20px;">
                  <small class="text-danger">{{ $errors->has('doc_file') ? $errors->first('doc_file') : '' }}</small>
		          <div class="file-loading">
					   <input id="doc_file" name="doc_file[]" multiple type="file" class="file" data-browse-on-zone-click="true">
				  </div>	
	          </div>

              {!! __form::textbox(
                '6', 'folder', 'text', 'Folder', 'Folder', old('folder'), $errors->has('folder'), $errors->first('folder'), ''
              ) !!}
		          
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
          <h4 class="modal-title">Duplicate Filename!</h4>
        </div>
        <div class="modal-body no-padding">

        	<table class="table table-hover">
			  <tr>
			    <th>Filename</th>
			    <th>Size</th>
			    <th>Last Updated</th>
            	<th style="width: 150px">Action</th>
			  </tr>
			  @if(Session::get('DOCUMENT_CREATE_HAS_DUPLICATE_LIST'))
				  @foreach(Session::get('DOCUMENT_CREATE_HAS_DUPLICATE_LIST') as $data) 
				    <tr {!! $data->is_duplicate == 0 ? 'style="background-color: #D5F5E3;"' : 'style="background-color: #F5B7B1;"' !!} >
				      <td id="mid-vert">
		                  <a href="{{ route('guest.document.view_file', $data->slug) }}" target="_blank">
		                    {{ $data->file_name }}
		                  </a>
		              </td>
				      <td id="mid-vert">{{ number_format($data->file_size / 1000)}} KB</td>
				      <td id="mid-vert">{{ __dataType::date_parse($data->updated_at, 'M d, Y - g:i A') }}</td>
		              <td id="mid-vert">
          				<button type="submit" class="btn btn-primary">Replace</button>
		              </td>
				    </tr>
				  @endforeach
			  @endif
			</table>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Ignore</button>
        </div>
      </div>
    </div>
  </div>

@endsection



@section('scripts')

  <script type="text/javascript">


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
        theme: "fa",
        showUpload: false,
        showCaption: false,
	    overwriteInitial: false,
        browseClass: "btn btn-primary btn-md",
    }); 


    var folders = {!! json_encode($folders) !!};
    $('#folder').autocomplete({ 
      source: folders,
    });


  </script> 

 @endsection
    