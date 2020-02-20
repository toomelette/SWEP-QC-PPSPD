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
	        <h3 class="box-title"  style="padding-top: 5px;">Edit Document</h3>
	        <div class="pull-right">
	            <code>Fields with asterisks(*) are required</code>
	            &nbsp;
	            {!! __html::back_button(['guest.document.index']) !!}
	        </div> 
	      </div>
	      
	      <form role="form" method="POST" autocomplete="off" action="{{ route('guest.document.update', $document->slug) }}" enctype="multipart/form-data">

	        <div class="box-body">

              <input name="_method" value="PUT" type="hidden">
	     
	          @csrf

	          {!! __form::file(
	             '6', 'doc_file', 'Upload File *', $errors->has('doc_file'), $errors->first('doc_file'), ''
	          ) !!} 

              {!! __form::textbox(
                '6', 'folder', 'text', 'Folder', 'Folder', old('folder') ? old('folder') : $document->folder_name, $errors->has('folder'), $errors->first('folder'), ''
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
      {!! __js::toast(Session::get('DOCUMENT_CREATE_SUCCESS')) !!}
    @endif

    {!! __js::pdf_upload('doc_file', 'fa', route('guest.document.view_file', $document->slug)) !!}
    

    var folders = {!! json_encode($folders) !!};

    $('#folder').autocomplete({ 
      source: folders,
    });

  </script> 

 @endsection
    