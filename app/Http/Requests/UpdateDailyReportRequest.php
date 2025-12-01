<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyReportRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを行う権限を持っているかどうかを確認
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * リクエストに適用される検証ルールを取得
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_date' => ['required', 'date'],
            'business_content' => ['required', 'string'],
            'work_plan' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
        ];
    }

    /**
     * バリデータエラーのカスタム属性を取得
     * 
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'report_date' => '日付',
            'business_content'=> '業務内容',
            'work?_plan' => '作業予定',
            'memo' => '備忘欄',
        ];
    }

    /**
     * バリデータエラーのカスタムメッセージの取得
     * 
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'report_date.required' => ':attributeは必須です。',
            'report_date.date' => ':attributeは有効な日付形式で入力して下さい。',
            'business_content.required' => ':attributeは必須です。',
        ];
    }
}
