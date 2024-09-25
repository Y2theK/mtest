<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        $uniqueEmailRule = "unique:employees,email";

        if($this->method() == "PUT"){
            $uniqueEmailRule = "unique:employees,email," . $this->route('employee')->id; 
        }
        return [
            'name' => 'string|max:190|required',
            'email' => 'required|email|' . $uniqueEmailRule,
            'phone' => 'required|string',
            'profile' => 'nullable|image',
            'company_id' => 'required|exists:companies,id'
        ];
    }
}
