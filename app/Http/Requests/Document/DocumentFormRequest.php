<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentFormRequest extends FormRequest{

    

    public function authorize(){

        return true;
    
    }

    
    public function rules(){

        return [
            
            'doc_file' => 'nullable',
            'folder' => 'nullable|string|max:90',
            
        ];
    
    }



}
