<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;

/**
 * Phone number for specific contact.
 */
class ContactNumber extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    const CN_TYPE_MOBILE = 'mobil';
    const CN_TYPE_SKYPE = 'skype';
    const CN_TYPE_VIBER = 'viber';

    public static $contactNumberTypes = [
        self::CN_TYPE_MOBILE,
        self::CN_TYPE_SKYPE,
        self::CN_TYPE_VIBER,
    ];

    /**
     * @inheritdoc
     */
    public $table = 'redhouse_shelter_contact_numbers';

    /** @var array */
    public $belongsTo = [
        'contact' => ['Redhouse\Shelter\Models\Contact'],
    ];

    /**
     * Validation rules.
     *
     * @return array
     */
    public $rules = [
        'type' => 'required',
        'number' => 'required',
    ];

    /**
     * Custom validation error messages.
     *
     * @var array
     */
    public $customMessages = [
        'number.digits' => 'redhouse.shelter::lang.contact_number.error.number_digits',
        'number.regex' => 'redhouse.shelter::lang.contact_number.error.number_regex',
    ];

    /**
     * Returns pretty phone number.
     */
    public static function makePhoneNumber(string $number): string
    {
        $phone = preg_replace_callback(
            '/^(\d{3})(\d{3})(\d{2})(\d{2})$/',
            function ($match) {
                if (count($match) == 5) {
                    return sprintf('+7(%d) %d-%d-%d', ...array_slice($match, 1));
                }
            },
            $number
        );

        return $phone ?: $number;
    }

    /**
     * Returns data validator.
     */
    public function makeValidator(
        array $data,
        array $rules,
        array $customMessages = [],
        array $attributeNames = []
    ): Validator {
        $validator = self::traitMakeValidator($data, $rules, $customMessages, $attributeNames);

        // Conditional rules
        $validator->sometimes(
            'number',
            'regex:/^[a-z][a-z0-9_\-\.]{5,31}$/i',
            function (Fluent $request) {
                return $request->get('type') === self::CN_TYPE_SKYPE;
            }
        );
        $validator->sometimes(
            'number',
            'digits:10',
            function (Fluent $request) {
                return $request->get('type') !== self::CN_TYPE_SKYPE;
            }
        );

        // Add extra rules
        $extraRules = [
            'type' => sprintf('in:%s', implode(',', self::$contactNumberTypes)),
            'number' => sprintf('unique:%s,number,%s,id,type,%s', $this->table, $this->id ?: 'NULL', $this->type),
        ];
        $validator->addRules($extraRules);

        return $validator;
    }

    /**
     * Modify model data and validation rules before validation.
     */
    public function beforeValidate()
    {
        if (in_array($this->type, [self::CN_TYPE_MOBILE, self::CN_TYPE_VIBER])) {
            $this->number = preg_replace('/[\-\+\(\)\s]/', '', $this->number);
            $this->number = preg_replace_callback(
                '/^(7|8)\d{10}$/',
                function ($items) {
                    return substr($items[0], 1);
                },
                $this->number
            );
        }
    }

    /**
     * Apply conditions for matching phone number.
     */
    public function scopeNumberLike(Builder $query, string $number): Builder
    {
        $numlen = strlen($number);
        if (!is_numeric($number) && $numlen >= 4) {
            $query->where('type', self::CN_TYPE_SKYPE);
            $query->where('number', 'like', $number.'%');
        } elseif (is_numeric($number) && $numlen >= 4) {
            if ($numlen > 10) {
                $query->where('id', 0);
            } elseif ($numlen == 10) {
                $query->where('number', $number);
            } else {
                $ptn = str_pad('', 10 - strlen($number), '_');
                $query->where('number', 'like', $ptn.$number);
                $query->orWhere('number', 'like', '_'.substr($number, 1).$ptn);
            }
        }

        return $query;
    }
}
