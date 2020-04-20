<?php

  $appended_requests = [
                        'q'=> Request::get('q'),
                        'sort' => Request::get('sort'),
                        'direction' => Request::get('direction'),
                      ];

?>




@extends('layouts.admin-master')

@section('content')
    
  <section class="content-header">
      <h1>Downloads</h1>
  </section>

  <section class="content">
    
    {{-- Form Start --}}
    <form data-pjax class="form" id="filter_form" method="GET" autocomplete="off" action="{{ route('dashboard.home') }}">

    <div class="box box-solid" id="pjax-container" style="overflow-x:auto;">

      {{-- Table Search --}}        
      <div class="box-header with-border">
        {!! __html::table_search(route('dashboard.home')) !!}
      </div>

    {{-- Form End --}}  
    </form>

      {{-- Table Grid --}}        
      <div class="box-body no-padding">
        <table class="table table-hover">
          <tr>
            <th>@sortablelink('document.file_name', 'Name')</th>
            <th>@sortablelink('downloaded_at', 'Date')</th>
            <th>@sortablelink('ip_downloaded', 'IP Address')</th>
            <th>@sortablelink('machine_downloaded', 'Machine Name')</th>
          </tr>
          @foreach($document_downloads as $data) 
            <tr>
              <td id="mid-vert">{{ $data->file_name }}</td>
              <td id="mid-vert">{{ __dataType::date_parse($data->downloaded_at, 'M d,Y H:i:s') }}</td>
              <td id="mid-vert">{{ $data->ip_downloaded }}</td>
              <td id="mid-vert">{{ $data->machine_downloaded }}</td>
            </tr>
            @endforeach
          </table>
      </div>

      @if($document_downloads->isEmpty())
        <div style="padding :5px;">
          <center><h4>No Records found!</h4></center>
        </div>
      @endif

      <div class="box-footer">
        {!! __html::table_counter($document_downloads) !!}
        {!! $document_downloads->appends($appended_requests)->render('vendor.pagination.bootstrap-4')!!}
      </div>

    </div>

  </section>

@endsection