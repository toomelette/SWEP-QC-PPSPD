<?php

namespace App\Http\Controllers;


use App\Core\Services\FolderService;
use App\Http\Requests\Folder\FolderCreateFormRequest;
use App\Http\Requests\Folder\FolderEditFormRequest;
use App\Http\Requests\Folder\FolderFilterRequest;



class FolderController extends Controller{


    protected $folder;


    public function __construct(FolderService $folder){
        $this->folder = $folder;
    }

    
    public function index(FolderFilterRequest $request){
        return $this->folder->fetch($request);
    }


    public function store(FolderCreateFormRequest $request){
        return $this->folder->store($request);
    }
 

    public function edit($slug){
        return $this->folder->edit($slug);
    }


    public function update(FolderEditFormRequest $request, $slug){
        return $this->folder->update($request, $slug);
    }


    public function destroy($slug){
        return $this->folder->destroy($slug);
    }



}
