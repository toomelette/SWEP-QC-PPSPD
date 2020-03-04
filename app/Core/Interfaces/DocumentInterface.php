<?php

namespace App\Core\Interfaces;
 


interface DocumentInterface {

	public function fetch($request);

	public function fetchDeleted($request);

	public function store($request, $data, $file_ext, $file_location, $is_deleted, $is_duplicate);

	public function update($request, $file_name, $file_size, $file_location, $document);

	public function findBySlug($slug);

	public function getFirstDuplicate();

	public function getByFileName($filename);

	public function isFileNameExist($filename);

	public function destroy($slug);

	public function destroyHard($document);

	public function restore($slug);

	public function overwriteReplace($document);

	public function overwriteKeepBoth($document, $file_name);

	public function getByDateScope($request);

	public function getAll();

}
