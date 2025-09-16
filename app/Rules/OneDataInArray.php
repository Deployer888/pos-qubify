<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * OneDataInArray
 */
class OneDataInArray implements Rule
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
        if (isset($value[0])) {
            if ($value[0]['item_name'] == null && $value[0]['item_qty'] == null && $value[0]['amount'] == 0) return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please add at least one item.';
    }
}
