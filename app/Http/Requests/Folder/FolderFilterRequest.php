<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FolderFilterRequest extends FormRequest{



    public function authorize(){
        return true;
    }

   

    public function rules(){

        return [
            'q' => 'nullable|string|max:90',
        ];

    }


}
