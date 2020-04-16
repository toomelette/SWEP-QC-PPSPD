<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class DocumentFormRequest extends FormRequest{

    

    public function authorize(){

        return true;
    
    }

    
    public function rules(){

        return [
            
            'folder_code' => 'required|string|max:90',
            'doc_file' => 'nullable',
            
        ];
    
    }



}
