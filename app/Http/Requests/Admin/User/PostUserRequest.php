<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\RegisterUserInputDto;
use App\Http\Requests\AppFormRequest;

class PostUserRequest extends AppFormRequest
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
            'name' => 'required|string|min:2|max:45',
            'last_name' => 'required|string|min:2|max:45',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:64|confirmed',
        ];
    }

    public function toDto(): RegisterUserInputDto
    {
        $params = $this->safe()->all();

        return new RegisterUserInputDto(
            name: $params['name'],
            lastName: $params['last_name'],
            email: $params['email'],
            password: $params['password'],
        );
    }
}
