<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Decimal implements Rule
{
    protected array $digits = [];

    /**
     * Create a new rule instance.
     */
    public function __construct(array $digits = [])
    {
        $this->digits = $digits;
    }

    /**
     * Generate an example value that satisifies the validation rule.
     *
     **/
    public function example()
    {
        return mt_rand(1, (int) str_repeat('9', $this->digits[0])) . '.' .
            mt_rand(1, (int) str_repeat('9', $this->digits[1]));
    }

    /**
     * Determine if the validation rule passes.
     *
     * The rule has two parameters:
     * 1. The maximum number of digits before the decimal point.
     * 2. The maximum number of digits after the decimal point.
     *
     * @return bool.
     *
     **/
    public function passes($attribute, $value)
    {
        return preg_match(
            "/^[0-9]{1,{$this->digits[0]}}(\.[0-9]{1,{$this->digits[1]}})$/",
            $value
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string.
     *
     **/
    public function message()
    {
        return 'The :attribute must be an appropriately formatted decimal e.g. ' . $this->example();
    }
}
