<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Html;
use Carbon\Carbon;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use October\Rain\Database\Traits\Sluggable;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Phone number for specific contact.
 */
class Animal extends Model
{
    use Sluggable;
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
    public $slugs = [
        'slug' => ['type', 'name'],
    ];

    /** @var array */
    public $rules = [
        'type' => 'required',
        'health' => 'required',
        'sex' => 'required',
        'birthday' => 'required',
        'description' => 'required',
        'fundraise_url' => 'url',
    ];

    /** @var array */
    public $attributeNames = [
        'description' => 'redhouse.shelter::lang.animal.description_label',
        'health_info' => 'redhouse.shelter::lang.animal.health_info_label',
        'adopted_by' => 'redhouse.shelter::lang.animal.adopted_by_label',
    ];

    public $customMessages = [
        'description.required' => 'redhouse.shelter::lang.animal.error.desc_required',
    ];

    /** @var array */
    protected $dates = [
        'birthday',
        'adopted_at',
    ];

    /** @var array */
    public $attachMany = [
        'featured_images' => ['System\Models\File', 'order' => 'sort_order'],
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
        ];
        $validator->addRules($extraRules);

        // Optional rules
        $validator->sometimes(
            'adopted_at',
            'required',
            function (Fluent $request) {
                return $request->get('adopted') === true;
            }
        );

        return $validator;
    }

    /**
     * Process input data before validation.
     */
    public function beforeValidate()
    {
        $this->adopted = $this->adopted == 1;
    }

    /**
     * Process data before saving it into database.
     */
    public function beforeSave()
    {
        $this->description = Html::clean($this->description);
        $this->health_info = Html::clean($this->health_info) ?: null;
        $this->adopted_by = Html::clean($this->adopted_by) ?: null;
    }

    /**
     * Returns birthday date object.
     */
    public function getBirthdayAttribute($value): ?Carbon
    {
        if ($value == '' && $value == null) {
            return null;
        }

        return new Carbon($value);
    }

    /**
     * Returns adopted_at date object.
     */
    public function getAdoptedAtAttribute($value): ?Carbon
    {
        if ($value == '' && $value == null) {
            return null;
        }

        return new Carbon($value);
    }

    public function getHealthOptions(): array
    {
        $options = [];
        foreach (self::$healthTypes as $type) {
            $options[$type] = 'redhouse.shelter::lang.animal.health.'.$type;
        }

        return $options;
    }

    public function getSexOptions(): array
    {
        $options = [];
        foreach (self::$sexTypes as $sex) {
            $options[$sex] = 'redhouse.shelter::lang.animal.sex.'.$sex;
        }

        return $options;
    }

    public function getTypeOptions(): array
    {
        $options = [];
        foreach (self::$animalTypes as $type) {
            $options[$type] = 'redhouse.shelter::lang.animal.type.'.$type;
        }

        return $options;
    }

    /**
     * Returns animal age.
     * Posible values:
     *      positive - age in months
     *      negative - age in days
     *      zero     - not born yet
     */
    public function getAge(): int
    {
        $diff = $this->birthday ? $this->birthday->diff(Carbon::now()) : null;
        if (!$diff || $diff->invert) {
            $age = 0;
        } elseif (!$diff->y && !$diff->m) {
            $age = $diff->d;
        } else {
            $age = $diff->y * 12 + $diff->m + round($diff->d / 41);
        }

        return $age;
    }
}
