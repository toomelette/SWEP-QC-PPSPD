<?php

   $years = ['2020'];
   $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

?>

@extends('layouts.guest-master')


@section('extras')
  <link type="text/css" rel="stylesheet" href="{{ asset('template/plugins/jquery-ui/jquery-ui.css') }}">
@endsection


@section('content')

	<section class="content">

	    <div class="box box-solid">
	    
			<div class="box-header with-border">
				<h3 class="box-title">Documents Imported per Year</h3>
			</div>

	        <table class="table table-bordered">

	          	<thead>
		          	<tr>
		            	<th>Year</th>
		            	<th>Jan</th>
		            	<th>Feb</th>
		            	<th>Mar</th>
		            	<th>Apr</th>
		            	<th>May</th>
		            	<th>Jun</th>
		            	<th>Jul</th>
		            	<th>Aug</th>
		            	<th>Sep</th>
		            	<th>Oct</th>
		            	<th>Nov</th>
		            	<th>Dec</th>
		            	<th>Total</th>
		          	</tr>
	          	</thead>

	          	<tbody>

	          		@foreach ($years as $data_year)

	          			<tr>

	          				<?php $count_total = 0; ?>

	          				<td>{{ $data_year }}</td>

			          		@foreach ($months as $data_month)
	          				
			          			<?php $count = 0; ?>

			          			@foreach ($documents as $data_doc)

			          				<?php

			          					$year_created = __dataType::date_parse($data_doc->created_at, 'Y');
			          					$month_created = __dataType::date_parse($data_doc->created_at, 'm');
			          				
			          				?>

			          				@if ($year_created == $data_year && $month_created == $data_month)
			          					
			          					<?php $count++; ?>

			          				@endif
			          				
			          			@endforeach

			          			<td>{{ $count }}</td>

			          			<?php $count_total += $count ?>

			          		@endforeach

			          		<td><b>{{ $count_total }}</b></td>	

	          			</tr>	

	          		@endforeach

	          	</tbody>

	        </table>

	        <div class="box-footer">
	          <a href="{{ route('guest.document.reports_print') }}" type="button" class="btn btn-default" target="__blank">Print <i class="fa fa-fw fa-print"></i></a>
	        </div>

	    </div>

	 </section>

@endsection
    