<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class GmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // jika user tidak memasukkan .gmail.com di input gmail maka
        if (!preg_match('/@gmail\.com$/', $value)) {
            // pesan validasi nya adalah "Masukkan gmail anda."
            // $gagal(')
            $fail('Masukkan gmail anda.');
        };
    }
}
