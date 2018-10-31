<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Phone number for specific contact.
 */
class Animal extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    const AN_TYPE_DOG = 'dog';
    const AN_TYPE_CAT = 'cat';

    const HP_TYPE_OK = 'healthy';
    const HP_TYPE_BAD = 'sick';
    const HP_TYPE_RECOVER = 'recovery';

    const SEX_M = 'male';
    const SEX_F = 'female';

    /** @var array */
    public static $animalTypes = [
        self::AN_TYPE_DOG,
        self::AN_TYPE_CAT,
    ];

    /** @var array */
    public static $healthTypes = [
        self::HP_TYPE_OK,
        self::HP_TYPE_BAD,
        self::HP_TYPE_RECOVER,
    ];

    /** @var array */
    public static $sexTypes = [
        self::SEX_M,
        self::SEX_F,
    ];

    /** @inheritdoc */
    public $table = 'redhouse_shelter_animals';

    /** @var array */
    public $rules = [
        'slug' => 'required|alpha_dash',
        'type' => 'required',
        'health' => 'required',
        'sex' => 'required',
        'birthday' => 'required|date_format:Y-m-d',
        'fundraise_url' => 'url',
    ];

    /** @var array */
    public $customMessages = [
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

        // Extend validator with custom rules
        ValidatorExtensions::apply($validator);

        // Runtime dependent rules
        $extraRules = [
            'type' => sprintf('in:%s', implode(',', self::$animalTypes)),
            'health' => sprintf('in:%s', implode(',', self::$healthTypes)),
            'sex' => sprintf('in:%s', implode(',', self::$sexTypes)),
            'name' => 'alpha_name',
            'health_info' => 'alpha_text',
            'adopted_by' => 'alpha_text',
        ];
        $validator->addRules($extraRules);

        // Optional rules
        $validator->sometimes(
            'adopted_at',
            'required|date_format:Y-m-d',
            function (Fluent $request) {
                return $request->get('adopted') === true;
            }
        );

        return $validator;
    }

    /**
     * Process data before saving it into database.
     */
    public function beforeSave()
    {
        if ($this->adopted === true) {
            //$this->adopted_at = null;
            //$this->adopted_by = null;
        }
    }
}
