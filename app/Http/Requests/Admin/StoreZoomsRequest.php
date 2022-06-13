<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoomsRequest extends FormRequest
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
            'zoom_email' => 'required',
            'zoom_number' => 'required',
            'password' => 'max:2147483647|required|numeric',
            'description' => 'required',
            'expire_at' => 'required',
        ];
    }
}
