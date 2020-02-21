<?php

namespace App\Core\Interfaces;
 


interface DocumentInterface {

	public function fetch($request);

	public function fetchDeleted($request);

	public function store($request, $data, $file_ext, $file_location);

	public function update($request, $file_name, $file_size, $file_location, $document);

	public function findBySlug($slug);

	public function destroy($slug);

	public function destroyHard($document);

	public function restore($slug);

}
