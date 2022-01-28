<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRules implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the Length Validation Rule passes.
     *
     * @var boolean
     */
    public $lengthPasses = true;

    /**
     * Determine if the Uppercase Validation Rule passes.
     *
     * @var boolean
     */
    public $uppercasePasses = true;

    public $condition = true;

    /**
     * Determine if the Numeric Validation Rule passes.
     *
     * @var boolean
     */
    public $numericPasses = true;

    /**
     * Determine if the Special Character Validation Rule passes.
     *
     * @var boolean
     */
    public $specialCharacterPasses = true;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        // $this->lengthPasses = (Str::length($value) >= 8);
        $this->condition = ((bool) preg_match('/^(?=.*?[A-Z])(?=.*?[a-z]).{8,}$/', $value)); //(Str::lower($value) !== $value);
        // $this->lowerPasses = ((bool) preg_match('/[^a-z]/', $value));
        // $this->numericPasses = ((bool) preg_match('/[0-9]/', $value));
        // $this->specialCharacterPasses = ((bool) preg_match('/[^A-Za-z0-9]/', $value));

        return ($this->condition);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        // return 'The validation error message.';

        switch (true) {
            case ! $this->condition:
                return 'The password must be at least 8 characters and at least one uppercase and one lowercase character.';

            // case ! $this->lowerPasses && $this->uppercasePasses:
            //     return 'The :attribute must be at least one lowercase character.';

            // case ! $this->specialCharacterPasses
            //     && $this->uppercasePasses
            //     && $this->numericPasses:
            //     return 'The :attribute must be at least 10 characters and contain at least one special character.';

            // case ! $this->uppercasePasses
            //     && ! $this->numericPasses
            //     && $this->specialCharacterPasses:
            //     return 'The :attribute must be at least 10 characters and contain at least one uppercase character and one number.';

            // case ! $this->uppercasePasses
            //     && ! $this->specialCharacterPasses
            //     && $this->numericPasses:
            //     return 'The :attribute must be at least 10 characters and contain at least one uppercase character and one special character.';

            // case ! $this->uppercasePasses
            //     && ! $this->numericPasses
            //     && ! $this->specialCharacterPasses:
            //     return 'The :attribute must be at least 10 characters and contain at least one uppercase character, one number, and one special character.';

            default:
                return 'The :attribute must be at least 8 characters.';
        }
    }
}
