<?php

namespace SebastiaanLuca\Validator\Validators;

use Illuminate\Foundation\Http\FormRequest;

abstract class Validator extends FormRequest
{
    /**
     * Automatically authorize any request by default.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    abstract public function rules();

    /**
     * Get validated user input.
     *
     * Check if input corresponds with fields under validation.
     *
     * @param bool $clean Remove the input fields whose values are empty.
     *
     * @return array
     */
    public function getValidInput($clean = false)
    {
        $input = array_dot($this->input());
        $rules = array_keys($this->rules());

        $keys = collect($rules)->map(function (string $rule) use ($input) {
            // Transform each rule to a regex string while supporting
            // the wildcard (*) character to include sequential arrays too.
            $regex = '/^' . str_replace(['.', '*'], ['\.', '(.*)'], $rule) . '$/';

            // Match the regex string against our input fields
            return preg_grep($regex, array_keys($input));
        });

        // Match the input attributes against the existing rules
        $input = array_only($input, $keys->flatten()->toArray());

        // Reverse dot flattening to match original input
        $expanded = [];

        foreach ($input as $key => $value) {
            array_set($expanded, $key, $value);
        }

        if ($clean) {
            $expanded = $this->sanitizeInput($expanded);
        }

        return $expanded;
    }

    /**
     * Get validated user input.
     *
     * Check if input corresponds with fields under validation.
     *
     * @param bool $clean Remove the input fields whose values are empty.
     *
     * @return array
     */
    public function valid($clean = false)
    {
        return $this->getValidInput($clean);
    }

    /**
     * Remove null and empty values from the input.
     *
     * @param array $input
     *
     * @return array
     */
    public function sanitizeInput(array $input)
    {
        return $input = collect($input)->reject(function ($value) {
            return is_null($value) || empty($value);
        })->toArray();
    }
}
