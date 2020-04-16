<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Folder extends Model{



    use Sortable;

    protected $table = 'folders';

    protected $dates = ['created_at', 'updated_at'];
    
	public $timestamps = false;



    protected $attributes = [

        'slug' => '',
        'folder_code' => '',
        'description' => '',
        'created_at' => null,
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];



    /** RELATIONSHIPS **/
    public function documents() {
    	return $this->hasMany('App\Models\Documents','folder_code','folder_code');
   	}



}
