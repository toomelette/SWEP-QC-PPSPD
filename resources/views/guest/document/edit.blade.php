<?php
   
   $folders = Storage::directories();

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

    $("#doc_file").fileinput({
		maxFileCount: 1,
	    showUpload: false,
	    showCaption: false,
	    overwriteInitial: true,
	    initialPreview: [
	        "{{ route('guest.document.view_file', $document->slug) }}",
	    ],
	    initialPreviewConfig: [
	    { 
	        @if($document->file_ext == "pdf")
	        	type: "pdf",
	       	@else
	       		type: "office", 
	        @endif
	        caption: "{{ $document->file_name }}", 
	        size: "{{ $document->file_size }}", 
	        width: "100%", 
	        key: 1 
	    },
	    ],
	    initialPreviewAsData: true,
    	preferIconicPreview: true,
	    previewFileIconSettings: { 
	        'doc': '<i class="fa fa-file"></i>',
	        'xls': '<i class="fa fa-file"></i>',
	        'ppt': '<i class="fa fa-file"></i>',
	        'pdf': '<i class="fa fa-file"></i>',
	        'zip': '<i class="fa fa-file"></i>',
	        'htm': '<i class="fa fa-file"></i>',
	        'txt': '<i class="fa fa-file"></i>',
	        'mov': '<i class="fa fa-file"></i>',
	        'mp3': '<i class="fa fa-file"></i>',
	        'jpg': '<i class="fa fa-file"></i>', 
	        'gif': '<i class="fa fa-file"></i>', 
	        'png': '<i class="fa fa-file"></i>',
	    },
	    previewFileExtSettings: {
	        'doc': function(ext) {
	            return ext.match(/(doc|docx)$/i);
	        },
	        'xls': function(ext) {
	            return ext.match(/(xls|xlsx)$/i);
	        },
	        'ppt': function(ext) {
	            return ext.match(/(ppt|pptx)$/i);
	        },
	        'zip': function(ext) {
	            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
	        },
	        'htm': function(ext) {
	            return ext.match(/(htm|html)$/i);
	        },
	        'txt': function(ext) {
	            return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
	        },
	        'mov': function(ext) {
	            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
	        },
	        'mp3': function(ext) {
	            return ext.match(/(mp3|wav)$/i);
	        }
	    },
	}); 
	$(".kv-file-remove").hide();

    var folders = {!! json_encode($folders) !!};
    $('#folder').autocomplete({ 
      source: folders,
    });

  </script> 

 @endsection
    