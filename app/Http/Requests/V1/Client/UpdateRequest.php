<?php

namespace App\Http\Requests\V1\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return [
            'name' => 'required',
            'email' => 'required|email|unique:clients',
            'phone_number' => 'required|min:10|max:11',
            'birthday' => 'required|date_format:Y-m-d',
            'address' => 'required',
            'complement' => 'nullable',
            'neighborhood' => 'required',
            'postal_code' => 'required|min:8|max:8',
        ];
    }
}
