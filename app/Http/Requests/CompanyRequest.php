<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $uniqueEmailRule = "unique:companies,email";
        if($this->method() == "PUT"){
            $uniqueEmailRule = "unique:companies,email," . $this->route('company')->id; 
        }
        return [
            'name' => 'string|max:190|required',
            'email' => 'required|email|' . $uniqueEmailRule,
            'logo' => 'nullable|image',
            'website' => 'nullable|active_url'
        ];
    }
}
