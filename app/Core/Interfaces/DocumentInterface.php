<?php

namespace App\Core\Interfaces;
 


interface DocumentInterface {

	public function fetchGuest($request);

	public function store($request, $file_location);

}
