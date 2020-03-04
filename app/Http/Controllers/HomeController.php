<?php

namespace App\Http\Controllers;


use App\Core\Services\HomeService;
use App\Http\Requests\Home\DocumentDownloadFilterRequest;



class HomeController extends Controller{
    



	protected $home;




    public function __construct(HomeService $home){

        $this->home = $home;

    }





    public function index(DocumentDownloadFilterRequest $request){

    	return $this->home->view($request);

    }
    





}
