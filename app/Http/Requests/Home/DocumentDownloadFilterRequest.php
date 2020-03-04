<?php

namespace App\Http\Requests\Home;

use Illuminate\Foundation\Http\FormRequest;

class DocumentDownloadFilterRequest extends FormRequest{




    public function authorize(){

        return true;
    }

   


    public function rules(){

        return [

            'q' => 'nullable|string|max:90',

        ];

    }




}
