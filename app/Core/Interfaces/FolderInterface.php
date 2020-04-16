<?php

namespace App\Core\Interfaces;
 


interface FolderInterface {

	public function fetch($request);

	public function store($request);

	public function update($request, $slug);

	public function destroy($slug);

	public function getAll();

	public function getBySlug($slug);
		
}