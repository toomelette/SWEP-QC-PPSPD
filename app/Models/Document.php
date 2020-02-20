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
        'file_name' => '',
        'folder_name' => '',
        'file_location' => '',
        'file_size' => '',
        'is_deleted' => 0,
        'created_at' => null,
        'updated_at' => null,
        'ip_created' => '',
        'ip_updated' => '',
        'user_created' => '',
        'user_updated' => '',

    ];




    
}
