<?php

namespace PortedCheese\SeoIntegration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetaUpdateRequest extends FormRequest
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
            'page' => 'required_if:model,off|min:2|max:250',
            'name' => 'required|max:250',
            'content' => 'required',
        ];
    }
}
