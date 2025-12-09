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
            // Web会議用のバリデーション
            'is_web_meeting' => ['sometimes', 'boolean'],
            'meeting_type' => ['nullable', 'required_if:is_web_meeting,true', 'string'], 
            'meeting_url' => ['nullable', 'url', 'max:255'],
            'participants_memo' => ['nullable', 'string', 'max:1000'],
            // 社用車予約用のバリデーション
            'is_vehicle_reservation' => ['sometimes', 'boolean'],
            'vehicle_id' => ['nullable', 'required_if:is_vehicle_reservation,true', 'exists:vehicles,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'schedule_date' => '日付',
            'start_time' => '開始時間',
            'end_time' => '終了時間',
            'destination' => '行先・目的',
            'meeting_type' => '会議ツール',
            'meeting_url' => '会議URL',
            'participants_memo' => '参加者メモ',
            'vehicle_id' => '社用車',
        ];
    }
}
