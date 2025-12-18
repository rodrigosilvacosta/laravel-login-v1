<?php

namespace App\Http\Requests;

use App\Application\Dto\InputDto;
use Illuminate\Foundation\Http\FormRequest;

abstract class AppFormRequest extends FormRequest
{
    abstract public function toDto(): InputDto;
}
