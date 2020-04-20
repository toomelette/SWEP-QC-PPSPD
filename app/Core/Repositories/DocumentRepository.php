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





    public function fetch($request){

        $key = str_slug($request->fullUrl(), '_');
        $entries = isset($request->e) ? $request->e : 20;

        $documents = $this->cache->remember('documents:fetch:' . $key, 240, function() use ($request, $entries){

            $document = $this->document->newQuery();
            $df = $this->__dataType->date_parse($request->df, 'Y-m-d 00:00:00');
            $dt = $this->__dataType->date_parse($request->dt, 'Y-m-d 24:00:00');

            $WORD = ['doc', 'docm', 'docx', 'dot', 'dotm', 'dotx'];
            $EXCEL = ['xls', 'xlsx', 'xlsm', 'xlt', 'xltx', 'xltm', 'xla', 'xlam', 'csv'];
            $PPT = ['ppt', 'pptm', 'pptx', 'pps', 'ppsm', 'ppsx', 'pot', 'potm', 'potx'];
            $IMG = ['jpeg', 'jpg', 'png', 'ai', 'psd'];

            if(isset($request->q)){
                $this->search($document, $request->q);
            }

            if(isset($request->alpha)){
                $document->where('file_name', 'LIKE', $request->alpha .'%');
            }

            if(isset($request->fc)){
                $document->where('folder_code', 'LIKE', '%'. $request->fc .'%');
            }

            if (isset($request->file_ext)) {
                if ($request->file_ext == 'pdf') {
                    $document->where('file_ext', 'pdf');   
                }elseif ($request->file_ext == 'word') {
                    $document->whereIn('file_ext', $WORD);
                }elseif ($request->file_ext == 'excel') {
                    $document->whereIn('file_ext', $EXCEL);
                }elseif ($request->file_ext == 'ppt') {
                    $document->whereIn('file_ext', $PPT);
                }elseif ($request->file_ext == 'pub') {
                    $document->where('file_ext', 'pub');
                }elseif ($request->file_ext == 'img') {
                    $document->whereIn('file_ext', $IMG);
                }
            }

            if(isset($request->df) || isset($request->dt)){
                $document->where('updated_at','>=',$df)
                         ->where('updated_at','<=',$dt);
            }

            return $this->populate($document, $entries);

        });

        return $documents;

    }





    public function fetchDeleted($request){

        $key = str_slug($request->fullUrl(), '_');
        $entries = isset($request->e) ? $request->e : 20;

        $documents = $this->cache->remember('documents:fetchDeleted:' . $key, 240, function() use ($request, $entries){

            $document = $this->document->newQuery();
            
            if(isset($request->q)){
                $this->search($document, $request->q);
            }

            return $this->populateDeleted($document, $entries);

        });

        return $documents;

    }





    public function getByDateScope($request){

        $documents = $this->document->newQuery();
        $df = $this->__dataType->date_parse($request->df, 'Y-m-d 00:00:00');
        $dt = $this->__dataType->date_parse($request->dt, 'Y-m-d 24:00:00');

        if(isset($request->df) || isset($request->dt)){
            $documents->where('created_at','>=',$df)
                      ->where('created_at','<=',$dt);
        }
        
        return $documents->select('created_at')->where('is_deleted', 0)->where('is_duplicate', 0)->get();;

    }







    public function store($request, $data, $file_ext, $file_location, $is_deleted, $is_duplicate){

        $document = new document;
        $document->slug = $this->str->random(32);
        $document->document_id = $this->getDocumentIdInc();
        $document->folder_code = $request->folder_code;
        $document->file_name = $data->getClientOriginalName();
        $document->file_ext = $file_ext;
        $document->file_size = $data->getSize();
        $document->file_location = $file_location;
        $document->is_deleted = $is_deleted;
        $document->is_duplicate = $is_duplicate;
        $document->created_at = $this->carbon->now();
        $document->updated_at = $this->carbon->now();
        $document->ip_created = request()->ip();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }







    public function update($request, $file_name, $file_size, $file_location, $document){

        $document->file_name = $file_name;
        $document->file_size = $file_size;
        $document->file_location = $file_location;
        $document->created_at = $this->carbon->now();
        $document->updated_at = $this->carbon->now();
        $document->ip_created = request()->ip();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }





    public function destroy($slug){

        $document = $this->findBySlug($slug);
        $document->is_deleted = 1;
        $document->updated_at = $this->carbon->now();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }





    public function destroyHard($document){

        $document->delete();
        return $document;

    }





    public function restore($slug){

        $document = $this->findBySlug($slug);
        $document->is_deleted = 0;
        $document->updated_at = $this->carbon->now();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }





    public function overwriteReplace($document){

        $document->is_deleted = 0;
        $document->is_duplicate = 0;
        $document->updated_at = $this->carbon->now();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }





    public function overwriteKeepBoth($document, $file_name){

        $document->is_deleted = 0;
        $document->is_duplicate = 0;
        $document->file_name = $file_name;
        $document->updated_at = $this->carbon->now();
        $document->ip_updated = request()->ip();
        $document->save();

        return $document;

    }





    public function findBySlug($slug){

        $document = $this->cache->remember('documents:findBySlug:' . $slug, 240, function() use ($slug){
            return $this->document->where('slug', $slug)->first();
        });
        
        if(empty($document)){
            abort(404);
        }
        
        return $document;

    }





    public function getFirstDuplicate(){

        return $this->document->where('is_duplicate', 1)
                              ->where('is_deleted', 0)        
                              ->orderBy('updated_at', 'desc')
                              ->first();

    }





    public function getByFileName($file_name){

        return $this->document->where('file_name', $file_name)
                              ->where('is_deleted', 0)
                              ->where('is_duplicate', 0)
                              ->orderBy('updated_at', 'desc')
                              ->first();


    }





    public function isFileNameExist($filename){

        $document = $this->cache->remember('documents:findByFileName:' . $filename, 240, function() use ($filename){
            return $this->document->where('file_name', $filename)
                                  ->where('is_deleted', 0)
                                  ->first();
        });
        
        if(empty($document)){
            return false;
        }
        
        return true;

    }





    public function search($model, $key){

        return $model->where(function ($model) use ($key) {
                $model->where('file_name', 'LIKE', '%'. $key .'%');
        });

    }





    public function populate($model, $entries){

        return $model->select('slug', 'file_name', 'file_size', 'file_location', 'file_ext', 'updated_at')
                     ->where('is_deleted', 0)
                     ->where('is_duplicate', 0)
                     ->sortable()
                     ->orderBy('updated_at', 'desc')
                     ->paginate($entries);

    }





    public function populateDeleted($model, $entries){

        return $model->select('slug', 'file_name', 'file_size', 'file_location', 'file_ext', 'updated_at')
                     ->where('is_deleted', 1)
                     ->where('is_duplicate', 0)
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





    public function getAll(){
        
        return $this->document->select('created_at')
                              ->where('is_deleted', 0)
                              ->where('is_duplicate', 0)
                              ->get();

    }






}