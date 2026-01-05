<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\RegisterUserInputDto;
use App\Http\Requests\AppFormRequest;

class PostUserRequest extends AppFormRequest
{
    /**
     * ^  inicio da validação
     * [\p{L}] só letras permitidas
     * {2,45} entre 2 e 45 caracteres
     * $ fim da validação
     * u suporte a Unicode (acentos funcionando)
     */
    private const VALIDATE_REGEX_FIRST_AND_LAST_NAME = '/^[\p{L}]{2,45}$/u';

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
        /** @todo mover validação de e-mail unico para o use case */
        return [
            'first_name' => 'required|string|min:2|max:45|regex:' . self::VALIDATE_REGEX_FIRST_AND_LAST_NAME,
            'last_name' => 'required|string|min:2|max:45|regex:' . self::VALIDATE_REGEX_FIRST_AND_LAST_NAME,
            'email' => 'required|min:3|max:255|email|unique:users,email',
            'password' => 'required|string|min:8|max:64|confirmed',
        ];
    }

    public function toDto(): RegisterUserInputDto
    {
        $params = $this->safe()->all();

        return new RegisterUserInputDto(
            firstName: $params['first_name'],
            lastName: $params['last_name'],
            email: $params['email'],
            password: $params['password'],
        );
    }
}
