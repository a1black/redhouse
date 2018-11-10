<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Html;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Organization contact information.
 */
class Contact extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    const CN_TYPE_MOBILE = 'mobile';
    const CN_TYPE_SKYPE = 'skype';
    const CN_TYPE_VIBER = 'viber';

    public static $contactNumberTypes = [
        self::CN_TYPE_MOBILE,
        self::CN_TYPE_SKYPE,
        self::CN_TYPE_VIBER,
    ];

    /**
     * {@inheritdoc}
     */
    public $table = 'redhouse_shelter_contacts';

    /**
     * {@inheritdoc}
     */
    public $jsonable = [
        'numbers',
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
        'numbers' => 'required|array',
        'numbers.*.number' => 'required_with:numbers.*.type',
    ];

    /**
     * Custom error messages.
     *
     * @return array
     */
    public $customMessages = [
        'name.alpha_name' => 'redhouse.shelter::lang.contact.error.name',
        'numbers.*.number.contact_number' => 'redhouse.shelter::lang.contact_number.error.number',
        'numbers.required' => 'redhouse.shelter::lang.contact.error.numbers',
    ];

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
            'name' => 'alpha_name',
            'numbers.*.type' => sprintf('in:%s', implode(',', self::$contactNumberTypes)),
            'numbers.*.number' => 'contact_number:numbers.*.type',
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

        $numbers = [];
        foreach ($this->numbers as $val) {
            $val['number'] = preg_replace('/[\s\(\)-]/', '', $val['number']);
            $val['number'] = preg_replace('/^(\+?7|8)(?=\d{10})/', '', $val['number']);
            $numbers[] = $val;
        }

        $this->numbers = $numbers;
    }

    public function beforeSave()
    {
        $this->name = mb_convert_case($this->name, MB_CASE_TITLE);
        $this->note = Html::entities(Html::strip($this->note));
        $this->description = Html::entities(Html::strip($this->description));
    }

    /**
     * Returns option list for dropdown widget.
     */
    public function getTypeOptions(): array
    {
        $options = [];
        foreach (self::$contactNumberTypes as $type) {
            $options[$type] = 'redhouse.shelter::lang.contact_number.type.'.$type;
        }

        return $options;
    }

    /**
     * Returns query for selecting only published contacts.
     */
    public function scopeIsPublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }
}
