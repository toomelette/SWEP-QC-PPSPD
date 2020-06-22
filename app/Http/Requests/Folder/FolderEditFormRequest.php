<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderEditFormRequest extends FormRequest{



    public function authorize(){
        return true;
    }

   

    public function rules(){

        return [
            	
            'e_folder_code' => 'required|string|max:90|unique:folders,folder_code,'.$this->route('folder').',slug',
            'e_description' => 'nullable|string|max:255',

        ];

    }


}
