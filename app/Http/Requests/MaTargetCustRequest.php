<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaTargetCustRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() : array
    {
        return [
            //
        ];
    }

    public function message() : array
    {
        return [
            //
        ];
    }
}
