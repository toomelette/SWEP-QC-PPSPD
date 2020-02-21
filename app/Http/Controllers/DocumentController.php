<?php

namespace App\Http\Controllers;

use App\Core\Services\DocumentService;
use App\Http\Requests\Document\DocumentFormRequest;
use App\Http\Requests\Document\DocumentFilterRequest;

class DocumentController extends Controller{



    protected $document;



    public function __construct(DocumentService $document){

        $this->document = $document;

    }



    public function index(DocumentFilterRequest $request){
        
        return $this->document->fetch($request);

    }



    public function archives(DocumentFilterRequest $request){
        
        return $this->document->fetchArchives($request);

    }

    

    public function create(){
        
        return view('guest.document.create');

    }

   

    public function store(DocumentFormRequest $request){
        
        return $this->document->store($request);

    }
 



    public function edit($slug){
        
        return $this->document->edit($slug);

    }
 



    public function update(DocumentFormRequest $request, $slug){
        
        return $this->document->update($request, $slug);

    }
 



    public function viewFile($slug){
        
        return $this->document->viewFile($slug);

    }

    


    public function destroy($slug){
        
        return $this->document->destroy($slug);

    }

    


    public function destroyHard($slug){
        
        return $this->document->destroyHard($slug);

    }

    


    public function restore($slug){
        
        return $this->document->restore($slug);

    }




}