<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください',
            'password.confirmed' => 'パスワードと一致しません',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // 既存のエラーメッセージ配列を取得
        $messages = $validator->errors()->getMessages();

        // password のメッセージがあるなら調べる
        if (isset($messages['password'])) {
            $isNotConfirm = false;
            $confirmMsg = [];
            foreach ($messages['password'] as $i => $msg) {
                // メッセージの文言を見て「確認と一致しない」系のメッセージなら移動する
                // 日本語・英語どちらにも対応するためにいくつかキーワードで判定
                if (
                    str_contains($msg, '一致') ||
                    str_contains($msg, 'does not match') ||
                    str_contains($msg, 'confirmation') ||
                    str_contains($msg, '一致しません')
                ) {
                    $confirmMsg = $msg;
                    // password 側の該当メッセージを削除（unset）
                    unset($messages['password'][$i]);
                } else {
                    $isNotConfirm = true;
                }
            }

            if (!$isNotConfirm) {
                // confirmメッセージ以外にエラーメッセージが無い場合は、
                // 画面に不一致メッセージを表示する。
                // password_confirmation に追加
                $messages['password_confirmation'][] = $msg;
            }

            // password の配列が空になったらキー自体を消す
            if (empty($messages['password'])) {
                unset($messages['password']);
            }
        }

        // 加工済みメッセージで ValidationException を投げる（通常の挙動に合わせる）
        throw ValidationException::withMessages($messages);
    }
}
