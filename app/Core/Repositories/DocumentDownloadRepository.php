<?php

namespace App\Core\Repositories;
 
use App\Core\BaseClasses\BaseRepository;
use App\Core\Interfaces\DocumentDownloadInterface;


use App\Models\DocumentDownload;


class DocumentDownloadRepository extends BaseRepository implements DocumentDownloadInterface {
	



    protected $document_download;




	public function __construct(DocumentDownload $document_download){

        $this->document_download = $document_download;
        parent::__construct();

    }





    public function fetch($request){

        $key = str_slug($request->fullUrl(), '_');
        $entries = isset($request->e) ? $request->e : 20;

        $document_downloads = $this->cache->remember('document_downloads:fetch:' . $key, 240, function() use ($request, $entries){

            $document_download = $this->document_download->newQuery();
            
            if(isset($request->q)){
                $this->search($document_download, $request->q);
            }

            return $this->populate($document_download, $entries);

        });

        return $document_downloads;

    }






    public function store($document_id){

        $document_download = new DocumentDownload;
        $document_download->document_id = $document_id;
        $document_download->downloaded_at = $this->carbon->now();
        $document_download->ip_downloaded = request()->ip();
        $document_download->machine_downloaded = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $document_download->save();
        
        return $document_download;

    }






    public function search($model, $key){

        return $model->where(function ($model) use ($key) {
                $model->where('machine_downloaded', 'LIKE', '%'. $key .'%')
                      ->orWhere('ip_downloaded', 'LIKE', '%'. $key .'%')
                      ->orwhereHas('document', function ($model) use ($key) {
                        $model->where('file_name', 'LIKE', '%'. $key .'%');
                       });
        });

    }





    public function populate($model, $entries){

        return $model->select('document_id', 'downloaded_at', 'ip_downloaded', 'machine_downloaded')
                     ->sortable()
                     ->orderBy('downloaded_at', 'desc')
                     ->paginate($entries);

    }







}