<?php

namespace App\Http\Requests\Admin\User;

use App\Application\Dto\InputDto;
use App\Application\User\Dtos\Inputs\LoginUserInputDto;
use App\Http\Requests\AppFormRequest;
use Illuminate\Auth\Events\Login;

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
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
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
