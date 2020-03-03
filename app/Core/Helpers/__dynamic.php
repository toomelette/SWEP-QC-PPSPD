<?php

namespace App\Core\Helpers;


use Carbon\Carbon;


class __dynamic{





    public static function months_between_dates($start, $end){

      $start = Carbon::parse($start);
      $end   = Carbon::parse($end);
      $start = $start->subMonths(1);

      $months = [];
      $i = 0;

      while ($start->addMonth() <= $end){
        $months[$i] = $start->format('F Y');
        $i++;
      }

      return $months;

    }





    public static function days_between_dates($start_date, $end_date){

    	$start_date = Carbon::parse($start_date);
      	$end_date   = Carbon::parse($end_date);

      	$dates = [];

	    for($date = $start_date; $date->lte($end_date); $date->addDay()) {

	        $dates[] = $date->format('d');

	    }

	    return $dates;

    }







}