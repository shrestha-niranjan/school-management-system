<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarkEntryRequest extends FormRequest
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
            'student_id' => 'required',
            'marks.*.course_id' => 'required',
            'marks.*.external' => 'required',
            'marks.*.internal' => 'required',
        ];
    }

    public function messages() : array
    {
        return [
            'student_id.required' => 'The student is required.',
            'marks.*.course_id.required' => 'The course is required.',
            'marks.*.external.required' => 'The external marks is required.',
            'marks.*.internal.required' => 'The internal marks is required.'
        ];
    }
}
