<?php 

namespace App\Core\Subscribers;


use App\Core\BaseClasses\BaseSubscriber;



class FolderSubscriber extends BaseSubscriber{




    public function __construct(){
        parent::__construct();
    }



    public function subscribe($events){

        $events->listen('folder.store', 'App\Core\Subscribers\FolderSubscriber@onStore');
        $events->listen('folder.update', 'App\Core\Subscribers\FolderSubscriber@onUpdate');
        $events->listen('folder.destroy', 'App\Core\Subscribers\FolderSubscriber@onDestroy');

    }



    public function onStore(){
        
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:getAll');

        $this->session->flash('FOLDER_CREATE_SUCCESS', 'The Folder has been successfully created!');

    }



    public function onUpdate($folder){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:getAll');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:getBySlug:'. $folder->slug .'');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:findBySlug:'. $folder->slug .'');

        $this->session->flash('FOLDER_UPDATE_SUCCESS', 'The Folder has been successfully updated!');
        $this->session->flash('FOLDER_UPDATE_SUCCESS_SLUG', $folder->slug);

    }



    public function onDestroy($folder){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:getAll');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:getBySlug:'. $folder->slug .'');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:folders:findBySlug:'. $folder->slug .'');

        $this->session->flash('FOLDER_DELETE_SUCCESS', 'The Folder has been successfully deleted!');
        $this->session->flash('FOLDER_DELETE_SUCCESS_SLUG', $folder->slug);

    }



}