<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderCreateFormRequest extends FormRequest{



    public function authorize(){
        return true;
    }

   

    public function rules(){

        return [
            	
            'folder_code' => 'required|string|max:90',
            'description' => 'nullable|string|max:255',

        ];

    }


}
