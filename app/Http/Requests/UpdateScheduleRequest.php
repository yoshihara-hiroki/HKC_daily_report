<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Policyで制御するのでここはTrue
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedule_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'destination' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'schedule_date' => '日付',
            'start_time' => '開始時刻',
            'end_time' => '終了時刻',
            'destination' => '行先・目的',
        ];
    }
}
