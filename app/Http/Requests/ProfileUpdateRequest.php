<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('tbl_users')->ignore($this->user()->user_id, 'user_id'),
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:5120', // 5MB max size
            ],
        ];

        // Add password validation if email is being changed and the confirm_email_change flag is set
        if ($this->has('confirm_email_change') && $this->user()->email !== $this->input('email')) {
            $rules['password'] = ['required', 'current_password'];
        }

        return $rules;
    }
}
