<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\LoginUserInputDto;
use App\Http\Requests\AppFormRequest;

class UserLoginRequest extends AppFormRequest
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
            'email' => 'required|email:filter',
            'password' => 'required|min:8|max:64',
            'device_name' => 'required|string|min:2|max:45',
        ];
    }

    public function toDto(): LoginUserInputDto
    {
        return new LoginUserInputDto(
            email: $this->input('email'),
            password: $this->input('password'),
            deviceName: $this->input('device_name'),
        );
    }
}
