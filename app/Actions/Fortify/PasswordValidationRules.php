<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function passwordRules(): array
    {
        // jadi tim Front End harus membuat input name password_confirmation
        return ['required', 'min:6', 'max:20', 'string', new Password, 'confirmed'];
    }
}
