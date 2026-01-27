<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\UserListInputDto;
use App\Http\Requests\AppFormRequest;
use App\Infrastructure\Validation\Illuminate\Rules\EmailRules;
use App\Infrastructure\Validation\Illuminate\Rules\FirstAndLastNameRules;

class UserListRequest extends AppFormRequest
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
            'page' => ['required', 'integer', 'min:1', 'max:100'],
            'per_page' => ['required', 'integer', 'min:1', 'max:100'],
            'first_name' => FirstAndLastNameRules::rulesForOptionalFilter(),
            'last_name' => FirstAndLastNameRules::rulesForOptionalFilter(),
            'email' => EmailRules::rulesForOptionalFilter(),
        ];
    }

    public function toDto(): UserListInputDto
    {
        $params = $this->safe()->all();

        return new UserListInputDto(
            page: $params['page'],
            perPage: $params['per_page'],
            firstName: $params['first_name'] ?? null,
            lastName: $params['last_name'] ?? null,
            email: $params['email'] ?? null,
        );
    }
}
