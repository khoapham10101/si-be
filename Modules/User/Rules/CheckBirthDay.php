<?php

namespace Modules\User\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckBirthDay implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $today = Carbon::now();
        $birthday = Carbon::createFromFormat('Y-m-d', $value);
        $age = $today->diffInYears($birthday);

        $nextBirthday = $birthday->copy()->addYears(12);

        return $age > 12 || ($age == 12 && $nextBirthday->lte($today->subDay()));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The birthday must be at least 12 years before the current date.';
    }
}
