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
	        <h3 class="box-title">Add Document</h3>
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





@section('scripts')

  <script type="text/javascript">

    @if(Session::has('DOCUMENT_CREATE_SUCCESS'))
      {!! __js::toast(Session::get('DOCUMENT_CREATE_SUCCESS'), 'bottom-right') !!}
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
    