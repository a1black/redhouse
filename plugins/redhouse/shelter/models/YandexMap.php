<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Html;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;
use System\Behaviors\SettingsModel;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Organization tax information.
 */
class YandexMap extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    /**
     * Unique identifier under which settings are stored.
     *
     * @var string
     */
    public $settingsCode = 'redhouse_shelter_yandexmap';

    /** @var array */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * Form fields definitions.
     *
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules.
     *
     * @var array
     */
    public $rules = [
        'apikey' => 'required|alpha_dash',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'title' => 'required',
        'description' => 'required',
        'enabled' => 'boolean',
    ];

    /**
     * Custom validation messages.
     *
     * @var array
     */
    public $customMessages = [
        'apikey.alpha_dash' => 'redhouse.shelter::lang.mapapi.error.key',
        'latitude.numeric' => 'redhouse.shelter::lang.mapapi.error.coordinates',
        'longitude.numeric' => 'redhouse.shelter::lang.mapapi.error.coordinates',
        'title.alpha_text' => 'redhouse.shelter::lang.mapapi.error.title',
        'description.alpha_text' => 'redhouse.shelter::lang.mapapi.error.description',
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
            'title' => 'alpha_text',
            'description' => 'alpha_text',
        ];
        $validator->addRules($extraRules);

        return $validator;
    }
}
