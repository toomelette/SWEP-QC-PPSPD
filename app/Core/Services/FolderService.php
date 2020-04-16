<?php
 
namespace App\Core\Services;


use App\Core\Interfaces\FolderInterface;
use App\Core\BaseClasses\BaseService;


class FolderService extends BaseService{



    protected $folder_repo;



    public function __construct(FolderInterface $folder_repo){

        $this->folder_repo = $folder_repo;
        parent::__construct();

    }



    public function fetch($request){

        $folders = $this->folder_repo->fetch($request);
        $request->flash();
        return view('guest.folder.index')->with('folders', $folders);

    }



    public function store($request){

        $folder = $this->folder_repo->store($request);
        $this->event->fire('folder.store');
        return redirect()->back();

    }



    public function update($request, $slug){

        $folder = $this->folder_repo->update($request, $slug);
        $this->event->fire('folder.update', $folder);
        return redirect()->route('guest.folder.index');

    }



    public function destroy($slug){

        $folder = $this->folder_repo->destroy($slug);
        $this->event->fire('folder.destroy', $folder);
        return redirect()->back();

    }



}