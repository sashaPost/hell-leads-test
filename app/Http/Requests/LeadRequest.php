<?php

namespace App\Http\Requests;

use App\Rules\PhoneValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'gi' => 'required|integer',
            // 'email' => 'required|email|unique:users,email|max:255',
            'email' => 'required|email|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'country' => [
                'required',
                'regex:/^[A-Z]{2,3}$/',
            ],
            'phone' => [
                'required',
                'string',
                new PhoneValidationRule,
                // 'unique:users,phone',
                'max:20',
            ],
            'ip' => 'required|ip',
            'sub_id1' => 'nullable|string|max:255',
            'sub_id2' => 'nullable|string|max:255',
            'sub_id3' => 'nullable|string|max:255',
            'sub_id4' => 'nullable|string|max:255',
            'sub_id5' => 'nullable|string|max:255',
            'aff_param1' => 'nullable|string|max:255',
            'aff_param2' => 'nullable|string|max:255',
            'aff_param3' => 'nullable|string|max:255',
            'aff_param4' => 'nullable|string|max:255',
            'aff_param5' => 'nullable|string|max:255',
        ];
    }
}
