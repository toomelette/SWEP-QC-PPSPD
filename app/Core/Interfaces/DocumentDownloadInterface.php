<?php

namespace App\Core\Interfaces;
 


interface DocumentDownloadInterface {

	public function fetch($request);

	public function store($document);
		
}