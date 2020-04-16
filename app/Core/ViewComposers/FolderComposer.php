<?php

namespace App\Core\ViewComposers;


use View;
use App\Core\Interfaces\FolderInterface;


class FolderComposer{
   


	protected $folder_repo;



	public function __construct(FolderInterface $folder_repo){
		$this->folder_repo = $folder_repo;
	}



    public function compose($view){

        $folders = $this->folder_repo->getAll();
    	$view->with('global_folders_all', $folders);

    }



}