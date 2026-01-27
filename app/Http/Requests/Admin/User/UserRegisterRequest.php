<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\UserRegisterInputDto;
use App\Http\Requests\AppFormRequest;
use App\Infrastructure\Validation\Illuminate\Rules\EmailRules;
use App\Infrastructure\Validation\Illuminate\Rules\FirstAndLastNameRules;

class UserRegisterRequest extends AppFormRequest
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
        /** @todo mover validação de e-mail unico para o use case */
        return [
            'first_name' => FirstAndLastNameRules::rulesForRequired(),
            'last_name' => FirstAndLastNameRules::rulesForRequired(),
            'email' => EmailRules::rulesForRequired(),
            'password' => ['required', 'string', 'min:8', 'max:64', 'confirmed'],
        ];
    }

    public function toDto(): UserRegisterInputDto
    {
        $params = $this->safe()->all();

        return new UserRegisterInputDto(
            firstName: $params['first_name'],
            lastName: $params['last_name'],
            email: $params['email'],
            password: $params['password'],
        );
    }
}
