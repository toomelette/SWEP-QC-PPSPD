<?php

namespace App\Core\Repositories;
 
use App\Core\BaseClasses\BaseRepository;
use App\Core\Interfaces\FolderInterface;

use App\Models\Folder;


class FolderRepository extends BaseRepository implements FolderInterface {
	


    protected $folder;



	public function __construct(Folder $folder){

        $this->folder = $folder;
        parent::__construct();

    }



    public function fetch($request){

        $key = str_slug($request->fullUrl(), '_');
        $entries = isset($request->e) ? $request->e : 20;

        $folders = $this->cache->remember('folders:fetch:' . $key, 240, function() use ($request, $entries){

            $folder = $this->folder->newQuery();
            
            if(isset($request->q)){
                $this->search($folder, $request->q);
            }

            return $this->populate($folder, $entries);

        });

        return $folders;

    }



    public function store($request){

        $folder = new Folder;
        $folder->slug = $this->str->random(16);
        $folder->folder_code = $request->folder_code;
        $folder->description = $request->description;
        $folder->created_at = $this->carbon->now();
        $folder->updated_at = $this->carbon->now();
        $folder->ip_created = request()->ip();
        $folder->ip_updated = request()->ip();
        $folder->save();
        
        return $folder;

    }



    public function update($request, $slug){

        $folder = $this->findBySlug($slug);
        $folder->folder_code = $request->e_folder_code;
        $folder->description = $request->e_description;
        $folder->updated_at = $this->carbon->now();
        $folder->ip_updated = request()->ip();
        $folder->save();
        
        return $folder;

    }



    public function destroy($slug){

        $folder = $this->findBySlug($slug);
        $folder->delete();

        return $folder;

    }



    public function findBySlug($slug){

        $folder = $this->cache->remember('folders:findBySlug:' . $slug, 240, function() use ($slug){
            return $this->folder->where('slug', $slug)->first();
        }); 
        
        if(empty($folder)){
            abort(404);
        }

        return $folder;

    }




    public function search($model, $key){

        return $model->where(function ($model) use ($key) {
                $model->where('folder_code', 'LIKE', '%'. $key .'%')
                      ->orWhere('description', 'LIKE', '%'. $key .'%');
        });

    }



    public function populate($model, $entries){

        return $model->select('folder_code', 'description', 'slug')
                     ->sortable()
                     ->orderBy('updated_at', 'desc')
                     ->paginate($entries);

    }




    public function getAll(){

        $folders = $this->cache->remember('folders:getAll', 240, function(){
            return $this->folder->select('folder_code')->get();
        });
        
        return $folders;

    }




    public function getBySlug($slug){

        $folder = $this->cache->remember('folders:getBySlug:' . $slug, 240, function() use ($slug){
            return $this->folder->where('slug', $slug)->get();
        }); 
        
        if(empty($folder)){
            $folder = [];
        }

        return $folder;

    }




}