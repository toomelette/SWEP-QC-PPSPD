<?php
 
namespace App\Core\Services;


use App\Core\Interfaces\DocumentDownloadInterface;
use App\Core\BaseClasses\BaseService;


class HomeService extends BaseService{

    protected $document_download_repo;



    public function __construct(DocumentDownloadInterface $document_download_repo){

        $this->document_download_repo = $document_download_repo;
        parent::__construct();

    }



    public function view($request){

        $document_downloads = $this->document_download_repo->fetch($request);
        $request->flash();
        return view('dashboard.home.index')->with('document_downloads', $document_downloads);

    }




}