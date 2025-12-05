<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use illuminate\Validation\Rule;
use illuminate\validation\Rules;
use App\Models\User;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; //権限チェックはルーティング側
    }

    public function rules(): array
    {
        // ルートパラメータから更新対象のユーザーIDを取得
        $userId = $this->route('user')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($userId), // 自分自身のメールアドレスは重複とみなさない
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // 空の場合は変更なし
            'role' => ['required', 'string', 'in:admin,employee'],
            'group_ids' => ['nullable', 'array'],
            'group_ids.*' => ['exists:groups,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '氏名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'role' => '権限',
            'group_ids' => '所属部署',
        ];
    }
}
