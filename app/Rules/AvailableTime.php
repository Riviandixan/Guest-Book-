<?php

namespace App\Rules;

use Closure;
use App\Models\Time;
use Illuminate\Contracts\Validation\ValidationRule;

class AvailableTime implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $availableTimes = Time::where('start_time', '<=', $value)
            ->where('end_time', '>=', $value)
            ->get();

        if ($availableTimes->isEmpty()) {
            $fail("Waktu yang dimasukkan tidak tersedia.");
        }
    }
}
