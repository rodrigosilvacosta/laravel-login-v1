<?php

namespace App\Http\Requests\Admin\User;

use App\Application\User\Dtos\Inputs\UserFindByUuidInputDto;
use App\Http\Requests\AppFormRequest;

class UserGetByUuidRequest extends AppFormRequest
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
            'uuid' => ['required', 'uuid'],
        ];
    }

    public function toDto(): UserFindByUuidInputDto
    {
        return new UserFindByUuidInputDto(
            uuid: $this->validated('uuid'),
        );
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'uuid' => $this->route('uuid'),
        ]);
    }
}
