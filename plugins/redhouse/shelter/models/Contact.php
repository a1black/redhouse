<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Html;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use Redhouse\Shelter\Models\ContactNumber;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Organization contact information.
 */
class Contact extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    /**
     * @inheritdoc
     */
    public $table = 'redhouse_shelter_contacts';

    /** @var array */
    public $hasMany = [
        'numbers' => ['Redhouse\Shelter\Models\ContactNumber', 'delete' => true],
    ];

    /**
     * Validation rules.
     *
     * @return array
     */
    public $rules = [
        'name' => 'required|min:2|max:100',
        'note' => 'max:100',
        'description' => 'max:255',
    ];

    /**
     * Custom error messages.
     *
     * @return array
     */
    public $customMessages = [
        'alpha_name' => 'redhouse.shelter::lang.contact.error.name',
    ];

    /**
     * Returns count of contuct numbers.
     */
    public function getNumberCountAttribute(): int
    {
        return $this->numbers->count();
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

        // Extend validator
        ValidatorExtensions::apply($validator);

        // Add extra rules
        $extraRules = [
            'name' => 'name',
        ];
        $validator->addRules($extraRules);

        return $validator;
    }

    /**
     * Process input data before validation.
     */
    public function beforeValidate()
    {
        if ($this->name) {
            $this->name = mb_ereg_replace('\s{2,}', ' ', $this->name);
        }
    }

    public function beforeSave()
    {
        $this->note = Html::entities(Html::strip($this->note));
        $this->description = Html::entities(Html::strip($this->description));
    }

    /**
     * Apply where condition to list query for filtering by contuct number.
     *
     * @see Redhouse\Shelter\Models\ContuctNumber::scopeNumberLike
     */
    public function scopeFilterContactNumber(Builder $query, string $number): Builder
    {
        if (strlen($number) >= 4) {
            $query->whereHas('numbers', function ($query) use ($number) {
                $query->numberLike($number);
            });
        }

        return $query;
    }
}
