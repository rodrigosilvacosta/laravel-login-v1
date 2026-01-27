<?php

namespace App\Infrastructure\Validation\Illuminate\Rules;

class EmailRules
{
    public static function rulesForRequired(): array
    {
        return [
            'required',
            'min:3',
            'max:255',
            'email',
        ];
    }

    public static function rulesForOptionalFilter(): array
    {
        return [
            'nullable',
            'min:1',
            'max:255',
        ];
    }
}
