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
        // Make sure any multi-dimensional input array matches our dotted rule keys
        $input = array_dot($this->input());
        
        // Match the input attributes against the existing rules
        $input = array_only($input, array_keys($this->rules()));
        
        // Reverse dot flattening to match original input
        $input = array_expand($input);
        
        // Clean empty values
        if ($clean) {
            $input = $this->sanitizeInput($input);
        }
        
        return $input;
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
        return $input = collect($input)->reject(function($value) {
            return is_null($value) || empty($value);
        })->toArray();
    }
}
