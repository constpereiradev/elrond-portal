<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' =>  'required|string',
            "email" =>  'required|email',
            "password" =>  'required|string',
            'role_id' => 'integer|exists:roles,id,status,a',
            'kingdom_id' => 'integer|exists:kingdoms,id,status,a',
            'council_id' => 'integer|exists:councils,id,status,a',
        ];
    }
}
