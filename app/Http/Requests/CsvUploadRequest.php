<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'csv' => [
                'required',
                'file',
                'mimetypes:text/csv,text/plain,application/csv',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'csv.required' => 'Please select a CSV file to upload.',
            'csv.file' => 'The uploaded file must be a valid file.',
            'csv.mimetypes' => 'The file must be a CSV file.',
            'csv.max' => 'The file size must not exceed 2MB.',
        ];
    }
}
