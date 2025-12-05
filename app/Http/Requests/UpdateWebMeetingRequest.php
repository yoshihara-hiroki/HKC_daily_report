<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWebMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meeting_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'meeting_type' => ['required', 'in:zoom,google_meet'],
            'title' => ['required', 'string', 'max:255'],
            'meeting_url' => ['nullable', 'url', 'max:2048'],
            'participants_memo' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'meeting_date' => '会議日',
            'start_time' => '開始時間',
            'end_time' => '終了時間',
            'meeting_type' => 'ツール',
            'title' => '会議名',
            'meeting_url' => '会議URL',
            'participants_memo' => '参加者・メモ',
        ];
    }
}
