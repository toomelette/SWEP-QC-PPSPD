<?php

namespace App\Core\Repositories;
 
use App\Core\BaseClasses\BaseRepository;
use App\Core\Interfaces\DocumentInterface;

use App\Models\Document;


class DocumentRepository extends BaseRepository implements DocumentInterface {
	


    protected $document;



	public function __construct(Document $document){

        $this->document = $document;
        parent::__construct();

    }





    public function fetchGuest($request){

        $key = str_slug($request->fullUrl(), '_');
        $entries = isset($request->e) ? $request->e : 100;

        $documents = $this->cache->remember('documents:fetchGuest:' . $key, 240, function() use ($request, $entries){

            $document = $this->document->newQuery();
            
            if(isset($request->q)){
                $this->search($document, $request->q);
            }

            return $this->populate($document, $entries);

        });

        return $documents;

    }







    public function store($request, $file_location){

        $document = new document;
        $document->slug = $this->str->random(32);
        $document->document_id = $this->getDocumentIdInc();
        $document->file_name = $request->file('doc_file')->getClientOriginalName();
        $document->file_location = $file_location;
        $document->is_deleted = 0;
        $document->created_at = $this->carbon->now();
        $document->updated_at = $this->carbon->now();
        $document->ip_created = request()->ip();
        $document->ip_updated = request()->ip();
        $document->user_created = $this->auth->user()->user_id;
        $document->user_updated = $this->auth->user()->user_id;
        $document->save();

        return $document;

    }






    public function search($model, $key){

        return $model->where(function ($model) use ($key) {
                $model->where('file_name', 'LIKE', '%'. $key .'%');
        });

    }





    public function populate($model, $entries){

        return $model->select('slug', 'file_name', 'file_location', 'updated_at')
                     ->where('is_deleted', 0)
                     ->sortable()
                     ->orderBy('updated_at', 'desc')
                     ->paginate($entries);

    }






    public function getDocumentIdInc(){

        $id = 'DOC10000001';

        $document = $this->document->select('document_id')->orderBy('document_id', 'desc')->first();

        if($document != null){
            
            if($document->document_id != null){
                $num = str_replace('DOC', '', $document->document_id) + 1;
                $id = 'DOC' . $num;
            }
        
        }
        
        return $id;
        
    }





}