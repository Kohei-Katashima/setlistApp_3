<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSong extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'band_name' => 'required|max:100',
            'title' => 'required|max:100',
            'time' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'band_name' => 'アーティスト名',
            'title' => '曲名',
            'time' => '時間',
        ];
    }
}
