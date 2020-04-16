<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Core\Interfaces\FolderInterface;

class ApiFolderController extends Controller{



	protected $folder_repo;



	public function __construct(FolderInterface $folder_repo){
		$this->folder_repo = $folder_repo;
	}



    public function edit(Request $request, $slug){

    	if($request->Ajax()){
    		$response_folder = $this->folder_repo->getBySlug($slug);
	    	return json_encode($response_folder);
	    }

	    return abort(404);

    }


    
}
