<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class DocumentDownload extends Model{


    use Sortable;

    protected $table = 'document_downloads';

    protected $dates = ['downloaded_at',];

    public $timestamps = false;



    protected $attributes = [

        'document_id' => '',
        'downloaded_at' => null,
        'ip_downloaded' => '',
        'machine_downloaded' => '',

    ];



    /** RELATIONSHIPS **/
    public function document() {
    	return $this->belongsTo('App\Models\Document','document_id','document_id');
   	}
    



}
