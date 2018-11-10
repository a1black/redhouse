<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Classes;

use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;

class ValidatorExtensions
{
    /**
     * Add to a validator object all defined rules.
     */
    public static function apply(Validator $validator): Validator
    {
        $reflection = new \ReflectionClass(self::class);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            if (strpos($method->name, 'addRule') === 0) {
                $method->invoke(new self, $validator);
            }
        }

        return $validator;
    }

    /**
     * Returns validator after adding rules for checkin 3 part name.
     * Valid names: Last-name[ F.[ Middle]]
     */
    public function addRuleName(Validator $validator): Validator
    {
        $validator->addExtension(
            'alpha_name',
            function ($attribute, $value, $parameters) {
                return preg_match('/^(\pL[\pL-]*\.?\s?){1,3}$/u', $value) > 0
                    && preg_match('/(-{2,}|-\s)/', $value) === 0;
            }
        );

        $validator->setCustomMessages(['alpha_name' => 'The :attribute field has invalid format!']);

        return $validator;
    }

    /**
     * Returns validator after adding rule for checking unicode text.
     * Usage: alpha_text[:(0|1)]
     * Parameter 1 is for multi-line text.
     */
    public function addRuleAlphaText(Validator $validator): Validator
    {
        $validator->addExtension(
            'alpha_text',
            function ($attribute, $value, $params) {
                $multi = array_key_exists(0, $params) && $params[0] == 1 ? 'm' : '';
                return preg_match('/^[\pL\pM\pN\pP\pZ]+$/u'.$multi, $value) > 0;
            }
        );

        $validator->setCustomMessages(['alpha_text' => 'The :attribute field has invalid format!']);

        return $validator;
    }

    /**
     * Returns validator after adding rule for checking list of contuct numbers.
     */
    public function addRuleContactNumbers(Validator $validator): Validator
    {
        $rule = function ($attribute, $value, $params) {
            $this->requireParameterCount(1, $params, 'contact_number');

            $other = \Illuminate\Support\Arr::get($this->data, $params[0]);

            if ($other === 'skype') {
                return $this->validateRegex(
                    $attribute,
                    $value,
                    ['/^[a-z][a-z0-9_\-\.]{5,31}$/i']
                );
            } elseif ($other) {
                return $this->validateDigits($attribute, $value, [10]);
            }

            return true;
        };

        $validator->addDependentExtension(
            'contact_number',
            \Closure::bind($rule, $validator, 'Illuminate\Validation\Validator')
        );

        $validator->setCustomMessages(['contact_number' => 'The :attribute field has invalid format!']);

        return $validator;
    }
}
