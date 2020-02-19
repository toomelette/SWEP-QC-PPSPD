@extends('layouts.admin-master')

@section('content')
    
  <section class="content-header">
      <h1>Add Document</h1>
  </section>

  <section class="content">

    <div class="box">
    
      <div class="box-header with-border">
        <h3 class="box-title">Form</h3>
        <div class="pull-right">
            <code>Fields with asterisks(*) are required</code>
        </div> 
      </div>
      
      <form role="form" method="POST" autocomplete="off" action="{{ route('dashboard.document.store') }}" enctype="multipart/form-data">

        <div class="box-body">
     
          @csrf

          {!! __form::file(
             '6', 'doc_file', 'Upload File *', $errors->has('doc_file'), $errors->first('doc_file'), ''
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

    {!! __js::pdf_upload('doc_file', 'fa', '') !!}

  </script> 
    
@endsection