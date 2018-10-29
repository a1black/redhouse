<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;

/**
 * Phone number for specific contact.
 */
class ContactNumber extends Model
{
    use Validation;

    const MOBILE_LEN = 11;

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
    public $rules = [];

    /**
     * Custom attribute names used by validator.
     *
     * @var array
     */
    public $attributeNames = [
        'number' => 'redhouse.shelter::lang.contact_number.number_label',
        'type' => 'redhouse.shelter::lang.contact_number.type_label',
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
            '/^(\d)(\d{3})(\d{3})(\d{2})(\d{2})$/',
            function ($match) {
                if (count($match) == 6) {
                    $res = sprintf('%d(%d) %d-%d-%d', ...array_slice($match, 1));
                    return $res[0] == '7' ? '+'.$res : $res;
                }
            },
            $number
        );

        return $phone ?: $number;
    }

    /**
     * Modify model data and validation rules before validation.
     */
    public function beforeValidate()
    {
        // Set validation rules
        $this->rules['type'] = [
            'required',
            'in:'.implode(',', self::$contactNumberTypes)
        ];
        $this->rules['number'] = [
            'required',
            $this->type == self::CN_TYPE_SKYPE
                ? 'regex:/^[a-z][a-z_,\-\.]{5,31}$/i'
                : 'digits:'.self::MOBILE_LEN,
            'unique:'.$this->table,
        ];
        // Process model data
        if (in_array($this->type, [self::CN_TYPE_MOBILE, self::CN_TYPE_VIBER])) {
            $this->number = preg_replace('/[\-\+\(\)\s]/', '', $this->number);
        }
    }

    /**
     * Returns list of contuct number types.
     */
    public function listTypes(): array
    {
        $list = [];
        foreach (self::$contactNumberTypes as $type) {
            $list[$type] = 'redhouse.shelter::lang.contact_number.type.'.$type;
        }

        return $list;
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
            if ($numlen > self::MOBILE_LEN) {
                $query->where('id', 0);
            } elseif ($numlen == self::MOBILE_LEN) {
                $query->where('number', $number);
            } else {
                $ptn = str_pad('', self::MOBILE_LEN - strlen($number), '_');
                $query->where('number', 'like', $ptn.$number);
                $query->orWhere('number', 'like', '_'.substr($number, 1).$ptn);
            }
        }

        return $query;
    }
}
