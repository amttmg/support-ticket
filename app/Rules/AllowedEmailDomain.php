<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        // Check if email ends with @rbb.com.np (case insensitive)
        if (!preg_match('/@rbb\.com\.np$/i', $value)) {
            $fail('The :attribute must be a valid RBB email address ending with @rbb.com.np.');
        }
    }
}
