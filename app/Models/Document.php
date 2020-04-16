<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Document extends Model{



    use Sortable;

    protected $table = 'documents';

    protected $dates = ['created_at', 'updated_at'];
    
	public $timestamps = false;



    protected $attributes = [

        'slug' => '',
        'document_id' => '',
        'folder_code' => '',
        'file_name' => '',
        'file_location' => '',
        'file_size' => '',
        'file_date' => '',
        'is_deleted' => 0,
        'is_duplicate' => 0,
        'created_at' => null,
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];



    // RELATIONSHIPS
    public function documentDownload() {
        return $this->hasMany('App\Models\DocumentDownload','document_id','document_id');
    }

    public function folder() {
        return $this->belongsTo('App\Models\Folder','folder_code','folder_code');
    }


    
}
