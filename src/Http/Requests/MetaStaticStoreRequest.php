<?php

namespace PortedCheese\SeoIntegration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetaStaticStoreRequest extends FormRequest
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
            'page' => 'required|min:2',
            'name' => 'required',
            'content' => 'required',
        ];
    }
}
