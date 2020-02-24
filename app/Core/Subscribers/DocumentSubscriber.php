<?php 

namespace App\Core\Subscribers;


use App\Core\BaseClasses\BaseSubscriber;



class DocumentSubscriber extends BaseSubscriber{




    public function __construct(){

        parent::__construct();

    }





    public function subscribe($events){

        $events->listen('document.store', 'App\Core\Subscribers\DocumentSubscriber@onStore');
        $events->listen('document.store_has_duplicate', 'App\Core\Subscribers\DocumentSubscriber@onStoreHasDuplicate');
        $events->listen('document.update', 'App\Core\Subscribers\DocumentSubscriber@onUpdate');
        $events->listen('document.destroy', 'App\Core\Subscribers\DocumentSubscriber@onDestroy');
        $events->listen('document.restore', 'App\Core\Subscribers\DocumentSubscriber@onRestore');

    }





    public function onStore(){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetch:*');

        $this->session->flash('DOCUMENT_CREATE_SUCCESS', 'The Document has been successfully created!');

    }





    public function onStoreHasDuplicate($duplicate_document, $duplicates){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:getByIsDuplicate:1');

        $this->session->flash('DOCUMENT_CREATE_HAS_DUPLICATE', 'The System detected duplicate files!');
        $this->session->flash('DOCUMENT_CREATE_HAS_DUPLICATE_SLUG', $duplicate_document->slug);
        $this->session->flash('DOCUMENT_CREATE_HAS_DUPLICATE_LIST', $duplicates);

    }





    public function onUpdate($document){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetchDeleted:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:findBySlug:'. $document->slug .'');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:findByFileName:'. $document->file_name .'');

        $this->session->flash('DOCUMENT_UPDATE_SUCCESS', 'The Document has been successfully updated!');

        $this->session->flash('DOCUMENT_UPDATE_SUCCESS_SLUG', $document->slug);
        
    }





    public function onDestroy($document){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetchDeleted:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:findBySlug:'. $document->slug .'');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:findByFileName:'. $document->file_name .'');

        $this->session->flash('DOCUMENT_DELETE_SUCCESS', 'The Document has been successfully deleted!');
        
    }





    public function onRestore($document){

        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetch:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:fetchDeleted:*');
        $this->__cache->deletePattern(''. config('app.name') .'_cache:documents:findBySlug:'. $document->slug .'');

        $this->session->flash('DOCUMENT_RESTORE_SUCCESS', 'The Document has been successfully restored!');
        
    }





}