<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Helpers\Spam\Spam as SpamDetector;

class Spam implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $spamDetector;
    protected $message;
    protected $attribute;

    public function __construct()
    {
        $this->spamDetector = resolve(SpamDetector::class);
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
        try {
            $this->spamDetector->detect($value);

            return true;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->attribute = $attribute;

            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The '.$this->attribute.' is a spam';
    }
}
