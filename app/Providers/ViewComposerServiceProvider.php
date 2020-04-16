<?php

namespace App\Providers;


use View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider{

    
    public function boot(){

    	View::composer('layouts.admin-sidenav', 'App\Core\ViewComposers\UserMenuComposer');


        // MENU
        View::composer(['dashboard.user.create', 
                        'dashboard.user.edit'], 'App\Core\ViewComposers\MenuComposer');
        

        // SUBMENU
        View::composer(['dashboard.user.create', 
                        'dashboard.user.edit'], 'App\Core\ViewComposers\SubmenuComposer');
        

        // FOLDERS
        View::composer(['guest.document.create',], 'App\Core\ViewComposers\FolderComposer');

    }

    
    
    public function register(){
    
    }



}
