<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingUpdateRequest extends FormRequest
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
            'address' => 'required',
            'established_at' => 'required',
            'academic_year' => 'required',
            'grade_id' => 'required',
            'courses.*.name' => 'required',
            'courses.*.external_mark' => 'required',
            'courses.*.internal_mark' => 'required'
        ];
    }
}
